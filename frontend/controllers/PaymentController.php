<?php

namespace frontend\controllers;

use common\models\Payment;
use common\models\Account\Accountbalance;
use frontend\controllers\MemberpointController;
use Yii;

class PaymentController extends \yii\web\Controller
{
	public static function Payment($did,$order)
	{

		$payment = new Payment();
		$userbalance = Accountbalance::find()->where('User_Username = :User_Username',[':User_Username' => $order->User_Username])->one();
		$payment->uid = Yii::$app->user->identity->id;
		$payment->paid_type = 1;
		if ($userbalance->AB_topup >= $order->Orders_TotalPrice ) {
                $payment->paid_amount = $order->Orders_TotalPrice; /* order price amount */
                $payment->item = $did;
                $payment->original_price = $order->Orders_TotalPrice;
                $payment->save();

                $userbalance->User_Balance -= $order->Orders_TotalPrice; /* order price amount */
                $userbalance->save();

                Yii::$app->session->setFlash('success', 'Payment Successful');
            } else {
                Yii::$app->session->setFlash('warning', 'Payment failed! Insufficient Funds.');
            }
		return true;
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