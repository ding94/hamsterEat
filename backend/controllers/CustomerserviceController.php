<?php
namespace backend\controllers;

use Yii;
use yii\web\Controller;
use yii\filters\VerbFilter;
use yii\helpers\ArrayHelper;
use yii\filters\AccessControl;
use common\models\User;
use common\models\Orders;
use backend\models\OrderitemSearch;

class CustomerserviceController extends Controller
{
	public function actionPausedorder()
	{
		$searchModel = new OrderitemSearch();
       	$dataProvider = $searchModel->search(Yii::$app->request->queryParams,1);
       	
        return $this->render('pausedorder',['searchModel'=>$searchModel,'dataProvider'=>$dataProvider]);
	}
}