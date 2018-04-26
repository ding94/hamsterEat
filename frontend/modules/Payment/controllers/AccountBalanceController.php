<?php

namespace frontend\modules\Payment\controllers;

use Yii;
use yii\filters\AccessControl;
use common\models\Account\Accountbalance;
use frontend\controllers\CommonController;

class AccountBalanceController extends CommonController
{

    public static function paying()
    {
    	$post = Yii::$app->request->post();
       	$type = $post['payment-type'];
      	
        $exists = DefaultController::findOrder($post['did']);
        if(empty($exists))
        {
            return false;
        }
        $order = DefaultController::updateOrderStatus($exists,$type);
      
        switch ($type) {
            case '1':
                $isValid = self::payment($order);
                break;
            case '3':
                $isValid = DefaultController::saveStatus($order);
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
	protected static function payment($order)
	{
		$userbalance = Accountbalance::find()->where('User_Username = :User_Username',[':User_Username' => Yii::$app->user->identity->username])->one();
        $price  = $order->Orders_TotalPrice;
		if ($userbalance->User_Balance >= $price ) 
        {
            $valid = self::updatePayment($order,$userbalance);
            if($valid)
            {
                OnlineBankingController::close();
                return true;
            }
        }
        else
        {
            Yii::$app->session->setFlash('warning', Yii::t('payment','Payment failed! Insufficient Funds.'));
        }
       
		return false;
	}

	  /*
    * update payment for account balance
    */
    protected static function updatePayment($order,$userbalance)
    {
        $price = $order->Orders_TotalPrice;

        $payment = DefaultController::generatePayment('1',$price,$order->Delivery_ID);

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

}