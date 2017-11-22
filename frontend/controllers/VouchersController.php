<?php

namespace frontend\controllers;
use Yii;
use yii\helpers\ArrayHelper;
use common\models\Vouchers;
use common\models\UserVoucher;
use common\models\VouchersUsed;
use common\models\VouchersType;
use yii\filters\AccessControl;
use frontend\controllers\CommonController;

class VouchersController extends CommonController
{
	public function behaviors()
    {
         return [
             'access' => [
                 'class' => AccessControl::className(),
                 'rules' => [
                    [
                        'actions' => ['index'],
                        'allow' => true,
                        'roles' => ['@'],

                    ],
                    //['actions' => [''],'allow' => true,'roles' => ['?'],],
                    
                 ]
             ]
        ];
    }

	public function actionIndex()
	{
		$model = UserVoucher::find()->where('uid = :id',[':id'=>Yii::$app->user->identity->id])->all();
		$key = 0;
		$this->layout = 'user';
		if (!empty($model)) {
			foreach ($model as $k => $var) {
				/*
				$model = user's vouchers
				$var = user each voucher
				$vouchers = vouchers with same code
				$vou = each voucher details
				$uservoucher = new variable to store each vouchers details
				*/
				$vouchers = Vouchers::find()->where('code=:c',[':c'=>$var['code']])->all();
				foreach ($vouchers as $ke => $vou) {
					$uservoucher[$key]['code'] = $vou['code'];
					$uservoucher[$key]['endDate'] = date('Y-m-d', $vou['endDate']);
					$uservoucher[$key]['discount_item'] = VouchersType::find()->where('id=:id',[':id'=>$vou['discount_item']])->one()->description;
					if ($vou['discount_type']>=1 && $vou['discount_type']>=4) {
						$uservoucher[$key]['discount'] = "RM ".$vou['discount'];
					}
					else
					{
						$uservoucher[$key]['discount'] = $vou['discount'].' %';
					}
					$key=$key+1;
				}
			}
			return $this->render("index",['model'=>$model,'uservoucher'=>$uservoucher]);
		}
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

	public static function endvoucher($code)
	{
		$voucher = Vouchers::find()->where('code=:c',[':c'=>$code])->all();
		if (!empty($voucher)) {
			foreach ($voucher as $k => $vou) {
				if ($vou['discount_type'] != 100) {
					if ($vou['discount_type'] != 101) {
						$vou['discount_type'] += 1;
					}
				}
				$vou['usedTimes'] += 1;
				if ($vou->validate()) {
					$use = new VouchersUsed;
					$use['vid'] = $vou['id'];
					$use['uid'] = Yii::$app->user->identity->id;
					$use['usedDate'] = time();
					$vou->save();
					$use->save();
				}
			}
		}
	}	
}
