<?php

namespace frontend\controllers;
use common\models\Withdraw;
use common\models\Bank;
use common\models\User;
use common\models\Account\AccounttopupStatus;
use yii\data\ActiveDataProvider;
use yii\helpers\ArrayHelper;
use Yii;
use frontend\controllers\CommonController;
use yii\filters\AccessControl;

class WithdrawHistoryController extends CommonController
{
	public function behaviors()
    {
         return [
             'access' => [
                 'class' => AccessControl::className(),
                 'rules' => [
                    [
                        'actions' => ['index',],
                        'allow' => true,
                        'roles' => ['@'],

                    ],
                    //['actions' => ['rating-data'],'allow' => true,'roles' => ['?'],],
                 ]
             ]
        ];
    }

    public function actionIndex()
    {
       $searchModel = new Withdraw();
        $dataProvider = $searchModel->searchUser(Yii::$app->request->queryParams);

		$list = ArrayHelper::map(AccounttopupStatus::find()->all() ,'title' ,'title');
		$name=ArrayHelper::map(Bank::find()->all() ,'Bank_ID' ,'Bank_Name');
        $this->layout = 'user';
		return $this->render('index',['model' => $dataProvider , 'searchModel' => $searchModel, 'list'=>$list ,'name'=>$name]);
    }

}
