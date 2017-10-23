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
		if (!empty($model)) {
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
		$this->layout = 'user';
		return $this->render("index",['model'=>$model,'voucher'=>$voucher]);
		}
		$this->layout = 'user';
		return $this->render("index",['model'=>$model]);

	}

	public static function voucherCreate($amount,$item,$type)
	{
		if (!empty($amount)) { // check null amount
			if ($item >= 7 && $item <= 10) { // check item valid
				if ($type == 2 || $type == 5) { // check type valid
					switch ($type) {
						case 2:
							$case = 1;
							break;

						case 5:
							$case = 2;
							break;
						
						default:
							$case = 3;
							break;
					}
					

					
						$voucher = new Vouchers;

						$voucher->scenario = 'save';
				      	$voucher->code = self::CodeCreate();
				        $voucher->discount = $amount;
				        $voucher->discount_type = $type;
				        $voucher->discount_item = $item;
				        $voucher->usedTimes = 0;
				        $voucher->inCharge = 0;
				      	$voucher->startDate = time();
				      	$voucher->endDate = strtotime(date('Y-m-d h:i:s',strtotime('+30 day')));
				      	$valid = ValidController::VoucherCheckValid($voucher,$case); //check voucher valid
				      	if ($valid == true) {
				      		return $voucher;
				      	}
				      	
					
				}
			}
		}
	}

	public static function codeCreate()
	{
		$chars ="ABCDEFGHIJKLMNOPQRSTUVWXYZ1234567890";//code 包含字母
		$code = "";
		for($i=0;$i<16; $i++)
        {
       		$code .= $chars[rand(0,strlen($chars)-1)];
    	}
    	return $code;
	}

	public static function userInviteReward($id,$vid,$code,$endDate)
	{
		$uservoucher = new UserVoucher;
		$uservoucher->scenario = 'save';
		$uservoucher->uid = $id;
		$uservoucher->vid = $vid;
		$uservoucher->code = $code;
		$uservoucher->endDate = $endDate;

		return $uservoucher;
	}	
}
