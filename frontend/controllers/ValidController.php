<?php
namespace frontend\controllers;

use Yii;
use yii\web\Controller;
use yii\filters\VerbFilter;
use yii\helpers\ArrayHelper;
use yii\filters\AccessControl;
use common\models\vouchers\Vouchers;
use common\models\vouchers\UserVoucher;
use common\models\food\Food;
use common\models\Restaurant;
use common\models\food\Foodstatus;


class ValidController extends Controller
{
	public static function restaurantValid($id)
	{
		$valid = Restaurant::find()->where('Restaurant_ID=:id AND Restaurant_Status=:s',[':id'=>$id,':s'=>3])->one();
		
		if (!empty($valid)) {
			return true;
		}
		else{
			return false;
		}
	}

	public static function checkUserValid($id)
	{
		if ($id == Yii::$app->user->identity->id) {
            return true;
        }
        else
        {
            Yii::$app->session->setFlash('error', Yii::t('common','You are not allow to perfrom this action!'));
        }
	}

	public static function foodValid($id)
	{
		$valid = Foodstatus::find()->where('Food_ID=:id AND Status=:s',[':id'=>$id,':s'=>1])->andWhere(['>','food_limit','0'])->one();
		if ($valid) {
			return true;
		}
		else{
			return false;
		}
		
	}

	public static function dateValidCheck($post,$case)
	{
		$voucher = Vouchers::find()->where('code = :c',[':c' => $post])->one();
		if (!empty($voucher)) {
			if($voucher['status'] == 5)
			{
				return true;
			}
			else
			{
				switch ($case) {
				case 1:
					if ($voucher->endDate <= strtotime(date('Y-m-d h:i:s'))) {
						if ($voucher['status'] != 5) {
				            $voucher['status'] = 4;
				        	$voucher->save(false);
				        }
						Yii::$app->session->setFlash('error',Yii::t('discount','Coupon was expired!'));
						return false;
					}
					elseif ($voucher->endDate >= strtotime(date('Y-m-d h:i:s'))) {
						return true;
					}
					break;
				
				default:
						Yii::$app->session->setFlash('error',Yii::t('cart','Something Went Wrong!'));
						return false;
					break;
				}
			}
		}
		return false;
	}

	public static function UserCheck($post,$case)
	{
		$voucher = UserVoucher::find()->where('code = :c',[':c' => $post])->one();
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
				Yii::$app->session->setFlash('error',Yii::t('cart','Something Went Wrong!'));
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
					Yii::$app->session->setFlash('error',Yii::t('discount','Voucher code repeated!'));
					return false;
				}
				elseif ($post->discount_type == 1 && $voucher->discount >=101) 
				{
					Yii::$app->session->setFlash('error',Yii::t('discount','Discount cannot higher than 100% !'));
					return false;
				}
				elseif ($post->discount_type == 2 && $voucher->discount >=501) 
				{
					Yii::$app->session->setFlash('error',Yii::t('discount','Discount cannot higher than RM500 !'));
					return false;
				}
				else
				{
					return true;
				}
				break;
			
			case 2:
				if ($post->discount_type == 1 && $voucher->discount >=101) 
				{
					Yii::$app->session->setFlash('error',Yii::t('discount','Discount cannot higher than 100% !'));
					return false;
				}
				elseif ($post->discount_type == 2 && $voucher->discount >=501) 
				{
					Yii::$app->session->setFlash('error',Yii::t('discount','Discount cannot higher than RM500 !'));
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
				Yii::$app->session->setFlash('error',Yii::t('cart','Something Went Wrong!'));
				return false;
				break;
		}
	}
}