<?php

namespace backend\controllers;

use Yii;
use yii\web\Controller;
use yii\helpers\ArrayHelper;
use common\models\notic\{NotificationType,NotificationSettingType,NotificationSetting};
use common\models\Order\StatusType;
use backend\models\{NotificationSettingSearch,SmsLogSearch};

class NotificationController extends CommonController
{
	public function actionSetting()
	{																	
		$searchModel = new NotificationSettingSearch();
    	$dataProvider = $searchModel->search(Yii::$app->request->queryParams);

    	$array = array();
    	$array['type'] = ArrayHelper::map(NotificationType::find()->all(),'id','description');
    	$array['status'] = ArrayHelper::map(StatusType::find()->all(),'id','type');
    	$array['notic'] = ArrayHelper::map(NotificationSettingType::find()->all(),'id','description');
    	$array['enable'] = ['-1'=>'Force Off','0'=>'Off','1'=>'On','2'=>'Force On'];

    	return $this->render('setting',['model' => $dataProvider , 'searchModel' => $searchModel,'array'=>$array]);
	}

	public function actionSmsLog()
	{
		$searchModel = new SmsLogSearch();
		$dataProvider = $searchModel->search(Yii::$app->request->queryParams);

		$array['result'] = [0=>'Fail',1=>'Success'];
		$array['type'] = ArrayHelper::map(NotificationType::find()->all(),'id','description');
		
		return $this->render('log',['model'=>$dataProvider,'searchModel'=>$searchModel,'array'=>$array]);
	}

	public function actionUpdate($id)
	{
		$model = $this->findModel($id);
		if($model->load(Yii::$app->request->post()))
		{
			if($model->save())
			{
				Yii::$app->session->setFlash('success', "Update completed");
				return $this->redirect(['setting']);
			}
			Yii::$app->session->setFlash('warning', "Update Fail");
		}
		return $this->render('update',['model'=>$model]);
	}

	protected function findModel($id)
    {
        if (($model = NotificationSetting::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}