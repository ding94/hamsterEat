<?php

namespace backend\modules\finance\controllers;

use Yii;
use yii\web\Controller;
use yii\data\ActiveDataProvider;
use backend\modules\finance\controllers\AccounttopupstatusController;
use common\models\Account\AcounttopupOperate;

Class AccounttopupoperateController extends Controller
{
	/*
	 * list all operate that parcel done without search
	 */
	

	/*
	 * create new operate
	 */

	public static function createOperate($tid,$status,$type)
	{
		//var_dump($status);exit;
		$oldOperate = AcounttopupOperate::find()->where('tid = :id' ,[':id' => $tid])->orderBy('updated_at DESC')->one();

		if(empty($oldOperate))
		{
			$old = "";
		}
		else
		{
			$old = $oldOperate->newVal;
		}

		$operate = new AcounttopupOperate;
		$operate->adminname = Yii::$app->user->identity->adminname;
    	$operate->tid = $tid;
    	$operate->oldVal = $old;

		if($type == 1)
		{
			$statusName = AccounttopupstatusController::getStatusType($status,2);
			$operate->newVal = $statusName;
			$operate->type = "Status";
			//var_dump($operate); exit;
		}
		elseif($type == 2)
		{
			$operate->newVal = "123";
			$operate->type = "Data";
		}
    	
    	return $operate;
	}
}