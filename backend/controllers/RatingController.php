<?php
namespace backend\controllers;

use yii\web\Controller;
use Yii;
use common\models\Rating\RatingSearch;

Class RatingController extends Controller
{
	public function actionIndex()
	{
		$searchModel = new RatingSearch();
    	$dataProvider = $searchModel->search(Yii::$app->request->queryParams);

    	return $this->render('index',['model' => $dataProvider , 'searchModel' => $searchModel]);
	}
}