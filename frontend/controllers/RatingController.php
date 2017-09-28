<?php

namespace frontend\controllers;

use yii\web\Controller;
use Yii;
use common\models\Orders;
use common\models\Orderitem;
use common\models\Rating\Foodrating;
use common\models\Rating\Servicerating;

Class RatingController extends Controller
{
	public function actionIndex($id)
	{
		$done = self::completedRating($id);
		if($done == true)
		{
			 return $this->redirect(['site/index']);
		}
		$orderitem = Orderitem::find()->where('Delivery_id = :id' ,[':id' => $id])->joinWith('food')->select('food.Food_ID')->distinct()->asArray()->all();
		$foodrating = new Foodrating;
		$servicerating = new Servicerating;
		$ratingLevel = [1=>'Very Bad',2=>'Bad',3=>'Average',4=>'Good',5=>'Very Good'];


		return $this->render('index',['orderitem' => $orderitem , 'foodrating' => $foodrating ,'servicerating' => $servicerating ,'ratingLevel' => $ratingLevel, 'id' =>$id]);
	}

	public function actionRatingData($id)
	{
		$post= Yii::$app->request->post();
		$servicerating = self::serviceRating($post,$id);
		if($servicerating == true)
		{
			$foodvalidate = self::allFoodRating($post['Foodrating'],$id);
			if($foodvalidate == false)
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

	protected static function completedRating($id)
	{
		$completed = Orders::find()->where('Delivery_ID = :did and User_Username = :name',[':did' => $id , ':name' =>Yii::$app->user->identity->username])->one();
		if(is_null($completed))
		{
			Yii::$app->session->setFlash('warning', "Wrong Person");
			return true;
		}
		$data = Servicerating::find()->where('delivery_id = :did and User_Id = :uid',[':did' => $id , ':uid' =>Yii::$app->user->identity->id ])->one();
		if($data)
		{
			Yii::$app->session->setFlash('warning', "You Already take part in");
			return true;
		}
		else
		{
			return false;
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
		Yii::$app->session->setFlash('success', "Thank You for completed");
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
			return true;
		}
		else
		{
			return false;
		}
	}

}