<?php

namespace app\modules\finance\controllers;

use Yii;
use yii\web\Controller;
use yii\helpers\ArrayHelper;
use app\modules\finance\controllers\DefaultController;
use app\modules\finance\controllers\AccountHistoryController;
use common\models\Account\AccountForce;
use common\models\User;
use common\models\Account\AccountForceSearch;
use backend\controllers\CommonController;


class AccountforceController extends CommonController
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

		$valid = self::permission();

		if($minusOrplus == '-' ){
			$force->reduceOrPlus = 1;
		}
		else{
			$force->reduceOrPlus = 0;
		}
		$force->amount = (double)$amount;

		$userAccount = DefaultController::getAccountBalance($data['uid'],$force->reduceOrPlus,$force->amount);

		$isValid = $force->validate() && $userAccount->validate();
		
		if($isValid == true)
		{
			$force->save();
			$userAccount->save();
		
			AccountHistoryController::createHistory($force->reason,$force->reduceOrPlus,$force->amount,$userAccount->AB_ID);
			Yii::$app->session->setFlash('success', "Success Operate");
			return true;
		}
		else
		{
			Yii::$app->session->setFlash('warning', "Fail Operate");
			return false;
		}
	}

	protected static function permission()
	{
		$controller = Yii::$app->controller->id;
	    $action = 'force-acc-balance';
        $module = Yii::$app->controller->module->id;

        if($module != 'app-backend'){
            $permissionName = $module.'/'.$controller.'/'.$action;
        }
        else{
             $permissionName =$controller.'/'.$action;
        }

	    if(!\Yii::$app->user->can($permissionName) && Yii::$app->getErrorHandler()->exception === null){
	        throw new \yii\web\UnauthorizedHttpException('Sorry, You do not have permission');
	    }

		return true;
	}

}