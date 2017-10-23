<?php
namespace frontend\controllers;

use Yii;
use yii\web\Controller;
use yii\filters\VerbFilter;
use yii\helpers\ArrayHelper;
use yii\filters\AccessControl;
use common\models\Vouchers;
use common\models\UserVoucher;


class ValidController extends Controller
{
	public static function dateValidCheck($post,$case)
	{
		$voucher = Vouchers::find()->where('id = :id',[':id' => $post])->one();
		if (!empty($voucher)) {
			switch ($case) {
			case 1:
				if ($voucher->endDate <= strtotime(date('Y-m-d h:i:s'))) {
					Yii::$app->session->setFlash('error','Coupon was expired!');
					return false;
				}
				elseif ($voucher->endDate >= strtotime(date('Y-m-d h:i:s'))) {
					return true;
				}
				break;
			
			default:
					Yii::$app->session->setFlash('error','Something went wrong');
					return false;
				break;
			}
		}
		return true;
	}

	public static function UserCheck($post,$case)
	{
		$voucher = UserVoucher::find()->where('vid = :id',[':id' => $post])->one();
		switch ($case) {
		case 1:
			if (!empty($voucher)) {
				return true;
			}
			elseif (empty($voucher)) {
				return false;
			}
			break;
		
		default:
				Yii::$app->session->setFlash('error','Something went wrong');
				return false;
			break;
		}
		return false;
	}

	public static function voucherCheckValid($post,$case)
	{
		switch ($case) {
			case 1:
				
				if (!empty(Vouchers::find()->where('code = :c',[':c' => $post->code])->one())) 
				{
					Yii::$app->session->setFlash('error','Voucher code repeated!');
					return false;
				}
				elseif ($post->discount_type == 1 && $post->discount >=101) 
				{
					Yii::$app->session->setFlash('error','Discount cannot higher than 100% !');
					return false;
				}
				elseif ($post->discount_type == 4 && $post->discount >=501) 
				{
					Yii::$app->session->setFlash('error','Discount cannot higher than RM500 !');
					return false;
				}
				else
				{
					return true;
				}
				break;
			
			case 2:
				if ($post->discount_type == 1 && $post->discount >=101) 
				{
					Yii::$app->session->setFlash('error','Discount cannot higher than 100% !');
					return false;
				}
				elseif ($post->discount_type == 4 && $post->discount >=501) 
				{
					Yii::$app->session->setFlash('error','Discount cannot higher than RM500 !');
					return false;
				}
				else
				{
					return true;
				}
				break;

			case 3:
				return true;
				break;
				
			default:
				Yii::$app->session->setFlash('error','Something went wrong');
				return false;
				break;
		}
	}
}