<?php namespace console\controllers; 

use Yii; 
use yii\console\Controller; 
use console\models\FoodLimit;

class FoodController extends Controller
{
	public function actionDailylimit()
	{
		Yii::$app->queue->push(new FoodLimit());
	}
}