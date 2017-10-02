<?php

namespace app\modules\finance\controllers;

use Yii;
use yii\web\Controller;
use yii\helpers\ArrayHelper;
use app\modules\finance\controllers\DefaultController;
use common\models\Account\AccountForce;
use common\models\User;
use common\models\Account\AccountForceSearch;

class AccountforceController extends Controller
{
	public function actionIndex()
	{
		$searchModel = new AccountForceSearch;
		$dataProvider = $searchModel->search(Yii::$app->request->queryParams);

		return $this->render('index',['model' => $dataProvider , 'searchModel' => $searchModel]);
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
			return $this->redirect(['index']);
		}
		else
		{
			return $this->redirect(Yii::$app->request->referrer);
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
			if(Yii::$app->user->can('admin'))
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

		$userAccount = DefaultController::getAccountBalance($data['uid'],$force->reduceOrPlus,$force->amount);

		$isValid = $force->validate() && $userAccount->validate();
		
		if($isValid == true)
		{
			$force->save();
			$userAccount->save();
			Yii::$app->session->setFlash('success', "Success Operate");
			return true;
		}
		else
		{
			Yii::$app->session->setFlash('warning', "Fail Operate");
			return false;
		}
	}

}