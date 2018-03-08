<?php

namespace frontend\controllers;

use yii\web\Controller;
use Yii;
use common\models\Order\Orders;
use common\models\Order\Orderitem;
use common\models\Rating\Foodrating;
use common\models\Rating\Servicerating;
use common\models\Rating\RatingStatus;
use common\models\food\Food;
use common\models\Restaurant;
use yii\helpers\ArrayHelper;
use frontend\controllers\CartController;
use frontend\controllers\CommonController;
use yii\filters\AccessControl;

Class RatingController extends CommonController
{
	public function behaviors()
    {
         return [
             'access' => [
                 'class' => AccessControl::className(),
                 'rules' => [
                    [
                        'actions' => ['index','rating-data'],
                        'allow' => true,
                        'roles' => ['@'],

                    ],
                    //['actions' => ['rating-data'],'allow' => true,'roles' => ['?'],],
                 ]
             ]
        ];
    }

	public function actionIndex($id)
	{
		$label = RatingStatus::find()->asArray()->all();
		$done = self::completedRating($id);
		if($done == true)
		{
			 return $this->redirect(['site/index']);
		}
		$orderitem = Orderitem::find()->where('Delivery_id = :id and OrderItem_Status = 10' ,[':id' => $id])->joinWith('food')->select('food.Food_ID')->all();
		
		$foodrating = new Foodrating;
		$servicerating = new Servicerating;
		$ratingLevel = ArrayHelper::map($label, 'id', 'labelName');
		//var_dump($orderitem);exit;
		return $this->render('index',['orderitem' => $orderitem , 'foodrating' => $foodrating ,'servicerating' => $servicerating ,'ratingLevel' => $ratingLevel, 'id' =>$id]);
	}

	public function actionRatingData($id)
	{
		$post= Yii::$app->request->post();
		if(!empty($post))
		{
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
				Yii::$app->session->setFlash('warning', Yii::t('common',"Failed"));
			}
		}
		
		return $this->redirect(['order/my-orders','status'=>6]);
	}

	protected static function changeStatus($id)
	{
		$order = Orders::findOne($id);
		$order->Orders_Status = 7;
		$order->update();
	}

	protected static function completedRating($id)
	{
		$completed = Orders::find()->where('Delivery_ID = :did and User_Username = :name',[':did' => $id , ':name' =>Yii::$app->user->identity->username])->one();
		if(is_null($completed))
		{
			Yii::$app->session->setFlash('warning', Yii::t('rating',"Not the right person"));
			return true;
		}
		else
		{
			switch ($completed->Orders_Status) {
				case 7:
					Yii::$app->session->setFlash('warning', Yii::t('rating',"You Already take part in"));
					return true;
					break;
				case 6:
					return false;
					break;
				default:
					Yii::$app->session->setFlash('warning', Yii::t('rating',"Food is In Process"));
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
		return true;
		/*if($servicerating->save())
		{
			return true;
		}
		else
		{
			return false;
		}*/
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
				Yii::$app->session->setFlash('warning', Yii::t('rating',"Food Rating Fail"));
				return false;
			}
		}
		Yii::$app->session->setFlash('success', Yii::t('rating',"Thank You for your feedback."));
		return true;
	}

//--The average rating of the rated food is calculated here
	protected static function foodRating($data,$id)
	{
		$foodrating = new Foodrating;
		$foodrating->load($data);
		//$foodrating->FoodRating_Rating = $data['FoodRating_Rating'];
		//$foodrating->Food_ID =$data['Food_ID'];
		$foodrating->delivery_id = $id;
		$foodrating->User_Id = Yii::$app->user->identity->id;
		//var_dump($foodrating->validate());exit;
		
		$food = self::countFoodRate($foodrating->Food_ID,$foodrating->FoodRating_Rating);
		
		$restaurant = self::countResRate($food->Restaurant_ID,$food->Rating);
		
		
		$isvalid = $food->validate() && $foodrating->validate() && $restaurant->validate();
	
		if($isvalid)
		{
			if($food->save() && $foodrating->save() && $restaurant->save())
			{
				return true;
			}
			return false;
		}
		return $isvalid;
		
	}

	protected static function countFoodRate($fid,$number)
	{
		$food = Food::findOne($fid);
		$food->Rating =  CartController::actionDisplay2decimal(($food->Rating+$number)/2);
		return $food;
	}

	protected static function countResRate($rid,$number)
	{
		$restaurant = Restaurant::findOne($rid);
		$restaurant->Restaurant_Rating = CartController::actionDisplay2decimal(($restaurant->Restaurant_Rating+$number)/2);
		return $restaurant;
	}

}