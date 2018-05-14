<?php

namespace backend\controllers;

use yii\web\Controller;
use yii\helpers\ArrayHelper;
use Yii;
use common\models\Report\Report;
use backend\models\ReportSearch;
use common\models\User;
use common\models\Report\ReportCategoryRestaurantStatus;
Class ReportController extends CommonController
{
	public function actionIndex()
	{
		$searchModel = new ReportSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
 		$alluser = ArrayHelper::map(User::find()->where('status = 10')->all(),'username','username');
 		$allstatus =ArrayHelper::map(ReportCategoryRestaurantStatus::find()->all(),'id','title');
 		$arrayData['user'] = $alluser;
        $arrayData['status'] = $allstatus;
        return $this->render('index',['model' => $dataProvider , 'searchModel' => $searchModel,'arrayData' => $arrayData]);
	}

	public function actionDetail($id)
	{
		$model = User::find()->where('id = :id',[':id'=>$id])->joinWith(['userdetails'])->one();
		return $this->renderAjax('detail',['model'=>$model]);
	}

}