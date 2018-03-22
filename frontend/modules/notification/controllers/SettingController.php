<?php

namespace frontend\modules\notification\controllers;

use Yii;
use yii\web\Controller;
use common\models\user\UserNotification;
use common\models\notic\NotificationSetting;
use frontend\controllers\CommonController;
use yii\helpers\ArrayHelper;
use yii\base\Model;

class SettingController extends Controller
{
	public function actionIndex()
	{
		
		$link = CommonController::createUrlLink(6);

		$array = $this->genSetting();

		$setting = $array['setting'];
		$data = $array['data'];
		$name = $array['name'];
		$usernotic = $array['user'];
		
		if(Yii::$app->request->post())
		{
			$isValid = $this->loadSetting($setting,$usernotic);
			if($isValid)
			{
				Yii::$app->session->setFlash('success', Yii::t('cart','Success!'));
				return $this->redirect(['index']);
			}
			Yii::$app->session->setFlash('danger', Yii::t('cart','Edit failed'));
		}
		return $this->render('index',['data'=>$data,'link'=>$link,'name'=>$name]);
	}

	/*
	* reindex base on id
	* if userNotic Avaiable replace to notic
	*/
	protected static function genSetting()
	{
		$userNotic  = UserNotification::find()->where('uid = :uid',[':uid'=>Yii::$app->user->identity->id])->all();

		$userNotic = ArrayHelper::index($userNotic,'setting_id');
		$data = $setting = $return = array();
		
		$query = NotificationSetting::find()->joinWith(['t','s']);

		foreach ($query->each() as $key => $value) 
		{
			if(empty($userNotic[$value->id]))
			{
				$data[$value->tid][$value->sid][] = $value;
			}
			else
			{

				$data[$value->tid][$value->sid][] = $userNotic[$value->id];
			}
			
			$name[$value->tid]['name'] = $value->t->description;
			$name[$value->tid][$value->sid] = $value->s->type;

			if($value->enable == 0 || $value->enable == 1)
			{
				$setting[$value->id] = $value;
			}
		}
		//var_dump($data[1][10][1]->tableSchema->name);exit;
		$return['data'] = $data;
		$return['setting'] = $setting;
		$return['name'] = $name;
		$return['user'] = $userNotic;
		return $return;

	}

	/*
	* load data from post
	* if the data change save to user notic
	*/
	protected static function loadSetting($setting,$usernotic)
	{
		$post = Yii::$app->request->post();
		Model::loadMultiple($setting, $post);

		foreach ($setting as $key => $value) 
		{
			$old = $value->OldAttributes;
			if($old['enable'] != $value->enable)
			{
				$unotic = UserNotification::find()->where('uid = :uid and setting_id = :sid',[':uid'=>Yii::$app->user->identity->id,':sid'=>$value->id])->one();
				$unotic = empty($unotic) ? new UserNotification : $unotic;
				$unotic->uid = Yii::$app->user->identity->id;
				$unotic->setting_id = $value->id;
				$unotic->enable = $value->enable;
				if(!$unotic->save())
				{
					return false;
				}
			}
			
		}

		if(!empty($usernotic))
		{
			Model::loadMultiple($usernotic,$post);
			foreach($usernotic as $value)
			{
				if(!$value->save())
				{
					return false;
				}
				
			}
		}
		return true;
	}
}