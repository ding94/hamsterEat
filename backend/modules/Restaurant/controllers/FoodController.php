<?php

namespace backend\modules\Restaurant\controllers;

use Yii;
use yii\web\Controller;
use backend\models\FoodSearch;

Class FoodController extends Controller
{
	public function actionIndex($id)
	{
		$searchModel = new FoodSearch();
    	$dataProvider = $searchModel->search(Yii::$app->request->queryParams,$id);
		return $this->render('index',['model' => $dataProvider , 'searchModel' => $searchModel]);
	}
}