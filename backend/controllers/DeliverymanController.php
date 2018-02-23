<?php

namespace backend\controllers;

use yii\web\Controller;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\helpers\ArrayHelper;
use yii\data\ActiveDataProvider;
use Yii;
use backend\models\DeliverySearch;
use common\models\DeliveryAttendence;

class DeliverymanController extends CommonController
{
	public function actionDailySignin($month,$day)
	{
		$searchModel = New DeliverySearch;
		$dataProvider = $searchModel->search(Yii::$app->request->queryParams,$month);
		return $this->render('daily',['model' => $dataProvider , 'searchModel' => $searchModel , 'day' => $day]);
	}
}