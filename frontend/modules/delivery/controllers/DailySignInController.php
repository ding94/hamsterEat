<?php

namespace frontend\modules\delivery\controllers;

use yii\web\Controller;
use yii\filters\AccessControl;
use Yii;
use yii\helpers\ArrayHelper;
use frontend\models\Deliveryman;
use common\models\DeliveryAttendence;
use frontend\controllers\CommonController;
use common\models\Area;
/**
 * Default controller for the `delivery` module
 */
class DailySignInController extends CommonController
{
	public function behaviors()
    {
         return [
             'access' => [
                 'class' => AccessControl::className(),
                 //'only' => ['logout', 'signup','index'],
                 'rules' => [
                     [
                         'actions' => ['index','signin','delivery-location'],
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

    /*
    * get all sign in data
    */
    public static function getAllDailyRecord()
    {
    	$today = date("Y-m");
    	$date = date("j");

    	$all ="";
    	$signData = DeliveryAttendence::find()->where(' month = :month',[':month' => $today])->all();
		
    	foreach($signData as $k=> $data)
    	{
    		$allDate = json_decode($data->day);
    		if($allDate->$date->result == 1)
			{
				$all[] = $data->uid;
			}
			
				
    	}
		
    	return $all;
    }

    /*
    * get today delivery man data
    * 1 => get decode today sign in data 
    * 2 => pass all data
    */
    protected static function getDailyData($type)
    {
    	$today = date("Y-m");
    	$date = date("j");
    	
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

    /*
    * detect whether user sign in
    * 1 => already sign in
    * 2 => success  sign in
    * 3 => fail to save data
    */
    protected static function updateSignRecord()
    {
    	$date = date("j");

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
            self::updateAssigement();
    		return 2;
    	}
    	else
    	{
    		return 3;
    	}
    }

    protected static function updateAssigement()
    {
        $large = deliveryman::find()->max("DeliveryMan_Assignment");
        $data = deliveryman::find()->where('User_id = :id',[':id' => Yii::$app->user->identity->id])->one();
        $data->DeliveryMan_Assignment = $large;
        $data->save();
    }

    /*
    * Deliveryman sign in time
    */
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
  public function actionDeliveryLocation()
    {
        $postcode = new Area();
		$postcodeArray = ArrayHelper::map(Area::find()->all(),'Area_Group','Area_Group');
		$area="SELECT DISTINCT Area_Group from area";
		$area = Yii::$app->db->createCommand($area)->queryAll();
		$find = new Deliveryman();
		// var_dump($find->load(Yii::$app->request->post()));exit;
		if($find->load(Yii::$app->request->post()))
        {
			// var_dump($find);exit;
			$areaa = $find->DeliveryMan_AreaGroup;
			
			$search = Deliveryman::find()->where('User_id = :id', [':id' => Yii::$app->user->identity->id])->one();
			$search->DeliveryMan_AreaGroup = $areaa;
			$search->save();
			Yii::$app->session->setFlash('success', "Update completed");
            return $this->redirect(['/user/user-profile']);
			// $sql="UPDATE deliveryman SET DeliveryMan_AreaGroup = ".$postcodeArray." WHERE User_id =18";
			//  Yii::$app->db->createCommand($sql)->execute();
		}

        return $this->render('deliverylocation', ['postcode'=>$postcode,'area'=>$area,'postcodeArray'=>$postcodeArray,'find'=>$find]);
    }
}

