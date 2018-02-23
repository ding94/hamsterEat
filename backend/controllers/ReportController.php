<?php

namespace backend\controllers;

use yii\web\Controller;
use yii\helpers\ArrayHelper;
use Yii;
use common\models\Report\Report;

Class ReportController extends CommonController
{
	public function actionIndex()
	{
		$searchModel = new Report();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
 
        return $this->render('index',['model' => $dataProvider , 'searchModel' => $searchModel]);
	}
}