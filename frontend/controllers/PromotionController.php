<?php
namespace frontend\controllers;
use Yii;
use yii\web\Controller;
use common\models\promotion\{Promotion,PromotionLimit,PromotionDailyLimit,PromotionUserUsed};
use common\models\food\{Food,Foodselection};
use common\models\Company\{CompanyEmployees};

class PromotionController extends Controller
{
	/*
	* detect any promotion
	* find user use already the promotion
	*/
	public static function getPromotioinPrice($price,$id,$type)
	{
		$today = date("Y-m-d");
		$userUsed = false;
		$promotion = Promotion::find()->where(['<=','start_date',$today])->andWhere(['>=','end_date',$today])->one();

		if(empty($promotion))
		{
			return $price;
		}

		if(!Yii::$app->user->isGuest)
		{
			$userUsed = PromotionUserUsed::find()->where('id = :id and uid = :uid',[':id'=>$promotion->id,':uid'=>Yii::$app->user->identity->id])->exists();
		}
		

		if($userUsed)
		{
			return $price;
		}
		//$food->Price = self::calPrice($promotion->type_discount,$promotion->discount,$food->Price);
		
		return self::detectPromotion($promotion,$price,$id,$type);
	}

	/*
	* use query to find the PromotionLimitData 
	* if not data return price
	* and find daily limit
	* to calculate daily food limit reach max limit of food limit
	*/
	protected static function detectPromotion($promotion,$price,$id,$type)
	{

		$food = Food::findOne($id);
		
		$query = PromotionLimit::find()->where('pid = :pid',[':pid'=>$promotion->id]);
		switch ($promotion->type_promotion) {
			case 2:
				$query->andWhere('tid = :tid',[':tid'=>$food->Restaurant_ID]);
				break;
			case 3:
				$query->andWhere('tid = :tid',[':tid'=>$food->Food_ID]);
				break;
			case 4:
				$cid = self::detectCompany();
				$query->andWhere('tid = :tid',[':tid'=>$cid]);
				break;
			default:
				# code...
				break;
		}

		$limit = $query->one();

		if(empty($limit))
		{
			return $price;
		}

		$dailyLimit = self::findDailiyLimit($promotion->id);
		
		if(empty($dailyLimit))
		{
			return $price;
		}
		
		$data = $price;

		if($dailyLimit->food_limit <= $limit->food_limit)
		{
			$data =  self::calPrice($promotion->type_discount,$promotion->discount,$food->Price,$type);
		}
		
		return $data;
	}

	/*
	* find User Company if not exist return -1
	*/
	protected static function detectCompany()
	{
		if(Yii::$app->user->isGuest)
		{
			return -1;
		}
		$company = CompanyEmployees::find()->where('uid = :uid',[":uid"=>Yii::$app->user->identity->id])->one();
		return empty($company) ? -1 : $company->cid;
	}

	/*
	*  recalculate the price using type and discount
	*  ptype => type of discount 
	*  type=> use for food selection to let it become 0 in ptype 3
	*/
	protected static function calPrice($ptype,$discount,$price,$type)
	{
		$message ="";
		switch ($ptype) {
			case '1':
				$dis = (100-$discount)/100;
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
	* find promotion daily limit
	* if not create one
	*/
	protected static function findDailiyLimit($id)
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