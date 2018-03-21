<?php

namespace frontend\modules\notification\controllers;

use Yii;
use yii\web\Controller;
use common\models\user\UserNotification;
use common\models\notic\NotificationSetting;
use frontend\controllers\CommonController;

class SettingController extends Controller
{
	public function actionIndex()
	{
		$userNotic  = UserNotification::find()->where('uid = :uid',[':uid'=>Yii::$app->user->identity->id])->all();
		$link = CommonController::createUrlLink(6);
		$data = array();
		$setting = NotificationSetting::find()->joinWith(['t','s']);
		foreach ($setting->each() as $key => $value) {
			$data[$value->tid][$value->sid][] = $value;
			$name[$value->tid]['name'] = $value->t->description;
			$name[$value->tid][$value->sid] = $value->s->type;
		}
		if(Yii::$app->request->post())
		{
			var_dump(Yii::$app->request->post());exit;
		}
		return $this->render('index',['data'=>$data,'link'=>$link,'name'=>$name]);
	}
}