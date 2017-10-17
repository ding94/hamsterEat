<?php

namespace frontend\controllers;

use yii;
use yii\web\Controller;
use common\models\food\Food;

class FoodpackController extends Controller
{
	public function actionIndex()
	{
		$food = Food::find()->where(['foodtypejunction.Type_ID' => '5'])->innerJoinWith('foodType',true)->joinWith(['restaurant'])->all();

		return $this->render("index",['food' => $food]);
	}

	public function actionAdd()
	{
		$post = Yii::$app->request->post();
		var_dump($post);exit;
	}
}