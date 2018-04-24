<?php

namespace common\models\PaymentGateWay;

use Yii;
use yii\base\Model;
use yii\httpclient\Client;
use yii\helpers\Url;

Class PaymentApi extends Model
{
	const base_url = "https://www.billplz.com/api/v3/";
	const api_key = "b40ef8dc-5bd8-49c9-bfe5-2d33efaa62d5";


	/*
	* generate header key
	*/
	private function getAuthoriza()
	{
		$data = ['Authorization' => 'Basic '.base64_encode(self::api_key)];
		return $data;
	}

	/*
	* 1=> generate collection
	* 2=> payment 
	*/
	private function getUrl($type,$id="")
	{
		$link = self::base_url;
		switch ($type) {
			case 1:
				$link = $link."collections";
				break;
			case 2:
				$link = $link."bills";
				break;
			case 3:
				$link = $link."collections/".$id;
				break;
			case 4:
				$link = $link."bills/".$id;
				break;
			default:
				# code...
				break;
		}
		return $link;
	}

	public function clientResult($method,$type,$data)
	{
		$client = new Client(['transport' => 'yii\httpclient\CurlTransport']);
		if($method == "POST")
		{
			$link = self::getUrl($type);
		}
		else
		{
			$link = self::getUrl($type,$data);
		}
		
		
		$response = $client->createRequest()->setMethod($method)->setUrl([$link])->addHeaders(self::getAuthoriza());
		
		if($method == 'POST')
		{
			$response = $response->setData($data)->send();
		}
		else
		{
			$response = $response->send();
		}
		
		return $response->getContent();
	}

	
};