<?php

namespace backend\controllers;

use yii\web\Controller;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\helpers\ArrayHelper;
use yii\data\ActiveDataProvider;
use Yii;
use backend\models\{DeliveryDailySearch,DeliveryManSearch};
use common\models\{Deliveryman,DeliverymanCompany};
use common\models\Order\Orders;

class DeliverymanController extends CommonController
{
	public function actionDailySignin($month,$day)
	{
		$searchModel = New DeliveryDailySearch;
		$dataProvider = $searchModel->search(Yii::$app->request->queryParams,$month);
		return $this->render('daily',['model' => $dataProvider , 'searchModel' => $searchModel , 'day' => $day]);
	}

	public function actionApproval()
	{
		$searchModel = New DeliveryManSearch;
		$dataProvider = $searchModel->search(Yii::$app->request->queryParams);
		return $this->render('approval',['model' => $dataProvider , 'searchModel' => $searchModel ]);
	}

	public function actionActive($id)
	{
		$model = $this->findModel($id);
		if($model->DeliveryMan_Approval == 0)
		{
			$model->DeliveryMan_Approval = 1;
			$model->DeliveryMan_DateTimeApproved = time();

			if($model->save())
			{
				Yii::$app->session->setFlash('success', "Active completed");
			}
			else
			{
				Yii::$app->session->setFlash('warning', "Fail to Active");
			}
		}
		else
		{
			Yii::$app->session->setFlash('warning', "Fail to Active");
		}
		return $this->redirect(Yii::$app->request->referrer);
	}

	public function actionDeactive($id)
	{
		$count = Orders::find()->where(['not in','Orders_Status',[1,6,7,8,9]])->andWhere('deliveryman = :d',[':d'=>$id])->joinWith(['address'])->count();
		
		if($count == 0 )
		{
			$model = $this->findModel($id);
			if($model->DeliveryMan_Approval == 1)
			{
				$message = "";
				$query = DeliverymanCompany::find()->where('uid = :uid',[':uid'=>$id]);

				foreach($query->each() as $value)
				{
					if(!$value->delete())
					{
						Yii::$app->session->setFlash('warning', "Fail to Deactive");
						break;
					}
					$message .= "There Are Company Is Assign To This Deliveryman. Please reasgin the company<br>";
				}
				$model->DeliveryMan_Approval=0;
				if($model->save())
				{
					$message .= "Deactive success";
					Yii::$app->session->setFlash('success', $message);
				}
				else
				{
					Yii::$app->session->setFlash('warning', "Fail to Deactive");
				}
			}
			
		}
		else
		{
			Yii::$app->session->setFlash('warning', "The DeliveryMan Still Got Assigned Job.<br>Cannot Deactive.");
		}
		return $this->redirect(Yii::$app->request->referrer);
	}

	protected static function findModel($id)
	{	
        if (($model = Deliveryman::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
	}
}