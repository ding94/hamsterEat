<?php

namespace frontend\modules\notification\controllers;

use Yii;
use yii\web\Controller;
use frontend\controllers\CommonController;
use common\models\User;
use common\models\food\FoodName;
use common\models\Order\DeliveryAddress;
use common\models\sms\SmsSender;
use common\models\user\UserNotification;
use common\models\notic\{NotificationSetting,Notification,NotificationType};

class OrderController extends CommonController
{
	/*
	* find notifcation setting data 
	* to detect whether send notification to user 
	* base on
	* => tid,sid
	* => id can be delivery id or order id
	* => uid is user id
	* => base on eable more then 0
	*/
	public static function createUserNotic($tid,$sid,$id,$uid)
	{
		$setting = NotificationSetting::find()->where("tid = :t and sid = :sid and enable != '-1'",[':t'=>$tid,':sid'=>$sid])->all();
		
		if(empty($setting))
		{
			return true;
		}
		
		foreach($setting as $i=>$value)
		{
			if($value->enable == 0 || $value->enable == 1)
			{
				$user = UserNotification::find()->where('setting_id = :id and uid = :uid ',[':id'=>$value->id,':uid'=>$uid])->one();
				$enable = empty($user) ? $value->enable : $user->enable;
				if($enable == 1)
				{
					$result[$i] = self::centerDetectType($value->setting_type_id,$tid,$id,$value->description);
				}
			}
			else
			{
				$result[$i] = self::centerDetectType($value->setting_type_id,$tid,$id,$value->description);
			}
			
		}
	}

	protected static function centerDetectType($stid,$tid,$id,$description)
	{
		$result = array();

		switch ($stid) {
			case 1:
				$result = self::genearateNotic($tid,$id,$description);
				break;
			case 2:
				$result = self::genereteEmail($tid,$id,$description);
				break;
			case 3:
				$result = self::genereteSms($tid,$id,$description);
				break;
			default:
					# code...
				break;
		}
		return $result;
	}

	
	public static function genearateNotic($type,$id,$description)
	{
		$data = self::evaluateData($type,1,$id,$description);

		if($data['value'] == 0)
		{
			return false;
		}

		$notic = new Notification;
		$notic->uid = $data['uid'];
		$notic->tid = $data['did'];
		$notic->type = $type;
		$notic->description = $description;
		
		if($notic->save())
		{
			return true;
		}
	
		return false;
		
	}

	public function genereteEmail($type,$id,$description)
	{
		$data = self::evaluateData($type,2,$id,$description);
	
		if($data['value'] == 0)
		{
			return false;
		}
		$did = empty($data['truedid']) ? $data['did'] : $data['truedid'];
		$email = \Yii::$app->mailer->compose(['html' => 'order-html'],['message'=>$data['message'],'did'=>$did])//html file, word file in email     
       	->setTo($data['email'])
        ->setFrom([\Yii::$app->params['supportEmail'] => \Yii::$app->name])
        ->setSubject($data['message']." (No Reply)");
        
		if($email->send())
		{
			return true;
		}
	
		return false;
	}

	public static function genereteSms($type,$id,$description)
	{
		$data = self::evaluateData($type,3,$id,$description);
		
		if($data['value'] == 0)
		{
			return false;
		}

		$sms = new SmsSender;
		$sms->type = $type;
		$sms->phone_number = $data['contactno'];
		$sms->content = $data['message'];
		
		if($sms->validate())
		{
			if($sms->send())
			{
				return true;
			}

		}
		return false;
	}

	public static function evaluateData($type,$case,$id,$description ="")
	{
		$array = array();
		$notictype = self::getType($type);
		$data = $notictype->name::findOne($id);
		
		if(empty($data))
		{
			$array['value'] = 0;
			return $array;
		}
		if(!empty($notictype->structure))
		{
			$new  = $data[$notictype->structure];
		}
		else
		{
			$new = $data;
		}
		
		$array = self::createNeedData($type,$case,$data,$description,$new);
		
		$array['value'] = 1;
		return $array;
	}

	public static function getType($type)
	{
		$data = NotificationType::findOne($type);
		return $data;
	}

	protected static function createNeedData($type,$case,$data,$description,$notictype)
	{
		$array =array();
		Yii::$app->language = 'en';
		switch ($case) {
			case 1:
				$user =  User::find()->where('username = :u',[':u'=>$notictype->User_Username])->one();
				$array['did'] = $data->Delivery_ID;
				$array['uid'] = $user->id;
				break;
			case 2:
				$user = User::find()->where('username = :u',[':u'=>$notictype->User_Username])->one();
				$array['did'] = $data->Delivery_ID;
				$array['email'] = $user->email;
				break;
			case 3:
				$address = DeliveryAddress::findOne($data->Delivery_ID);
				$array['contactno'] = $address->contactno;
				break;
			default:
				# code...
				break;
		}
		
		$text = explode("{",$description);
		$keyword = explode("}",$text[1]);
		
		if($keyword[0] == 'foodname')
		{
		
			$foodname = FoodName::find()->where("id = :fid and language ='en'",[':fid'=>$data->Food_ID])->one();
			$array['message'] = \Yii::t('food',$description,[$keyword[0]=>$foodname->translation]);
			$array['did'] = $data->Order_ID;
			$array['truedid'] = $data->Delivery_ID;
		}
		else
		{
			$array['message'] = \Yii::t('food',$description,['id'=>$data->Delivery_ID]);
		}
		
		return $array;
	}

}