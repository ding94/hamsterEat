<?php

namespace frontend\controllers;

use Yii;
use yii\web\Controller;
use common\models\User;
use common\models\Orderitem;
use common\models\Orders;
use common\models\Notification;

class NotificationController extends Controller
{
	/*
	* id => can be order item or delivery id
	* based on type
	* type 
	* => 1 delivery id
	* => 2 order id
	*/
	public static function createNotification($id,$type)
	{
		switch ($type) {
			case 1:
				$data = self::getRestaurantdetail($id);
				break;
			case 2:
				$data = self::getUserdetail($id);
				break;
			case 3:
				$data = self::getDeliverydetail($id);
			default:
				# code...
				break;
		}
		
		if(empty($data))
		{
			return false;
		}
	
		foreach($data as $value)
		{
			$model = new Notification;
			$model->uid = $value['uid'];
			$model->type = $type;

			switch ($type) {
				case 1:
					$model->rid = $value['rid'];
					$model->description = "A New Order Was Made";
					break;
				case 2:
					$model->description = "Your Food ".$value['foodName']." status change from ".$value['preStatus']." to ".$value['currentStatus'];
					break;
				default:
					$model->description = "Your Order id : ".$id." has ready to pick up";
					break;
			}
			
			$model->save();
		}
		return true;
	}

	public static function getRestaurantdetail($did)
	{
		$food = Orderitem::find()->joinWith('food.manager')->where('Delivery_ID = :did',[':did' => $did])->all();
		
		foreach($food as $restaurant)
		{
			foreach ($restaurant['food']['manager'] as $k=> $value) 
			{
				$uid = User::find()->where('username = :name',[':name' => $value['User_Username']])->one();
				$data[$k]['uid'] = $uid->id;
				$data[$k]['rid'] = $restaurant['food']['Restaurant_ID'];
			}
			
		}
		return $data;
	}

	public static function getUserdetail($oid)
	{
		$item = Orderitem::find()->joinWith(['order','food'])->where('Order_ID = :oid',[':oid'=> $oid])->one();
		$data[0]['uid'] = User::find()->where('username = :name',[':name'=>$item['order']['User_Username']])->one()->id;
		$data[0]['foodName'] = $item['food']['Name'];
		$data[0]['currentStatus'] = $item['OrderItem_Status'];
		$data[0]['preStatus'] = self::getPreOrderStatus($item['OrderItem_Status']);
		return $data;
	}

	public static function getDeliverydetail($oid)
	{
		$item = Orderitem::find()->joinWith(['order'])->one();
		$data[0]['uid'] = User::find()->where('username = :name' ,[':name' => $item['order']['Orders_Deliveryman']])->one()->id;
		return $data;
	}

	public static function getPreOrderStatus($status)
	{
		switch ($status) {
			case "Preparing":
				$data = "Pending";
				break;
			case "Ready For Pick Up":
				$data = "Preparing";
				break;
			case "Picked Up":
				$data = "Ready For Pick Up";
			default:
				$data = "";
				break;
		}
	
		return $data;
	}
}