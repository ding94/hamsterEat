<?php
namespace frontend\controllers;

use Yii;
use yii\web\Controller;
use common\models\Account\Memberpoint;

class MemberpointController extends Controller
{
	public static function addMemberpoint($amount,$type)
	{
		$memberpoint = Memberpoint::find()->where('uid = :uid',[':uid' => Yii::$app->user->identity->id])->one();
		$amount = round($amount);
		$memberpoint->amount = $amount;
		$memberpoint->type = $type;

		switch ($type) {
			case 1:
				$memberpoint->point += $amount;
				$memberpoint->positive += round($amount);
				break;
			case 2:
				$memberpoint->point -= $amount;
				$memberpoint->negative += round($amount);
			default:
				
				break;
		}
		$memberpoint->save();
	}
}