<?php
namespace frontend\controllers;
use Yii;
use yii\web\Controller;
use yii\helpers\ArrayHelper;
use common\models\Report\Report;
use common\models\Report\ReportCategoryUserStatus;
use common\models\Report\ReportCategoryRestaurantStatus;

class ReportController extends Controller{
	
	public function actionReportUser(){
		$report = new Report();
		$categoryArray = ArrayHelper::map(ReportCategoryUserStatus::find()->all(),'title','title');
		if ($report->load(Yii::$app->request->post())) {
			$report->User_Username = Yii::$app->user->identity->username;
			$report->Report_DateTime = date('Y-m-d H:i:s');
			// $report->PersonReported = 
			$report->save();
		}
		return $this->render('report', ['report'=>$report,'categoryArray'=>$categoryArray]);
	}
}