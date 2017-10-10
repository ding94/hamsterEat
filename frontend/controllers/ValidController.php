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
}