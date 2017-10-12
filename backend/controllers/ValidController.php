<?php
namespace backend\controllers;

use Yii;
use yii\web\Controller;
use yii\filters\VerbFilter;
use yii\helpers\ArrayHelper;
use yii\filters\AccessControl;
use common\models\Vouchers;

class ValidController extends Controller
{
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

	public static function saveValidCheck($post,$case)
	{
		if ($post->validate()) {
			$post->save();
			switch ($case) 
			{
				case 1:
					Yii::$app->session->setFlash('success','Voucher Saved!');
					break;
				
				default:
					Yii::$app->session->setFlash('success','Saved!');
					break;
			}
			return true;
		}
		else
		{
			return false;
		}
	}

	public static function userVoucherCheckValid($model,$voucher,$case)
	{
		switch ($case) 
		{
			case 1:
				$check = Vouchers::find()->where('code = :c',[':c' => $model['code']])->one(); // check voucer exist
				if (!empty($check)) 
				{
					if ($check->discount_type!=1 && $check->discount_type !=4) //check voucher status
					{
						Yii::$app->session->setFlash('error','Voucher assigned or used!');
						return false;
					}
				}
				elseif(empty($check)) 
				{
					if (empty($voucher['discount'])) //check discount
					{
						Yii::$app->session->setFlash('error','Lack of discount amount!');
						return false;
					}
					$valid = self::VoucherCheckValid($voucher,2); //check exceed amount
					if ($valid==false) {
						if ($voucher->discount_type == 1) {
							Yii::$app->session->setFlash('error','Discount cannot higher than 100% !');
							return false;
						}
						elseif ($voucher->discount_type == 4) {
							Yii::$app->session->setFlash('error','Discount cannot higher than RM500 !');
							return false;
						}
					}
				}
				return true;
				break;
			
			default:
				Yii::$app->session->setFlash('error','Something went wrong');
				return false;
				break;
		}
	}

	public static function dateValidCheck($post,$case)
	{
		var_dump('expression');exit;
	}
}
