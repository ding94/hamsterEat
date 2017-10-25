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
	* order id is for restuarnt to know the order
	* delivery id is for user and deliveryman to know the process
	* $type => use for category the tree list
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
				break;
			case 4:
				$data = self::getUserorder($id);
				break;
			default:
				$data ="";
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
					$model->description = "Your Food ".$value['foodName']." is ".$value['currentStatus'];
					break;
				case 3:
					$model->description = "Your Order id : ".$id." has ready to pick up";
					break;
				case 4:
					$model->description = "Your Order id : ".$id." is ".$value['currentStatus'];
					break;
				default:
					
					break;
			}
			
			$model->save();
		}
		return true;
	}

	/*
	* get all restaurant worker id
	* rid use for a link to the order page
	*/
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

	/*
	* get user detail from order
	* get the current status and pres status for let the user know the status change
	*/
	public static function getUserdetail($oid)
	{
		$item = Orderitem::find()->joinWith(['order','food'])->where('Order_ID = :oid',[':oid'=> $oid])->one();
		$data[0]['uid'] = User::find()->where('username = :name',[':name'=>$item['order']['User_Username']])->one()->id;
		$data[0]['foodName'] = $item['food']['Name'];
		$data[0]['currentStatus'] = $item['OrderItem_Status'];
		//$data[0]['preStatus'] = self::getPreOrderStatus($item['OrderItem_Status']);
		return $data;
	}

	/*
	* get user oder detail
	* get the current status and pres status for let the user know the status change
	*/
	public static function getUserorder($did)
	{
		$item = Orders::find()->where('Delivery_ID = :did',[':did' => $did])->one();

		$data[0]['uid'] = User::find()->where('username = :name',[':name' => $item['User_Username']])->one()->id;
		$data[0]['currentStatus'] = $item['Orders_Status'];
		//$data[0]['preStatus'] = self::getPreOrderStatus($item['Orders_Status']);
		
		return $data;
	}

	/*
	* get deliveryman detail by order
	*/
	public static function getDeliverydetail($oid)
	{
		$item = Orderitem::find()->joinWith(['order'])->one();
		$data[0]['uid'] = User::find()->where('username = :name' ,[':name' => $item['order']['Orders_Deliveryman']])->one()->id;
		return $data;
	}

	/*
	* get the pre status base on status
	* currently hardcore may change next time
	*/
	public static function getPreOrderStatus($status)
	{
		switch ($status) {
			case "Preparing":
				$data = "Pending";
				break;
			case "On The Way":
				$data = "Pick Up";
				break;
			case "Completed":
				$data = "On The Way";
				break;
			default:
				$data = "";
				break;
		}
	
		return $data;
	}
}