<?php

namespace frontend\modules\delivery\controllers;

use yii\web\Controller;
use yii\filters\AccessControl;
use Yii;
use common\models\DeliveryAttendence;
/**
 * Default controller for the `delivery` module
 */
class DailySignInController extends Controller
{
	public function behaviors()
    {
         return [
             'access' => [
                 'class' => AccessControl::className(),
                 //'only' => ['logout', 'signup','index'],
                 'rules' => [
                     [
                         'actions' => ['index','signin'],
                         'allow' => true,
                         'roles' => ['rider'],
 
                     ],
                 ]
             ]
        ];
    }
    /**
     * Renders the index view for the module
     * @return string
     */
    public function actionIndex()
    {
    	$record = self::getDailyData(1);
    	
        return $this->render('index',['record' => $record]);
    }

    public function actionSignin()
    {
    	$record = self::updateSignRecord();
    	switch ($record) {
    		case 1:
    			Yii::$app->session->setFlash('warning', "Today Already Sign In");
    			break;
    		case 2:
    			Yii::$app->session->setFlash('Success', "Sign In Success");
    			break;
    		case 3:
    			Yii::$app->session->setFlash('warning', "Sign In Fail");
    			break;
    		case 4:
    			Yii::$app->session->setFlash("warning" ,'Today Sign in Time Already Pass!!');
    		default:
    			# code...
    			break;
    	}
    	return $this->redirect(Yii::$app->request->referrer);	
    }

    public static function getDailyData($type)
    {
    	$today = date("Y-m");
    	$date = date("d");
    	
    	$signin = DeliveryAttendence::find()->where('uid = :uid and month = :month',[':uid' => Yii::$app->user->identity->id, ':month' => $today])->one();
    	if(empty($signin))
    	{
    		$signin = self::createSignInRecord($today);
    	}

    	switch ($type) {
    		case 1:
    			$allDate = json_decode($signin->day);
    			$todayData = $allDate->$date;
    			break;
    		case 2:
    			$todayData = $signin;
    			break;
    		default:
    			break;
    	}
    	
    	return $todayData;
    }

    protected static function createSignInRecord($today)
    {
    	$data = "";
    	$month = date('t');
    	for($i = 1; $i<=$month ; $i++)
    	{
    		$data[$i]['result'] = 0;
    		$data[$i]['date'] = 0;
    	}
    	$delivery = new DeliveryAttendence;
    	$delivery->uid = Yii::$app->user->identity->id;
    	$delivery->day = json_encode($data);
    	$delivery->month = $today;
    	$delivery->save();
    	return $delivery;
    }

    protected static function updateSignRecord()
    {
    	$date = date("d");

    	$time = Yii::$app->formatter->asTime(time());

    	/*$validateTime = self::inBetweenDate($time);
    	if($validateTime == false)
    	{
    		return 4;
    	}*/
    	
    	$data = self::getDailyData(2);

    	$updateTime = json_decode($data->day);

    	$currentDay = $updateTime->$date;

    	if($currentDay->result == 1)
    	{
    		return 1;
    	}

    	$currentDay->result =1;
    	$currentDay->date = $time;

    	$updateTime =json_encode($updateTime);
    	
    	$data->day = $updateTime;
    	
    	if($data->save())
    	{
    		return 2;
    	}
    	else
    	{
    		return 3;
    	}
    }

    protected static function inBetweenDate($time)
    {
    	$early = date('09:00:00');
    	$last = date('10:30:00');
    	if($early <= $time && $last >= $time)
    	{
    		return true;
    	}
    	else
    	{
    		return false;
    	}
    }

}
