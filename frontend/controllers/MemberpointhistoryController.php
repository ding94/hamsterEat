<?php
namespace frontend\controllers;

use Yii;
use yii\web\Controller;
use common\models\Account\Memberpoint;
use common\models\Account\Memberpointhistory;
use yii\filters\AccessControl;

class MemberpointhistoryController extends Controller
{
	public function behaviors()
    {
         return [
             'access' => [
                 'class' => AccessControl::className(),
                 //'only' => ['logout', 'signup','index'],
                 'rules' => [
                    [
                        'actions' => ['index'],
                        'allow' => true,
                        'roles' => ['@'],

                    ],
                    //['actions' => [],'allow' => true,'roles' => ['?'],],
                    
                 ]
             ]
        ];
    }

	 public function actionIndex()
    {
		$searchModel = new Memberpointhistory();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
		//$list = ArrayHelper::map(AccounttopupStatus::find()->all() ,'title' ,'title');
		//$name=ArrayHelper::map(Bank::find()->all() ,'Bank_ID' ,'Bank_Name');
        $this->layout = 'user';
		return $this->render('index',['model' => $dataProvider , 'searchModel' => $searchModel]);
	}
	

}