<?php

namespace common\models\sms;

use Yii;
use yii\base\Model;
use yii\httpclient\Client;

Class SmsSender extends Model
{
	const url = 'http://cloudsms.trio-mobile.com/index.php/api/bulk_mt';
	const api_key = '6122bab4f20c0a275b9929b72833e0e41b4985d5f54acf4bdef6b0dfbedce878';
	const action = 'send';
	const sender_id = 'CLOUDSMS';
	const content_type = 1;
	const mode = 'shortcode';
	const campagin_name = "HamsterEat";
	public $content;
	public $phone_number;
	public $type;

	public function rules()
	{
		return[
			[['content','phone_number'],'required'],
			['phone_number','match','pattern'=>'/^[0]{1}[1-9]{1}[0-9]{7,9}$/'],
		];
	}

	public function send()
	{
		$client = new Client();
		$response = $client->createRequest()->setMethod('GET')->setUrl([self::url])->setData($this->generateData())->send();
		
		$this->createLog($response->getContent());
		if($response->getContent() < 0)
		{
			return false;
		}
		else
		{
			return true;
		}
	}

	private function generateData()
	{
		$data = array();
		$data=[
			'api_key'=>self::api_key,
			'action'=> self::action,
			'to' => $this->phone_number,
			'msg' => self::getContent(),
			'sender_id' => self::sender_id,
			'content_type' => self::content_type,
			'mode'=> self::mode,
			'campaign' => self::campagin_name,
		];
		return $data;
	}

	private function getContent()
	{
		return "From HamsterEat: ".$this->content.".[HamsterEat]";
	}

	private function createLog($result)
	{
		$log = new SmsLog;
		$log->type = $this->type;
		$log->result = $result;
		$log->content = self::getContent();
		$log->save();
		
	}

}