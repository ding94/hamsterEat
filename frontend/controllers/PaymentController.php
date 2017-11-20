<?php

namespace frontend\controllers;

use common\models\Payment;
use common\models\Account\Accountbalance;
use frontend\controllers\MemberpointController;
use Yii;
use frontend\controllers\CommonController;

class PaymentController extends CommonController
{
	public static function Payment($price)
	{
        $data = [];
		$payment = new Payment();
		$userbalance = Accountbalance::find()->where('User_Username = :User_Username',[':User_Username' => Yii::$app->user->identity->username])->one();
		$payment->uid = Yii::$app->user->identity->id;
		$payment->paid_type = 1;
       
		if ($userbalance->User_Balance >= $price ) {
                $payment->paid_amount = $price; /* order price amount */
                //$payment->item = $did;
                $payment->original_price = $price;

                $userbalance->User_Balance -= $price; /* order price amount */
                $userbalance->AB_minus += $price;
                $userbalance->type = 5;
                $userbalance->deliveryid = $did;
                $userbalance->defaultAmount = $price;
                $data[0] = $payment;
                $data[1] = $userbalance;
                return $data;
                /*if($userbalance->save() && $payment->save())
                {
                    Yii::$app->session->setFlash('success', 'Payment Successful');
                    return true;
                }*/
        } 
        Yii::$app->session->setFlash('warning', 'Payment failed! Insufficient Funds.');
		return -1;
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
}