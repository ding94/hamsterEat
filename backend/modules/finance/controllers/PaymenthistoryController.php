<?php

namespace app\modules\finance\controllers;

use Yii;
use yii\web\Controller;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\helpers\ArrayHelper;
use common\models\payment;
use backend\models\PaymenthistorySearch;
use common\models\User;
use common\models\Userdetail;

Class PaymenthistoryController extends Controller
{
	public function actionIndex()
	{
		$searchModel = new PaymenthistorySearch();
		$dataProvider =$searchModel->search(Yii::$app->request->queryParams);
		$alluser = ArrayHelper::map(User::find()->where('status = 10')->all(),'username','username');

		$arrayData['user']= $alluser;

		return $this->render('index',['model'=> $dataProvider, 'searchModel' => $searchModel, 'arrayData' =>$arrayData]);
	}

	public function actionDetail($id)
	{
		$model = User::find()->where('id = :id',[':id'=>$id])->joinWith(['balance','userdetails'])->one();
		return $this->renderAjax('detail',['model'=>$model]);
	}



}