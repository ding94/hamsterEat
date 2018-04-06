<?php
namespace frontend\modules\offer\controllers;

use Yii;
use yii\web\Controller;
use frontend\controllers\CartController;
use common\models\food\Food;
use common\models\promotion\{Promotion,PromotionLimit,PromotionUserUsed};

class ReverseController extends Controller
{
	public static function calDiscount($order,$data,$uid)
	{
		$dis = 0;
		foreach($order->item as $item)
        {
            if($item->Order_ID != $data->Order_ID)
            {
                $dis += self::calculatePromotion($item,$data->Delivery_ID,$uid,$item->Food_ID);
            }
        }
        $order->Orders_DiscountTotalAmount = CartController::actionRoundoff1decimal($dis);
       
        return $order;
	}
	/*
	* recalculate the promotion price
	*/
	protected static function calculatePromotion($item,$did,$uid,$fid)
	{
		$promotion = self::isDiscount($uid,$did,$fid);

		if(empty($promotion))
		{
			return 0;
		}

        $price = $item->OrderItem_LineTotal - $item->OrderItem_SelectionTotal;
        $dis = PromotionController::calPrice($promotion->type_discount,$promotion->	discount,$price,1);
       
        $disPrice = $price-$dis['price'];

        if($promotion->enable_selection == 1)
        {
        	if($item->OrderItem_SelectionTotal != 0)
        	{
        		$seldis = PromotionController::calPrice($promotion->type_discount,$promotion->discount,$item->OrderItem_SelectionTotal,2);
        		$disPrice += $item->OrderItem_SelectionTotal - $seldis['price'];
        	}
        	
        }
       	return $disPrice;
	}

	/*
	* find Promotion Discount
	* if is food or restaurant prmotion
	* find base on tid
	*/
	protected static function isDiscount($uid,$did,$fid)
	{
		$pused = PromotionUserUsed::find()->where('uid = :uid and did = :did',[':uid'=>$uid,':did'=>$did])->one();
		
        $promotion = Promotion::findOne($pused->id);

        if($promotion->type_promotion == 2 || $promotion->type_promotion == 3)
        {
        	$tid = $fid;
        	if($promotion->type_promotion == 2)
        	{
        		$food = Food::findOne($fid);
        		$tid = $food->Restaurant_ID;
        	}
        	$limit = PromotionLimit::find()->where('pid = :pid and tid = :tid',[':pid'=>$promotion->id,':tid'=>$tid])->exists();
        	if(!$limit)
        	{
        		return "";
        	}
        }

        return $promotion;
	}

	
}