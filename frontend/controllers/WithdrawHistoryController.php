<?php

namespace frontend\controllers;
use common\models\Withdraw;
use common\models\Bank;
use common\models\User;
use common\models\Account\AccounttopupStatus;
use yii\data\ActiveDataProvider;
use yii\helpers\ArrayHelper;
use Yii;

class WithdrawHistoryController extends \yii\web\Controller
{
    public function actionIndex()
    {
       $searchModel = new Withdraw();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams,0);

		$list = ArrayHelper::map(AccounttopupStatus::find()->all() ,'title' ,'title');
		$name=ArrayHelper::map(Bank::find()->all() ,'Bank_ID' ,'Bank_Name');
        $this->layout = 'user';
		return $this->render('index',['model' => $dataProvider , 'searchModel' => $searchModel, 'list'=>$list ,'name'=>$name]);
    }

}
