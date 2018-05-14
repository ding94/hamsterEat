<?php
namespace frontend\controllers;
use Yii;
use yii\web\Controller;
use yii\helpers\ArrayHelper;
use common\models\Report\Report;
use common\models\Report\ReportCategoryUserStatus;
use common\models\Report\ReportCategoryRestaurantStatus;
use frontend\controllers\CommonController;
use yii\filters\AccessControl;

class ReportController extends CommonController
{
	public function behaviors()
    {
         return [
             'access' => [
                 'class' => AccessControl::className(),
                 'rules' => [
                    [
                        'actions' => ['report-user','report-restaurant'],
                        'allow' => true,
                        'roles' => ['@'],

                    ],
                    //['actions' => ['report-restaurant',],'allow' => true,'roles' => ['?'],],
                 ]
             ]
        ];
    }

	public function actionReportUser($name)
	{
		$report = new Report();
		$categoryArray = ArrayHelper::map(ReportCategoryUserStatus::find()->all(),'title','title');
		if ($report->load(Yii::$app->request->post())) {
			$report->User_Username = Yii::$app->user->identity->username;
			$report->Report_DateTime = date('Y-m-d H:i:s');
			$report->Report_PersonReported = $name;
			$report->save();
			if ($report->save()) {
				Yii::$app->session->setFlash('success', "Report has successfully been submitted!");
				// return $this->render('report', ['report'=>$report,'categoryArray'=>$categoryArray]);
				// return $this->goBack();
				return $this->redirect(Yii::$app->request->referrer);
			}
		}
		return $this->renderAjax('report', ['report'=>$report,'categoryArray'=>$categoryArray]);
	}

	public function actionReportRestaurant($name)
	{
		$report = new Report();
		$categoryArray = ArrayHelper::map(ReportCategoryRestaurantStatus::find()->all(),'id','title');
		foreach ($categoryArray as $k => $value) {
			$categoryArray[$k] = Yii::t('report',$value);
		}
		if ($report->load(Yii::$app->request->post())) {
			$report->User_Username = Yii::$app->user->identity->username;
			$report->uid = Yii::$app->user->identity->id;
			$report->Report_DateTime = time('Y-m-d H:i:s');
			$report->Report_PersonReported = $name;
			$report->save();
			if ($report->save()) {
				Yii::$app->session->setFlash('success', "Report has successfully been submitted!");
				return $this->redirect(Yii::$app->request->referrer);
			}
		}
		return $this->renderAjax('report', ['report'=>$report,'categoryArray'=>$categoryArray]);
	}
}