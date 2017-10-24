<?php

namespace frontend\modules\Restaurant\controllers;

use Yii;
use yii\web\Controller;
use backend\models\RestaurantSearch;
use common\models\Restaurant;
use common\models\Rmanager;
use common\models\food\Food;
use common\models\food\Foodstatus;
use yii\web\NotFoundHttpException;
use frontend\controllers\CommonController;

class RestaurantController extends CommonController
{
	public function actionIndex()
    {
    	$searchModel = new RestaurantSearch();
    	$dataProvider = $searchModel->search(Yii::$app->request->queryParams);

    	return $this->render('index',['model' => $dataProvider , 'searchModel' => $searchModel]);
    }

    public function actionRestaurantService()
    {
        if (Rmanager::find()->where('uid=:id',[':id' => Yii::$app->user->identity->id])->one()) {
            $restaurant = Restaurant::find()->where('Restaurant_Manager=:r',[':r' => Yii::$app->user->identity->username])->all();
             $this->layout = "/user";
            return $this->render('restaurantservice',['restaurant'=>$restaurant]);
        }
        
    }

    public function actionFoodService($id)
    {
        $foods = Food::find()->where('Restaurant_ID=:rid',[':rid'=>$id])->all();
        $this->layout = "/user";
        return $this->render('foodservice',['foods'=>$foods]);
    }

    public function actionActive($id,$item)
    {
        switch ($item) {
            case 1:
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
                break;

            case 2:
                $model = Foodstatus::find()->where('Food_ID=:id',[':id'=>$id])->one();
                $model->Status = 1;
                if($model->validate())
                {
                    $model->save();
                    Yii::$app->session->setFlash('success', "Status change to operating.");
                }
                else
                {
                    Yii::$app->session->setFlash('warning', "Change status failed.");
                }
                break;
            
            default:
                # code...
                break;
        }
        

        return $this->redirect(Yii::$app->request->referrer);
    }

    public function actionDeactive($id,$item)
    {
        switch ($item) {
            case 1:
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
                break;

            case 2:
                $model = Foodstatus::find()->where('Food_ID=:id',[':id'=>$id])->one();
                $model->Status = 0;
                if($model->validate())
                {
                    $model->save();
                    Yii::$app->session->setFlash('success', "Status change to paused.");
                }
                else
                {
                    Yii::$app->session->setFlash('warning', "Change status failed.");
                }
                break;
            
            default:
                # code...
                break;
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