<?php
namespace backend\modules\Order\controllers;

use yii;
use yii\web\Controller;
use backend\models\ItemProfitSearch;

class ProfitController extends Controller
{
	public function actionIndex($first = 0,$last = 0,$id =0)
	{
		if($first == 0 && $last == 0)
        {
            $first = date("Y-m-d", strtotime("first day of this month"));
            $last = date("Y-m-d", strtotime("+1 days")); 
        }
		$searchModel = new ItemProfitSearch();
    	$dataProvider = $searchModel->search(Yii::$app->request->queryParams,$first,$last,$id);
		return $this->render('index',['model' => $dataProvider ,'first'=>$first,'last'=>$last]);
	}
}