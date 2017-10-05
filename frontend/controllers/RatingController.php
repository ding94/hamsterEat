<?php

namespace frontend\controllers;

use yii\web\Controller;
use Yii;
use common\models\Orders;
use common\models\Orderitem;
use common\models\Rating\Foodrating;
use common\models\Rating\Servicerating;
use common\models\Rating\RatingStatus;
use yii\helpers\ArrayHelper;

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
			$validate = self::foodrating($data['FoodRating_Rating'],$data['Food_ID'],$id);
			if($validate == false)
			{
				Foodrating::deleteAll('delivery_id = :id',[':id' => $id]);
				Yii::$app->session->setFlash('warning', "Food Rating Fail");
				return false;
			}
		}
		Yii::$app->session->setFlash('success', "Thank You for your feedback.");
		return true;
	}

	protected static function foodRating($rating,$foodID,$id)
	{
		$foodrating = new Foodrating;
		$foodrating->FoodRating_Rating = $rating;
		$foodrating->Food_ID =$foodID;
		$foodrating->delivery_id = $id;
		$foodrating->User_Id = Yii::$app->user->identity->id;
		if($foodrating->save())
		{
			$sql = "SELECT id FROM Foodrating WHERE Food_ID = ".$foodID."";
			$result = Yii::$app->db->createCommand($sql)->execute();

			$ratings = Foodrating::find()->where('Food_ID = :fid', [':fid'=>$foodID])->all();
			$rating = 0;
			foreach ($ratings as $ratings) :
				$rating = $ratings['FoodRating_Rating'] + $rating;
			endforeach;

			$averagerating = $rating / $result;

			$sql1 = "UPDATE food SET FoodRating = ".$averagerating.", Food_TotalBought = ".$result." WHERE Food_ID = ".$foodID."";
			$result = Yii::$app->db->createCommand($sql1)->execute();
			
			return true;
		}
		else
		{
			return false;
		}
	}

}