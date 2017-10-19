<?php

namespace backend\modules\Restaurant\controllers;

use Yii;
use yii\web\Controller;
use backend\models\RestaurantSearch;
use common\models\Restaurant;
use yii\web\NotFoundHttpException;

class RestaurantController extends Controller
{
	public function actionIndex()
    {
    	$searchModel = new RestaurantSearch();
    	$dataProvider = $searchModel->search(Yii::$app->request->queryParams);

    	return $this->render('index',['model' => $dataProvider , 'searchModel' => $searchModel]);
    }

    public function actionActive($id)
    {
        $model = self::findModel($id);
        $model->Restaurant_Status = "Operating";
        if($model->validate())
        {
        	$model->save();
            Yii::$app->session->setFlash('success', "Status change to operating.");
        }
        else
        {
            Yii::$app->session->setFlash('warning', "Change status failed.");
        }

        return $this->redirect(Yii::$app->request->referrer);
    }

    public function actionDeactive($id)
    {
        $model = self::findModel($id);
        $model->Restaurant_Status = "Closed";
        if($model->validate())
        {
        	$model->save();
            Yii::$app->session->setFlash('success', "Status change to closed.");
        }
        else
        {
            Yii::$app->session->setFlash('warning', "Change status failed.");
        }
        return $this->redirect(Yii::$app->request->referrer);
    }

    protected function findModel($id)
    {
        $model = Restaurant::find()->where('Restaurant_ID = :id',[':id' =>$id])->one();
        if ($model !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested restaurant does not exist.');
        }
    }
}