<?php

namespace app\modules\finance\controllers;

use Yii;
use yii\web\Controller;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\helpers\ArrayHelper;
use common\models\payment;
use common\models\PaymentGateWay\PaymentGateWayHistory;
use backend\models\OnlinepaymenthistorySearch;
use common\models\PaymentGateWay\PaymentBill;



Class OnlinepaymenthistoryController extends Controller
{
	public function actionIndex()
	{
		$searchModel = new OnlinepaymenthistorySearch();
		$dataProvider =$searchModel->search(Yii::$app->request->queryParams);
	

		return $this->render('index',['model'=> $dataProvider, 'searchModel' => $searchModel]);
	}

	public function actionDetail($id)
	{	

		$model = PaymentBill::getBill($id);
		if($model['value']==-1){
			Yii::$app->session->setFlash('warning', "Fail to Open");
			return $this->redirect(['onlinepaymenthistory/index']);
		}
		return $this->renderAjax('detail',['model'=>$model]);
	}



}