<?php

namespace backend\modules\finance\controllers;

use Yii;
use yii\web\Controller;
use yii\helpers\ArrayHelper;
use yii\web\NotFoundHttpException;
use common\models\AccounttopupStatus;

Class AccounttopupstatusController extends Controller
{
	/*
	 * get statusName by type
	 * 1=> get id
	 * 2=> get showName
	 * 3=> get description name
	 */
	public static function getStatusType($id,$type)
	{
		$data = self::getStatus($id);
		switch ($type) {
			case 1:
				$value = $data->id;
				break;
			case 2:
				$value = $data->title;
				break;
			default:
				$value="";
				break;
		}
		return $value;
	}

	public static function listStatus()
	{
		$data = ArrayHelper::map(AccounttopupStatus::find()->all(),'id','title');
		return $data;
	}

	protected static function getStatus($id)
	{
		if (($data = AccounttopupStatus::findOne($id)) !== null) {
            return $data;
        } else {
            throw new NotFoundHttpException('The requested id does not exist.');
        }
	}
	
	
	
	
}