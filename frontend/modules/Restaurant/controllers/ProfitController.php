<?php
namespace frontend\modules\Restaurant\controllers;

use yii;
use yii\web\Controller;
use yii\helpers\Json;
use frontend\controllers\CommonController;
use common\models\Profit\RestaurantItemProfit;
use common\models\Profit\RestaurantProfit;
use common\models\Order\Orderitem;
use common\models\Order\DeliveryAddress;


class ProfitController extends CommonController
{
	public function actionIndex($rid,$first =0 ,$last =0)
	{
		$linkData = CommonController::restaurantPermission($rid);
        $link = CommonController::getRestaurantUrl($linkData[0],$linkData[1],$linkData[2],$rid);
        
		if($first == 0 || $last == 0)
		{
			$first = date("Y-m-d", strtotime("first day of this month"));
		
			$last = date("Y-m-d", strtotime("last day of this month"));
		}
		
	
		$itemProfit = RestaurantItemProfit::find()->where('rid = :rid',[':rid'=>$rid])->andWhere(['between','created_at',strtotime($first),strtotime($last)])->all();
		$data = [];
		foreach ($itemProfit as $key => $item) {
			$data[$item->did][] = $item;
		}
		
		return $this->render('index',['data'=>$data,'first'=>$first,'last'=>$last ,'link'=>$link]);
		
	}

	public static function getItemProfit($did)
	{
		$isValid = true;
		$data = [];
		$item = Orderitem::find()->where('Delivery_ID = :did',[':did' => $did])->joinWith(['food'])->all();

		foreach ($item as $key => $value) {
			$empty = json_encode(['empty'=>'N/A']);

            $selectionName = empty(Json::decode($value->trim_selection)) ? $empty : $value->trim_selection;
			$profit = new RestaurantItemProfit;
			$profit->oid = $value->Order_ID;
			$profit->did = $did;
			$profit->rid = $value->food->Restaurant_ID;
			$profit->quantity = $value->OrderItem_Quantity;
			$profit->finalPrice = $value->OrderItem_LineTotal;
			$profit->originalPrice = $profit->finalPrice/100 * 70;
			$profit->fid = Json::encode(['id'=>$value->Food_ID,'name'=>$value->food->Name]);
			
			$profit->sid = $selectionName;
			$isValid = $profit->validate() && $isValid;
			$data[] = $profit;
		}
		
		return $isValid == true ? $data : -1;
		
	}

	public static function getProfit($order,$did)
	{
		$address = DeliveryAddress::findOne($did);
		$profit = new RestaurantProfit;
        $profit->did = $order->Delivery_ID;
        $profit->cid = $address->cid;
        $profit->earlyDiscount = $order->Orders_DiscountEarlyAmount;
        $profit->voucherDiscount = $order->Orders_DiscountTotalAmount;
        $profit->total = $order->Orders_TotalPrice;

        return $profit;
	}
}