<?php
namespace frontend\controllers;

use frontend\controllers\CommonController;
use common\models\sms\SmsSender;
use common\models\user\Userdetails;
use yii\web\Session;
use Yii;

class PhoneController extends CommonController
{
	/*
	* generate phone random number send 
	* use cookie to detect
	*/
	public function actionValidate()
	{
		$data = array();
		$data['value'] = 0;
		$data['message'] = "";

		$post = Yii::$app->request->post();

		// detect time limit for 60sec, prevent from resend loop
		$session = Yii::$app->session;
		if (!empty($session->get('validation'))) {
			$time_left = $session->get('validation')['time-limit'] - time();
			if($time_left >= 0){
				$data['message'] = Yii::t('user','Please wait for ').$time_left.' '.Yii::t('user','seconds to resend sms.');
				return json_encode($data);
			}
		}
		if(empty($post['phone_number']))
		{
			$data['message'] = "Empty Phone Number";
			return json_encode($data);
		}
		if (Userdetails::find()->where('User_ContactNo = :c',[':c'=>$post['phone_number']])->one()) {
			$data['message'] = Yii::t('site','This Phone number has already been taken.');
			return json_encode($data);
		}
		$random_digit = mt_rand(100000, 999999);
		$sms = new SmsSender;
		$sms->type = 3;
		$sms->phone_number = $post['phone_number'];
		$sms->content = "Your Validate Number : ".$random_digit;
				
		if($sms->validate())
		{
			if($sms->send())
			{
				$this->detectSession();
				$time = time();
				$session = Yii::$app->session;
				$session['validation'] = [
				    'time' => $time,
				    'time-limit' =>$time + 60,
				    'lifetime' => 3600,
				    'code' => md5($random_digit.$time.$sms->phone_number),
				];
				$data['value'] = 1;
				$data['message'] = "Success Sending Validation Code";
				return json_encode($data);
			}

		}
		$data['message'] = "Some Thing Went Wrong.";
		return json_encode($data);

	}

	public static function ValidatePhone($validate_code,$contact_number)
	{
		$session = Yii::$app->session;
        $validate = $session->get('validation');
        if(empty($validate))
        {
        	Yii::$app->session->setFlash('warning','Code Expire');
        	return false;
        }

        $code = md5($validate_code.$validate['time'].$contact_number);
       	self::detectSession();
       	
        if(time()-$validate['time'] > 600)
        {
        	 
        	Yii::$app->session->setFlash('warning','Code Expire');
        	return false;
        }
       
        if($code === $validate['code'])
        {
        	return true;
        }

        Yii::$app->session->setFlash('warning','Code Does Not Match');
        return false;
       
	}

	protected static function detectSession()
	{
		$session = Yii::$app->session;
		$data = $session->get('validation');
		if($data)
		{
			$session->remove('validation');
		}
	}
}