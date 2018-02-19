<?php

namespace frontend\controllers;

use Yii;
use yii\web\Controller;
use yii\helpers\ArrayHelper;	
use common\models\User;
use common\models\Order\Orderitem;
use common\models\Order\Orders;
use common\models\Order\DeliveryAddress;
use common\models\Notification;
use common\models\NotificationSetting;
use common\models\Order\StatusType;
use yii\data\Pagination;
use frontend\controllers\CommonController;
use frontend\modules\UserPackage\controllers\PackageController;

class NotificationController extends CommonController
{

	/*
	* view all notification for the user
	* turn off the notification in the parm to read
	*/
	public function actionIndex($type = 0)
	{
		$this->layout = 'user';
		$title = Yii::t('notification',"All Notification");
		self::turnOffNotification();
		$link = CommonController::createUrlLink(6);
		$query = Notification::find()->where('uid = :uid',[':uid' =>Yii::$app->user->identity->id ]);

		switch ($type) {
			case 1:
				$query->andWhere('view = 0');
				$title = Yii::t('notification',"Unread");
				break;
			case 2:
				$query->andWhere('view = 1');
				$title = Yii::t('notification',"Read");
				break;
			default:
				# code...
				break;
		}
	    $count = $query->count();
      	
        $pagination = new Pagination(['totalCount' => $count]);		    
        $notification = $query->offset($pagination->offset)->limit($pagination->limit)->orderBy(['updated_at'=> SORT_DESC])->all();
      
		return $this->render('index',['notification'=>$notification,'pages' => $pagination,'link'=>$link,'title'=>$title]);
	}

	public function actionTurnoff()
	{
		Notification::updateAll(['view' => 1],'uid = :uid',[':uid'=>Yii::$app->user->identity->id]);
		return $this->redirect(Yii::$app->request->referrer); 
	}

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
					$model->rid = $value['rid'];
					break;
				case 3:
					$model->description = "Your Delivery id : ".$id." has ready to pick up";
					break;
				case 4:
					$model->description = "Your Delivery id : ".$id." is ".$value['currentStatus'];
					$model->rid = $id;
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
	* get the delivery ID from link to my order
	*/
	public static function getUserdetail($oid)
	{
		$item = Orderitem::find()->joinWith(['order','food'])->where('Order_ID = :oid',[':oid'=> $oid])->one();
		$data[0]['uid'] = User::find()->where('username = :name',[':name'=>$item['order']['User_Username']])->one()->id;
		$data[0]['rid'] = $item['order']['Delivery_ID'];
		$data[0]['foodName'] = $item['food']['originName'];
		$status = StatusType::findOne($item['OrderItem_Status']);
		$data[0]['currentStatus'] = $status->type;
		//$data[0]['preStatus'] = self::getPreOrderStatus($item['OrderItem_Status']);
		return $data;
	}

	/*
	* get user oder detail
	* get the current status and pres status for let the user know the status change
	*/
	public static function getUserorder($did)
	{
		$item = Orders::find()->where('Delivery_ID = :did',[':did' => $did])->joinWith('user')->one();

		$data[0]['uid'] = User::find()->where('username = :name',[':name' => $item['User_Username']])->one()->id;
		$status = StatusType::findOne($item['Orders_Status']);
		$data[0]['currentStatus'] = $status->type;
		//$data[0]['preStatus'] = self::getPreOrderStatus($item['Orders_Status']);

		$email = \Yii::$app->mailer->compose(['html' => 'orderLink-html'],['item'=>$item])//html file, word file in email     
       	->setTo($item['user']['email'])
        ->setFrom([\Yii::$app->params['supportEmail'] => \Yii::$app->name])
        ->setSubject('Order is on Its Way (No Reply)')
        ->send();
		return $data;
	}

	/*
	* get deliveryman detail by order
	*/
	public static function getDeliverydetail($did)
	{
		$address = DeliveryAddress::findOne($did);
		//var_dump($item);exit;
		
		$data[0]['uid'] = $address->deliveryman;
		return $data;
	}

	/*
	* get the pre status base on status
	* currently hardcore may change next time
	*/
	public static function getPreOrderStatus($status)
	{
		switch ($status) {
			case 2:
				$data = "Pending";
				break;
			case 5:
				$data = "Pick Up";
				break;
			case 6:
				$data = "On The Way";
				break;
			default:
				$data = "";
				break;
		}
	
		return $data;
	}

	/*
	* update all params notification to be readed
	*/
	public static function turnOffNotification()
	{
		$data = [];
		if(!empty(Yii::$app->params['notication']))
		{
			foreach(Yii::$app->params['notication'] as $notic)
			{
				
				$data[]= array_column($notic, 'id');
			}

			$data = PackageController::removeNestedArray($data);
			
			foreach($data as $id)
			{
				$model = Notification::findOne($id);
				$model->view = 1;
				$model->save();
			}
		}
	}

}