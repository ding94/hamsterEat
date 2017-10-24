<?php

namespace frontend\controllers;

use yii\web\Controller;
use Yii;
use common\models\Orders;
use common\models\Orderitem;
use common\models\Rating\Foodrating;
use common\models\Rating\Servicerating;
use common\models\Rating\RatingStatus;
use common\models\food\Food;
use yii\helpers\ArrayHelper;
use frontend\controllers\CartController;

Class RatingController extends Controller
{
	public function actionIndex($id)
	{
		$label = RatingStatus::find()->asArray()->all();
		$done = self::completedRating($id);
		if($done == true)
		{
			 return $this->redirect(['site/index']);
		}
		$orderitem = Orderitem::find()->where('Delivery_id = :id' ,[':id' => $id])->joinWith('food')->select('food.Food_ID')->distinct()->asArray()->all();
		$foodrating = new Foodrating;
		$servicerating = new Servicerating;
		$ratingLevel = ArrayHelper::map($label, 'id', 'labelName');

		return $this->render('index',['orderitem' => $orderitem , 'foodrating' => $foodrating ,'servicerating' => $servicerating ,'ratingLevel' => $ratingLevel, 'id' =>$id]);
	}

	public function actionRatingData($id)
	{
		$post= Yii::$app->request->post();
		
		$servicerating = self::serviceRating($post,$id);
		if($servicerating == true)
		{
			$foodvalidate = self::allFoodRating($post['Foodrating'],$id);
			
			if($foodvalidate == true)
			{
				self::changeStatus($id);
			}
			else
			{
				Servicerating::deleteAll('delivery_id = :id',[':id' => $id]);
			}
		}
		else
		{
			Yii::$app->session->setFlash('warning', "Fail");
		}
		return $this->redirect(['site/index']);
	}

	protected static function changeStatus($id)
	{
		$order = Orders::findOne($id);
		$order->Orders_Status = "Rating Done";
		$order->update();
	}

	protected static function completedRating($id)
	{
		$completed = Orders::find()->where('Delivery_ID = :did and User_Username = :name',[':did' => $id , ':name' =>Yii::$app->user->identity->username])->one();
		if(is_null($completed))
		{
			Yii::$app->session->setFlash('warning', "Not the right person");
			return true;
		}
		else
		{
			switch ($completed->Orders_Status) {
				case 'Rating Done':
					Yii::$app->session->setFlash('warning', "You Already take part in");
					return true;
					break;
				case 'Completed':
					return false;
					break;
				default:
					Yii::$app->session->setFlash('warning', "Food is In Process");
					return true;
					break;
			}
		}
	}

	protected static function serviceRating($post,$id)
	{
		$servicerating = new Servicerating;
		$servicerating->load($post);
		$servicerating->delivery_id = $id;
		$servicerating->User_Id = Yii::$app->user->identity->id;
		if($servicerating->save())
		{
			return true;
		}
		else
		{
			return false;
		}
	}

	protected static function allFoodRating($post,$id)
	{
		foreach($post as $data)
		{
			$aa['Foodrating'] = $data;
			$validate = self::foodrating($aa,$id);
			//var_dump($validate);exit;
			if(!$validate)
			{
				Foodrating::deleteAll('delivery_id = :id',[':id' => $id]);
				Yii::$app->session->setFlash('warning', "Food Rating Fail");
				return false;
			}
		}
		Yii::$app->session->setFlash('success', "Thank You for your feedback.");
		return true;
	}

	protected static function foodRating($data,$id)
	{
		$foodrating = new Foodrating;
		$foodrating->load($data);
		//$foodrating->FoodRating_Rating = $data['FoodRating_Rating'];
		//$foodrating->Food_ID =$data['Food_ID'];
		$foodrating->delivery_id = $id;
		$foodrating->User_Id = Yii::$app->user->identity->id;
		//var_dump($foodrating->validate());exit;
		if($foodrating->save())
		{
			$sql = "SELECT id FROM foodrating WHERE Food_ID = ".$foodrating->Food_ID."";
			$result = Yii::$app->db->createCommand($sql)->execute();

			$ratings = Foodrating::find()->where('Food_ID = :fid', [':fid'=>$foodrating->Food_ID])->all();
			$rating = 0;
			foreach ($ratings as $ratings) :
				$rating = $ratings['FoodRating_Rating'] + $rating;
			endforeach;

			$averagerating = $rating / $result;

			$averagerating = CartController::actionDisplay2decimal($averagerating);
			$sql1 = "UPDATE food SET Rating = ".$averagerating." WHERE Food_ID = ".$foodrating->Food_ID."";
			$result = Yii::$app->db->createCommand($sql1)->execute();

			return true;
		}
		else
		{
			return false;
		}
	}

}