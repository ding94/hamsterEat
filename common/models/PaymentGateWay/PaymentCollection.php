<?php

namespace common\models\PaymentGateWay;

use yii\helpers\Json;

class PaymentCollection extends PaymentApi
{
	/*
	* create a collection
	*/
	public static function generateCollection($did)
	{
		$data = array(
			'value' => "-1",
			'id' => 0,
		);

		$dataPass = ['title'=>'Hamster Eat Delivery ID : '.$did." Payment"];
		$response = PaymentApi::clientResult('POST',1,$dataPass);
		
		$result = Json::decode($response);
	
		if(array_key_exists("error", $result))
		{
			$data['id'] = $result;
			return $data;
		}
		$data['value'] = 1; 
		$data['id'] = $result['id'];
		return $data;
	}

	/*
	* detect collection is avaiable base on status
	*/
	public static function detectCollection($collectionid)
	{
		$data = array(
			'value' => "-1",
			'data' => array(),
		);

		$response =  PaymentApi::clientResult('GET',3,$collectionid);
		$result = Json::decode($response);

		if(array_key_exists("error", $result))
		{
			return $data;
		}
		
		if($result['status'] == 'active')
		{
			$data['value'] = 1;
			$data['data'] = $result;
			return $data;
		}
		return $data;
	}	
}