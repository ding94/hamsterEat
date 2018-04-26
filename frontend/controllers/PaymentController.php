<?php

namespace frontend\controllers;

use common\models\Payment;
use common\models\Account\Accountbalance;
use common\models\Order\{Orders,Orderitem,DeliveryAddress};
use common\models\PaymentGateWay\{PaymentCollection,PaymentBill,PaymentGatewayHistory};
use frontend\controllers\MemberpointController;
use frontend\controllers\CommonController;
use Yii;
use yii\web\NotFoundHttpException;
use yii\filters\AccessControl;
use yii\base\Model;
use yii\helpers\Json;

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
                    [
                        'actions' => ['process-payment','payment-post','payment-gateway','notify'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                    [
                        'actions' => ['detect-payment','close-session'],
                        'allow' => true,
                        'roles' => ['?','@'],
                    ],
                ],  
            ],
        ];
    }

    public function actionProcessPayment($did)
    {
        $order = $this->findOrder($did);
        if(empty($order))
        {
            Yii::$app->session->setFlash('warning',Yii::t('food','Something Went Wrong. Please Try Again Later!'));
            return $this->redirect(['site/index']);
        }
        $balance = Accountbalance::find()->where('User_Username = :User_Username',[':User_Username' => Yii::$app->user->identity->username])->one();
        if($order->User_Username != $balance->User_Username || $order->Orders_Status != 1)
        {
            throw new NotFoundHttpException('Wrong Request.');
        }
        return $this->render('process',['order'=>$order,'balance'=>$balance]);
    }

    public function actionPaymentPost()
    {
        $post = Yii::$app->request->post();
        
        if(empty($post['account-balance']))
        {
            Yii::$app->session->setFlash('warning', Yii::t('payment','Please Choose A Method'));
            return $this->redirect(Yii::$app->request->referrer);
        }

        if($post['account-balance'] == 2)
        {
            return $this->redirect(['payment-gateway','did'=>$post['did']]);
        }
       
        $isValid = $this->getPayment();
        if($isValid)
        {
             return $this->redirect(['/cart/aftercheckout','did'=>$post['did']]);
        }  
        
        return $this->redirect(Yii::$app->request->referrer);
    }

    public function actionPaymentGateway($did)
    {
        $payment = Payment::find()->where('item_id = :did',[':did'=>$did])->one();
        if(empty($payment))
        {
            $collect = PaymentCollection::generateCollection($did);
            $address = DeliveryAddress::findOne($did);
            $order = $this->findOrder($did);
            if(empty($address) || empty($order))
            {
                return $this->redirect(['process-payment','did'=>$did]);
            }

            $payment = $this->generatePayment($order->Orders_TotalPrice,$order->Delivery_ID,'2');
            if($payment->save())
            {
                if($collect['value'] == 1)
                {
                    $bill = PaymentBill::generateBill($collect['id'],Yii::$app->user->identity->email,$address->name,$address->contactno,$order->Orders_TotalPrice,$payment->id);
                    if($bill['value'] == 1)
                    {
                        return $this->redirect($bill['link']);
                    }

                } 
            }
        }
        else
        {
            $history = PaymentGatewayHistory::find()->where("pid = :pid",[':pid'=>$payment->id])->one();
           
            $billData =  PaymentBill::getBill($history->bill_id);

            if($billData['value'] == 1)
            {
                PaymentBill::generateCookie($history->collect_id,$history->bill_id);
                return $this->redirect($billData['data']['url']);
            }
        }
       
        Yii::$app->session->setFlash('warning',Yii::t('food','Something Went Wrong. Please Try Again Later!'));
        return $this->redirect(['process-payment','did'=>$did]);
    }

    public function actionDetectPayment()
    {
        $array = array(
            'value' => 0,
            'link' =>"",
        );
        if(!Yii::$app->user->isGuest)
        {
            $session = Yii::$app->session;
            $payment = $session->get('payment');
            $controller = Yii::$app->controller->id;
            $action = Yii::$app->controller->action->id;
            $permissionName = $controller.'/'.$action; 
            if($permissionName != "payment/process-payment")
            {
                if(!empty($payment))
                {
                    $data = self::detectPaymentAvaiable($payment['bill_id'],$payment['collect_id']);
                    
                    return Json::encode($data);
                }
            }
        }
        return Json::encode($array);
    }

    public function actionNotify()
    {
        $get = Yii::$app->request->get();
        $billData =  PaymentBill::getBill($get['billplz']['id']);
        if($billData['value'] != 1)
        {
            Yii::$app->session->setFlash('warning',Yii::t('food','Something Went Wrong. Please Try Again Later!'));
            return $this->redirect(['site/index']);
        }
        $billapi = $billData['data'];
        $bill = PaymentGatewayHistory::find()->where('collect_id = :cid and bill_id = :bid',[':cid'=>$billapi['collection_id'],':bid'=>$billapi['id']])->joinWith(['p'])->one();
        $did = $bill->p->item_id;
        
        if($billapi['paid'] && $billapi['state'] == 'paid')
        {
            $bill->status = 1;
            $bill->save();
            $order = $this->findOrder($did);
            if(!empty($order))
            {
                $order = $this->updateOrderStatus($order,2);
                $this->saveStatus($order);
            }
          
            return $this->redirect(['/cart/aftercheckout','did'=>$did]);
        }

        Yii::$app->session->setFlash('warning',Yii::t('food','Something Went Wrong. Please Try Again Later!'));
        return $this->redirect(['process-payment','did'=>$did]);
    }

    public function actionCloseSession()
    {
        $this->close();
        return  "test";
    }

    protected static function getPayment()
    {
        $post = Yii::$app->request->post();
        $type = $post['account-balance'];
      
        $exists = self::findOrder($post['did']);
        if(empty($exists))
        {
            return false;
        }
        $order = self::updateOrderStatus($exists,$type);
      
        switch ($type) {
            case '1':
                $isValid = self::accountBalancePayment($order);
                break;
            case '3':
                $isValid = self::saveStatus($order);
                break;
            default:
                $isValid =false;
                break;
        }
       return $isValid;
    }

    /*
    * generate payment detail and detect user account balance 
    */
	public static function AccountBalancePayment($order)
	{
		$userbalance = Accountbalance::find()->where('User_Username = :User_Username',[':User_Username' => Yii::$app->user->identity->username])->one();
        $price  = $order->Orders_TotalPrice;
		if ($userbalance->User_Balance >= $price ) 
        {
            $valid = self::updatePayment($order,$userbalance);
            if($valid)
            {
                self::close();
                return true;
            }
        }
        else
        {
            Yii::$app->session->setFlash('warning', Yii::t('payment','Payment failed! Insufficient Funds.'));
        }
       
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
                    Yii::$app->session->setFlash('success', Yii::t('payment','Payment Successful'));
                    return true;
                }
               
        } 
        else {
            Yii::$app->session->setFlash('warning', Yii::t('payment','Payment failed! Insufficient Funds.'));
        }
        return false;
    }

    public static function refund($refund,$name,$did,$type)
    {
         $acc = Accountbalance::find()->where('User_Username=:us',[':us'=>$name])->one();
        $acc->type = $type;
        $acc->deliveryid = $did;
        $acc->defaultAmount = $refund;

        $acc['User_Balance'] += $refund;
        $acc['AB_minus'] -= $refund;
        return $acc;
    }

    /*
    * find order;
    */
    protected static function findOrder($id)
    {
        $model = Orders::find()->where('orders.Delivery_ID = :id and Orders_Status = 1',[':id'=>$id])->joinWith(['item'])->one();
        if(empty($model))
        {
            return "";
        }
        return $model;
    }

    /*
    * update order status
    * or payment status
    * return empty if validate wrong
    */
    protected static function updateOrderStatus($order,$changePayment)
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
    protected static function saveStatus($order)
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
            self::close();
            return true;
        }

        Orderitem::updateAll(['OrderItem_Status'=>1],'Delivery_ID = :id',[':id'=>$order->Delivery_ID]);
        return false;
    }

    /*
    * update payment for account balance
    */
    protected static function updatePayment($order,$userbalance)
    {
        $price = $order->Orders_TotalPrice;

        $payment = self::generatePayment($price,$order->Delivery_ID,'1');

        $userbalance->User_Balance -= $price; /* order price amount */
        $userbalance->AB_minus += $price;
        $userbalance->type = 5;
        $userbalance->deliveryid = $order->Delivery_ID;
        $userbalance->defaultAmount = $price;
        $valid = $userbalance->validate() && $payment->validate();
        if($valid)
        {
            $transaction = Yii::$app->db->beginTransaction();
            try{
                foreach($order->item as $value)
                {
                    if(!$value->save())
                    {
                        break;
                    }
                }
                if($order->save() && $payment->save() && $userbalance->save())
                {
                    $transaction->commit();
                    return true;
                }
            }
            catch(Exception $e)
            {
                $transaction->rollBack();
            }
        }
       
        Yii::$app->session->setFlash('warning',Yii::t('food','Something Went Wrong. Please Try Again Later!'));
        return false;
    }

    protected static function generatePayment($price,$did,$type)
    {
        $payment = new Payment;
        $payment->uid = Yii::$app->user->identity->id;
        $payment->paid_type = $type;
        $payment->paid_amount = $price; /* order price amount */
        $payment->item_id = $did;
        return $payment;
    }

    protected static function detectPaymentAvaiable($billid,$collectid)
    {
        $array = array(
            'value' => 0,
            'link' =>"",
        );

        $bill = PaymentGatewayHistory::find()->where('collect_id = :cid and bill_id = :bid and uid = :uid',[':cid'=>$collectid,':bid'=>$billid,':uid'=>Yii::$app->user->identity->id])->joinWith(['p'])->one(); 
        $billData =  PaymentBill::getBill($billid);

        if(empty($bill) || $billData['value'] != 1)
        {
            return $array;
        }
        
        $billApi = $billData['data'];
        if($bill->status == 0)
        {
            if($billApi['paid'] && $billApi['state'] == 'paid')
            {
                $bill->status = 1;
                $bill->save();
                $array['value']=1;
                $array['link']='Your Payment Has Successful Paid';
            }
            else
            {
                $array['value'] = 2;
                $array['link'] = $billApi['url'];
            }
        }
       return $array;
    }

    protected static function close()
    {
        $session = Yii::$app->session;
        $session->remove('payment');
    }
}