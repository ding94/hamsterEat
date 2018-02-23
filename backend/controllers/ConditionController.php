<?php
namespace backend\controllers;

use Yii;
use yii\web\Controller;
use yii\filters\VerbFilter;
use yii\helpers\ArrayHelper;
use yii\filters\AccessControl;
use common\models\vouchers\Vouchers;
use common\models\vouchers\VouchersConditions;
use common\models\vouchers\VouchersSetCondition;

class ConditionController extends CommonController
{
	public function actionIndex()
	{
		$searchModel = new VouchersSetCondition;
       	$dataProvider = $searchModel->search(Yii::$app->request->queryParams,1);
       	
        return $this->render('index',['dataProvider'=>$dataProvider, 'searchModel'=>$searchModel]);
	}

	public function actionSetcondition() 
	{
		$voucon = new VouchersSetCondition;
		$voucon->scenario = "set";
		$condition = ArrayHelper::map(VouchersConditions::find()->all(),'id','description');
		if (Yii::$app->request->post()) {
			$voucon->load(Yii::$app->request->post());
		}

		return $this->render('setcondition',['voucon'=>$voucon,'condition'=>$condition]);
	}
}