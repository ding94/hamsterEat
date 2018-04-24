<?php

namespace common\models\PaymentGateWay;

use Yii;
use yii\helpers\Json;
use yii\helpers\Url;
use yii\web\Session;

class PaymentBill extends PaymentApi
{
	/*
	* get bill from collect id
	*/
	public function getBill($billid)
	{
		$data = array(
			'value' => "-1",
			'data' => array(),
		);

		$response =  PaymentApi::clientResult('GET',4,$billid);

		$result = Json::decode($response);
		
		if(array_key_exists("error", $result))
		{
			return $data;
		}

		$data['value'] = 1; 
		$data['data'] = $result;
		return $data;

	}

	/*
	* generate bill from collect id
	*/
	public function generateBill($collectid,$email,$name,$mobile,$amount,$pid)
	{
		$data = array(
			'value' => "-1",
			'link'=>"",
		);

		$collectidAvaiable = PaymentCollection::detectCollection($collectid);
		
		if($collectidAvaiable['value'] == -1)
		{
			return $data;
		}

		$passData = array(
			'collection_id'=>$collectid,
			'description'=>$collectidAvaiable['data']['title'],
			'email'=>$email,
			'name'=>$name,
			'amount'=>$amount*100,
			'callback_url'=>Url::to(['payment/notify'],'http'),
			'redirect_url'=>Url::to(['payment/notify'],'http'),
		);
		
		$response =  PaymentApi::clientResult('POST',2,$passData);
		$result = Json::decode($response);
		
		if(array_key_exists("error", $result))
		{
			return $data;
		}

		self::generateHistory($collectid,$result['id'],$pid);
		self::generateCookie($collectid,$result['id']);
		$data['value'] = "1";
		$data['link'] = $result['url'];
		return $data;
	}

	/*
	* generate history
	*/
	public static function generateHistory($collectionid,$billid,$pid)
	{
		$history = new PaymentGatewayHistory;
		$history->collect_id = $collectionid;
		$history->bill_id = $billid;
		$history->pid = $pid;
		$history->status = 0;
		$history->save();
	}

	public static function generateCookie($collectid,$billid)
	{
		$session = Yii::$app->session;
		$session->set('payment', ['collect_id'=>$collectid,'bill_id'=>$billid]);
	}
}