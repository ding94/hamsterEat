<?php

namespace frontend\controllers;
use Yii;
use yii\helpers\ArrayHelper;
use common\models\Vouchers;
use common\models\UserVoucher;
use common\models\VouchersType;

class VouchersController extends \yii\web\Controller
{
	public function actionIndex()
	{
		$model = UserVoucher::find()->where('uid = :id',[':id'=>Yii::$app->user->identity->id])->all();
		foreach ($model as $k => $var) {
			$voucher[$k] = Vouchers::find()->where('id = :id',[':id'=>$model[$k]['vid']])->one();
			$voucher[$k]['endDate'] = date('Y-m-d', $voucher[$k]['endDate']);
			$voucher[$k]['discount_item'] = VouchersType::find()->where('id=:id',[':id'=>$voucher[$k]['discount_item']])->one()->description;
			if ($voucher[$k]['discount_type']>=1 && $voucher[$k]['discount_type']>=4) {
				$voucher[$k]['discount'] = "RM ".$voucher[$k]['discount'];
			}
			else
			{
				$voucher[$k]['discount'] = $voucher[$k]['discount'].' %';
			}
		}
		
		//var_dump($voucher);exit;
		return $this->render("index",['model'=>$model,'voucher'=>$voucher]);

	}
}
