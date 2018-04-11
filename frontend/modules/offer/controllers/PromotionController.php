<?php
namespace frontend\modules\offer\controllers;

use Yii;
use yii\web\Controller;
use common\models\promotion\{Promotion,PromotionDailyLimit,PromotionUserUsed};
use common\models\Cart\Cart;
use common\models\food\Food;

class PromotionController extends Controller
{

	/*
	* detect any promotion
	* find user use already the promotion
	*
	*/
	public static function getPromotioinPrice($price,$id,$type)
	{
		$promotion = self::getPromotion();
		
		if(empty($promotion))
		{
			return $price;
		}
		
		if($type == 2 && $promotion->enable_selection == 0)
		{
			return $price;
		}

		//$food->Price = self::calPrice($promotion->type_discount,$promotion->discount,$food->Price);
		
		return self::findPromotionPrice($promotion,$price,$id,$type);
	}

	/*
	* detect promotion and whether user used;
	*/
	public static function getPromotion()
	{
		$today = date("Y-m-d");
		$promotion = Promotion::find()->where(['<=','start_date',$today])->andWhere(['>=','end_date',$today])->one();

		if(empty($promotion))
		{
			return;
		}

		if(!Yii::$app->user->isGuest)
		{
			if($promotion->enable_per_user == 1)
			{
				$userUsed = PromotionUserUsed::find()->where('id = :id and uid = :uid',[':id'=>$promotion->id,':uid'=>Yii::$app->user->identity->id])->exists();
				if($userUsed)
				{
					return;
				}
			}
			
		}
		return $promotion;
	}

	/*
	* ind the PromotionLimitData 
	* if not data return price
	* and find daily limit
	* to calculate daily food limit reach max limit of food limit
	*/
	protected static function findPromotionPrice($promotion,$price,$id,$type)
	{

		$arrayLimit = DetectPromotionController::getDailyList($id,$promotion->id,$promotion->type_promotion);
		$food = Food::findOne($id);
		if(empty($arrayLimit))
		{
			
			return $price;
		}

		$data = $price;
		$dailyLimit = $arrayLimit['daily'];
		$limit =$arrayLimit['limit'];
		
		if($dailyLimit->food_limit < $limit->food_limit)
		{
			$data =  self::calPrice($promotion->type_discount,$promotion->discount,$price,$type);
			$data['left'] = $limit->food_limit - $dailyLimit->food_limit;
		}
		
		return $data;
	}

	/*
	*  recalculate the price using type and discount
	*  ptype => type of discount 
	*  type=> use for food selection to let it become 0 in ptype 3
	*/
	public static function calPrice($ptype,$discount,$price,$type)
	{
		$message ="";
		switch ($ptype) {
			case '1':
				$dis = $discount/100;
				$price *= $dis;
				$message = "Discount ".$discount." %";
				break;
			case '2':
				$price -= $discount;
				if($price <= 0)
				{
					$price = 0;
				}
				$message = "Discount RM".$discount;
				break;
			case '3':
				$price = $type == 1 ? $discount : 0;
				$message = "Only RM".$discount;
				break;
			default:
				# code...
				break;
		}
		
		$data['price'] = $price;
		$data['message'] = $message;
		return $data;
	}

	/*
	* create user used promotion data
	* id => promotion id
	*/
	public static function createUserUsed($id)
	{
		$data = new PromotionUserUsed;
		$data->id =$id;
		$data->uid = Yii::$app->user->identity->id;
		return $data;
	}

	/*
	* find promotion daily limit
	* if not create one
	*/
	public static function findDailiyLimit($id)
	{
		$today = date("Y-m-d");
		$model = PromotionDailyLimit::find()->where('id = :id and date = :d',[':id'=>$id,':d'=>$today])->one();
		
		if(empty($model))
		{
			$model = new PromotionDailyLimit;
			$model->id = $id;
			$model->date = $today;
			$model->food_limit = 0;
			
			if(!$model->save())
			{
				return;
			}
		}
		return $model;
	}	
}