<?php

namespace frontend\controllers;

use common\models\Payment;
use common\models\Accountbalance;

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
                $userbalance->AB_topup -= $order->Orders_TotalPrice; /* order price amount */
                
                $userbalance->save();

                Yii::$app->session->setFlash('success', 'Payment Successful');
            } else {
                Yii::$app->session->setFlash('warning', 'Payment failed! Insufficient Funds.');
            }
		return true;
	} 
}