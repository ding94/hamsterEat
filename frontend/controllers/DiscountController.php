<?php
namespace frontend\controllers;

use Yii;
use yii\web\Controller;
use yii\filters\VerbFilter;
use yii\helpers\ArrayHelper;
use yii\filters\AccessControl;
use common\models\Vouchers;
use common\models\UserVoucher;


class DiscountController extends Controller
{
	//price after discount
	public static function discount($post,$price)
	{
		$voucher = Vouchers::find()->where('id =:id',[':id'=>$post])->one();
		
		if ($voucher['discount_type'] >= 1 && $voucher['discount_type'] <= 3) {
			$price = $price * ((100 - $voucher['discount']) / 100);
		}
		elseif ($voucher['discount_type'] >= 4 && $voucher['discount_type'] <= 6) {
			$price = $price - $voucher['discount'];
		}

		return $price;
	}

	//discounted amount
	public static function reversediscount($post,$price)
	{
		$voucher = Vouchers::find()->where('id =:id',[':id'=>$post])->one();
		
		if ($voucher['discount_type'] >= 1 && $voucher['discount_type'] <= 3) {
			$price = (($price*$voucher['discount']) / 100);
		}
		elseif ($voucher['discount_type'] >= 4 && $voucher['discount_type'] <= 6) {
			$price = $voucher['discount'];
		}

		return $price;
	}

}