<?php

namespace app\modules\finance\controllers;

use Yii;
use yii\web\Controller;
use yii\helpers\ArrayHelper;
use common\models\Account\AccountForce;
use common\models\User;

class AccountforceController extends Controller
{
	public function actionIndex()
	{

	}

	public function actionForce()
	{
		$model = new AccountForce;
		$user = ArrayHelper::map(User::find()->all(),'id','username');
		return $this->render('force',['model' => $model,'user' => $user]);
	}

	public function actionSubmitData()
	{
		$post = Yii::$app->request->post();
		$newForce = self::createForce($post['AccountForce']);
		if($newForce == true)
		{

		}
	}

	protected static function createForce($data)
	{
		$force = new AccountForce;

		$force->uid = $data['uid'];
		$force->reason = $data['reason'];
		$force->adminid = Yii::$app->user->identity->id;
		$amount = $data['amount'];
		$minusOrplus = substr(strval($amount), 0, 1);

		if($minusOrplus == '-' )
		{
			if(Yii::$app->user->can('admina'))
			{
				$force->reduceOrPlus = 1;
				$force->amount = (double)$amount;
			}
			else
			{
				Yii::$app->session->setFlash('warning', "Permission Denied");
				return false;
			}
			
		}
		else
		{
			$force->reduceOrPlus = 0;
			$force->amount = (double)$amount;
		}

		var_dump($force);exit;
		if($force->save())
		{
			return true;
		}
		else
		{
			Yii::$app->session->setFlash('warning', "Fail Operate");
			return false;
		}
	}

}