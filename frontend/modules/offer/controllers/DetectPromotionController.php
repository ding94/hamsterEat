<?php

namespace frontend\modules\offer\controllers;

use Yii;
use yii\web\Controller;
use common\models\Cart\Cart;
use common\models\food\Food;
use common\models\Company\CompanyEmployees;
use common\models\promotion\PromotionLimit;

class DetectPromotionController extends Controller
{
	/*
	* calucate the cart price and delivery price
	* detect anoy promotion
	*/
    public static function calCheckOutPrice($cookie)
    {
    	$data = array();
    	$dailyLimit = array();
    	$originLimit = array();
    	$total = 0;
    	$dis = 0;

    	foreach($cookie['cid'] as $i=>$cid)
		{
			$cart = Cart::find()->where('cart.id = :id',[':id'=>$cid])->joinWith(['food'])->one();
			if($cookie['promotion'][$i] == 1)
			{
				$promotion = self::getPromotionData($cart->price,$cart->selectionprice,$cart->fid);
				if(empty($promotion))
				{
					return "";
				}
				
				$dis += $promotion['dis']*$cart->quantity;
				$daily = $promotion['daily'];
				$limit = $promotion['limit'];

				if(array_key_exists($daily->id,$dailyLimit))
				{
					$dailyLimit[$daily->id]->food_limit += $cart->quantity;
					$originLimit[$limit->id] = $limit;
				}
				else
				{
					
					$daily->food_limit += $cart->quantity;
					$dailyLimit[$daily->id] = $daily;
					$originLimit[$limit->id] = $limit;
				}
				
			}
			$countDelivery[$cart->food->Restaurant_ID] = 0;
			$total += $cart->price * $cart->quantity;

		}

		if(!empty($dailyLimit) && !empty($originLimit))
		{
			$valid = self::detectMaxLimit($dailyLimit,$originLimit);
			$data['userUsed'] = PromotionController::createUserUsed($limit->pid);
			if($valid)
			{
				return "";
			}
		}
		$data['total'] = $total;
		$data['dis'] = $dis;
		$data['dailyLimit'] = $dailyLimit;
		$data['countDelivery'] = $countDelivery;
		
		return $data;
    }

    /*
    *  detect daily limit is more then limit
    *  more then return false
    */
    protected static function detectMaxLimit($daily,$origin)
    {
    	foreach ($daily as $key => $value) {
    		if($value->food_limit > $origin[$key]->food_limit)
    		{
    			Yii::$app->session->setFlash('warning', Yii::t('food','You Cannot Order To Many Quantity In Promotion'));
    			return true;
    		}
    	}
    	return false;
    }

    /*
    * get discount price
    * get default limit and daily limit
    */
    protected static function getPromotionData($price,$selprice,$fid)
    {
    	$array = array();
    	$dis = self::getPrice($price,$selprice,$fid);
    	$promotion = PromotionController::getPromotion();
    	
    	$defaultLimit = self::getDailyList($fid,$promotion->id,$promotion->type_promotion);

    	if(empty($dis) || empty($defaultLimit))
    	{
    		return "";
    	}

    	$array['daily'] = $defaultLimit['daily'];
    	$array['limit'] = $defaultLimit['limit'];
    	$array['dis'] = $dis;
    	return $array;
    }

    /*
    * get price and selection price
    */
    protected static function getPrice($price,$selprice,$fid)
    {
    	$promotion = PromotionController::getPromotioinPrice($price-$selprice,$fid,1);
		if(empty($promotion))
		{
			Yii::$app->session->setFlash('warning', Yii::t('food','Promotion Already Finish'));
			return"";
		}

		$dis = ($price-$selprice)- $promotion['price'];
     
        $seldis = PromotionController::getPromotioinPrice($selprice,$fid,2);

        if(is_array($seldis))
	    {
	         $dis += $cart->selectionprice-$seldis['price'];
	    }

	    return $dis;
    }

    /*
    * get promotion daily and limit data
    * find limit with condition
    * base on type
    */
    public static function getDailyList($fid,$pid,$type)
    {
    	$array = array();
    	$food = Food::findOne($fid);
		
		$query = PromotionLimit::find()->where('pid = :pid',[':pid'=>$pid]);
		switch ($type) {
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
			return;
		}

		$dailyLimit = PromotionController::findDailiyLimit($limit->id);
		if(empty($limit))
		{
			return;
		}
		else
		{
			$array['daily'] =$dailyLimit;
			$array['limit'] = $limit;
			return $array;
		}
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
}
