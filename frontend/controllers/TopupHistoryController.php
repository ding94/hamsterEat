<?php

namespace frontend\controllers;
use frontend\models\Accounttopup;
use common\models\Account\AccounttopupStatus;
use common\models\User;
use common\models\Bank;
use yii\helpers\ArrayHelper;
use Yii;
use yii\web\UploadedFile;
use frontend\controllers\CommonController;
use yii\filters\AccessControl;

class TopupHistoryController extends CommonController
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
        $searchModel = new Accounttopup();
		$dataProvider = $searchModel->search(Yii::$app->request->queryParams,5);
	//	var_dump($model); exit;
		 //$dataProvider = $searchModel->search(Yii::$app->request->queryParams);          
	    $list = ArrayHelper::map(AccounttopupStatus::find()->all() ,'title' ,'title');
		$name=ArrayHelper::map(Bank::find()->all() ,'Bank_ID' ,'Bank_Name');
		//var_dump($name);exit;
        $link = CommonController::createUrlLink(2);
		$this->layout = 'user';
	    return $this->render('index',['model' => $dataProvider , 'searchModel' => $searchModel,'list'=>$list,'name'=>$name ,'link'=>$link]);
    }

}
