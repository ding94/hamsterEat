<?php

namespace frontend\controllers;

use common\models\Payment;
use common\models\Account\Accountbalance;
use common\models\Order\Orders;
use frontend\controllers\MemberpointController;
use frontend\controllers\CommonController;
use Yii;
use yii\web\NotFoundHttpException;
use yii\filters\AccessControl;

class PaymentController extends CommonController
{
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => \yii\filters\VerbFilter::className(),
                'actions' => [
                    'process-payment'  => ['GET'],
                    'payment-post'   => ['POST'],
                   
                ],
            ],
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    'actions' => [
                    'actions' => ['process-payment','payment-post'],
                    'allow' => true,
                    'roles' => ['@'],
                    ],
                ],  
            ],
        ];
    }

    public function actionProcessPayment($did)
    {
        $order = $this->findOrder($did);
        $balance = Accountbalance::find()->where('User_Username = :User_Username',[':User_Username' => Yii::$app->user->identity->username])->one();
        if($order->User_Username != $balance->User_Username || $order->Orders_Status != "Not Paid")
        {
            throw new NotFoundHttpException('Wrong Request.');
        }
        return $this->render('process',['order'=>$order,'balance'=>$balance]);
    }

    public function actionPaymentPost()
    {
        $post = Yii::$app->request->post();
        
        
        if(empty($post['type']))
        {
            Yii::$app->session->setFlash('warning', 'Please Choose A Payment Method');
            return $this->redirect(Yii::$app->request->referrer);
        }

        if($post['type'] == 1)
        {
            $order = $this->findOrder($post['did']);
            $isValid = $this->Payment($order->Orders_TotalPrice,$post['did']);
            if($isValid)
            {
                $this->updateOrderStatus($post['did']);
                NotificationController::createNotification($post['did'],3);
                return $this->redirect(['/cart/aftercheckout','did'=>$post['did']]);
            }
        }
        return $this->redirect(Yii::$app->request->referrer);
    }

	public static function Payment($price,$did)
	{
		$payment = new Payment();
		$userbalance = Accountbalance::find()->where('User_Username = :User_Username',[':User_Username' => Yii::$app->user->identity->username])->one();
		$payment->uid = Yii::$app->user->identity->id;
		$payment->paid_type = 1;
       
		if ($userbalance->User_Balance >= $price ) {
                $payment->paid_amount = $price; /* order price amount */
                $payment->item = $did;
                $payment->original_price = $price;

                $userbalance->User_Balance -= $price; /* order price amount */
                $userbalance->AB_minus += $price;
                $userbalance->type = 5;
                $userbalance->deliveryid = $did;
                $userbalance->defaultAmount = $price;
              
                if($userbalance->save() && $payment->save())
                {
                    return true;
                }
        } 
        Yii::$app->session->setFlash('warning', 'Payment failed! Insufficient Funds.');
		return false;
	}

    public static function subScribePayment($price,$pid)
    {
        $payment = new Payment();
        $userbalance = Accountbalance::find()->where('User_Username = :name',[':name' => Yii::$app->user->identity->username])->one();
        $payment->uid = Yii::$app->user->identity->id;
        $payment->paid_type = 1;
        
        if ($userbalance->User_Balance >= $price ) {

                $payment->paid_amount = $price; /* order price amount */
                $payment->item = (String)$pid;
                $payment->original_price = $price;
                
                $userbalance->User_Balance -= $price; /* order price amount */
                
                if($userbalance->save() &&  $payment->save())
                {   
                    MemberpointController::addMemberpoint($price,1);
                    Yii::$app->session->setFlash('success', 'Payment Successful');
                    return true;
                }
               
            } else {
                Yii::$app->session->setFlash('warning', 'Payment failed! Insufficient Funds.');
        }
        return false;
    }

    protected static function updateOrderStatus($did)
    {
        $order = Orders::find()->where('orders.Delivery_ID = :id',[':id'=>$did])->joinWith(['item'])->one();
        $order->Orders_Status = "Pending";
        foreach($order['item'] as $item)
        {
            $item->OrderItem_Status = "Pending";
            $item->save();
        }
        $order->save();
    }

    public static function findOrder($id)
    {
        if (($model = Orders::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}