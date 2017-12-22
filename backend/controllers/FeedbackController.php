<?php

namespace backend\controllers;

use yii\web\Controller;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\helpers\ArrayHelper;
use yii\data\ActiveDataProvider;
use Yii;
use common\models\Feedback;

class FeedbackController extends Controller
{
	public function actionIndex()
	{
		$searchModel = New Feedback;
		$dataProvider = $searchModel->search(Yii::$app->request->queryParams,0);
		return $this->render('index',['dataProvider' => $dataProvider , 'searchModel' => $searchModel]);
	}
}