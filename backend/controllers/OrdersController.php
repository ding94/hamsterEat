<?php 
namespace backend\controllers;

use Yii;
use yii\db\Query;
use yii\helpers\Url;
use common\models\User;;
use common\models\RestDays;;
use common\models\order\PlaceOrderChance;

class OrdersController extends CommonController 
{
	public function actionPlaceOrderChance()
	{
		$searchModel = new PlaceOrderChance();
    	$dataProvider = $searchModel->search(Yii::$app->request->queryParams,1);

    	return $this->render('place-order-chance',['dataProvider' => $dataProvider , 'searchModel' => $searchModel]);
	}

	public function actionAddChance()
	{
		date_default_timezone_set("Asia/Kuala_Lumpur");
		$model = new PlaceOrderChance();
		$model['chances'] = 1;
		$url = Url::to(['/orders/userlist']);

		if (Yii::$app->request->post()) {
			$model->load(Yii::$app->request->post());
			if ($model['start_time'] < $model['end_time']) {
				$model['start_time'] = strtotime($model['start_time']);
				$model['end_time'] = strtotime($model['end_time']);
				if ($model->validate()) {
					$model->save();
					Yii::$app->session->setFlash('success','Chance was gave.');
				}
			}
			else{
				Yii::$app->session->setFlash('warning','End date cannot exceed start date.');
				return $this->render('add-chance',['model'=>$model,'url'=>$url]);
			}
			
			return $this->redirect(['/orders/place-order-chance']);
		}
		return $this->render('add-chance',['model'=>$model,'url'=>$url]);
	}

	public function actionUserlist($q = null) 
    {
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        $out = ['results' => ['id' => '', 'text' => '']];
        if (!is_null($q)) {
            $query = new Query;
            $query= User::find()->select('id , username')->andWhere(['like','username',$q])->all();
            $out['results'] = array_values($query);
        }
        return $out;
    }

    public function actionRestDays()
    {
    	$searchModel = new RestDays();
    	$dataProvider = $searchModel->search(Yii::$app->request->queryParams,1);

    	return $this->render('rest-days',['dataProvider' => $dataProvider , 'searchModel' => $searchModel]);
    }

    public function actionAddRestDay()
	{
		$model = new RestDays();

		if (Yii::$app->request->post()) {
			$model->load(Yii::$app->request->post());
			if ($model['month'] >= 13 || $model['date'] >= 32) {
				Yii::$app->session->setFlash('warning','Month or Date does not exist');
			}
			else{
				if ($model->validate()) {
					$model->save();
					Yii::$app->session->setFlash('sucess','Success saved');
					return $this->redirect(['/orders/rest-days']);
				}
				else{
					Yii::$app->session->setFlash('warning','Failed to save info');
				}
			}
		}
		return $this->render('add-rest-day',['model'=>$model]);
	}
}