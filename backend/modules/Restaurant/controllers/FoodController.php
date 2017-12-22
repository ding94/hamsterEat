<?php

namespace backend\modules\Restaurant\controllers;

use Yii;
use yii\web\Controller;
use yii\helpers\ArrayHelper;
use backend\models\FoodSearch;
use common\models\food\Foodstatus;
use common\models\food\Foodselection;
use common\models\food\Foodtype;

Class FoodController extends Controller
{
	public function actionIndex($id)
	{
		$searchModel = new FoodSearch();
    	$dataProvider = $searchModel->search(Yii::$app->request->queryParams,$id);

    	if($id == 0)
    	{
    		$searchModel->restaurant = "All Food";
    	}
    	else
    	{
    		$searchModel->restaurant = "Food Detail";
    	}
    	
    	$typeList = ArrayHelper::map(Foodtype::find()->all(),'Type_Desc','Type_Desc');

		return $this->render('index',['model' => $dataProvider , 'searchModel' => $searchModel,'typeList' => $typeList]);
	}

	public function actionFoodControl($id,$status)
	{
		$model = Foodstatus::find()->where('Food_ID = :id',[':id' => $id])->one();
		$model->Status = $status;
		if($model->save())
		{
			Yii::$app->session->setFlash('success', "Food Change completed");
		}
		else
		{
			Yii::$app->session->setFlash('warning', "Food Change Fail");
		}
		return $this->redirect(Yii::$app->request->referrer);
	}

	public function actionTypeControl($id,$status)
	{
		$model = Foodselection::find()->where('ID = :id',[':id' => $id])->one();
		$model->Status = $status;
		if($model->save())
		{
			Yii::$app->session->setFlash('success', "Type Change completed");
		}
		else
		{
			Yii::$app->session->setFlash('warning', "Type Change Fail");
		}
		return $this->redirect(Yii::$app->request->referrer);
	}
}