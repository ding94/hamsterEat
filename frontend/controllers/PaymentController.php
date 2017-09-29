<?php

namespace frontend\controllers;

use common\models\Payment;

use Yii;

class PaymentController extends \yii\web\Controller
{
	public static function Payment($did,$checkout)
	{
		$payment = new Payment();
		
		var_dump($checkout);exit;
	} 
}