<?php

namespace frontend\modules\Payment\controllers;

use Yii;
use yii\filters\AccessControl;
use yii\base\Model;
use yii\helpers\Json;
use frontend\controllers\CommonController;
use common\models\Order\{Orders};
use common\models\Account\Accountbalance;
use common\models\Payment;

/**
 * Default controller for the `Payment` module
 */
class DefaultController extends CommonController
{
	public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'actions' => ['process','payment-post','payment-cancel'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                    [
                    	'actions'=>['close-session'],
                    	'allow'=>true,
                    	'roles' => ['?','@'],
                    ]
                ],  
            ],
        ];
    }

    /*
    * payment process index page
    * user choose payment 
    */
    public function actionProcess($did)
    {
    	$order = $this->findOrder($did);
    	$balance = Accountbalance::find()->where('User_Username = :User_Username',[':User_Username' => Yii::$app->user->identity->username])->one();
    	if(empty($order)|| empty($balance))
    	{
    		 Yii::$app->session->setFlash('warning',Yii::t('food','Something Went Wrong. Please Try Again Later!'));
            return $this->redirect(Yii::$app->request->referrer);
    	}

    	return $this->render('process',['order'=>$order,'balance'=>$balance]);
    }

    /*
    * post page for process page
    * using online banking redirect to online-banking page
    */
    public function actionPaymentPost()
    {
    	$post = Yii::$app->request->post();
        $time_valid = CommonController::getOrdertime();

        if ($time_valid == false) {
            return $this->redirect(Yii::$app->request->referrer);
        }
    	if(empty($post)|| empty($post['payment-type']) || empty($post['did']))
    	{
    		Yii::$app->session->setFlash('warning', Yii::t('payment','Please Choose A Method'));
            return $this->redirect(Yii::$app->request->referrer);
    	}

    	if($post['payment-type'] == 2)
        {
            return $this->redirect(['/payment/online-banking/billing','did'=>$post['did']]);
        }

       	$valid = AccountBalanceController::paying();
       	if($valid)
        {
             return $this->redirect(['/cart/aftercheckout','did'=>$post['did']]);
        }  
        
        return $this->redirect(Yii::$app->request->referrer);	
        
    }

    public function actionPaymentCancel($did)
    {
        $order = Orders::findOne($did);
        $order['Orders_Status'] = 8;
        if ($order->validate()) {
            foreach ($order['item'] as $k => $value) {
                $value['OrderItem_Status'] = 8;
                $value->save(false);
            }
            $order->save();
            Yii::$app->session->setFlash('success', Yii::t('payment','Cancel Success!'));
            return $this->redirect(['/order/my-orders']);
        }
    }

    public function actionCloseSession()
    {
        OnlineBankingController::close();
    }

    public static function generatePayment($type,$price,$did)
    {
    	$payment = new Payment;
        $payment->uid = Yii::$app->user->identity->id;
        $payment->paid_type = $type;
        $payment->paid_amount = $price; /* order price amount */
        $payment->item_id = $did;
        return $payment;
    }


    /*
    * update order status
    * or payment status
    * return empty if validate wrong
    */
    public static function updateOrderStatus($order,$changePayment)
    {
        if($changePayment == 3)
        {
            $order['Orders_PaymentMethod'] = 'Cash on Delivery';
        }
        elseif($changePayment == 2)
        {
             $order['Orders_PaymentMethod'] = 'Online Banking';
        }

        $order->Orders_Status = 2;
        
        foreach($order['item'] as $item)
        {
            $item->OrderItem_Status = 2;
           
        }

        $valid = Model::validateMultiple($order->item) && $order->validate();
        if($valid)
        {
            return $order;
        }
        return "";
    }

    /*
    * save order status
    */
    public static function saveStatus($order)
    {
        foreach ($order->item as $key => $value) {
            if(!$value->save())
            {
                Orderitem::updateAll(['OrderItem_Status'=>1],'Delivery_ID = :id',[':id'=>$order->Delivery_ID]);
                return false;
            }
        }

        if($order->save())
        {
            OnlineBankingController::close();
            return true;
        }

        Orderitem::updateAll(['OrderItem_Status'=>1],'Delivery_ID = :id',[':id'=>$order->Delivery_ID]);
        return false;
    }


    /*
    * find order;
    */
    public static function findOrder($id)
    {
        $model = Orders::find()->where('orders.Delivery_ID = :id and Orders_Status = 1',[':id'=>$id])->joinWith(['item'])->one();
        if(empty($model))
        {
            return "";
        }
        return $model;
    }
}
