<?php
namespace backend\modules\Order\controllers;

use yii;
use yii\web\Controller;
use backend\models\ItemProfitSearch;

class ProfitController extends Controller
{
	public function actionIndex()
	{
		$searchModel = new ItemProfitSearch();
    	$dataProvider = $searchModel->search(Yii::$app->request->queryParams);
		return $this->render('index',['model' => $dataProvider , 'searchModel' => $searchModel]);
	}
}