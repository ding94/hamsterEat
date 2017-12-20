<?php

namespace backend\controllers;
use yii\web\Controller;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use Yii;

Class CommonController extends Controller
{
	public function beforeAction($action)
	{
		$controller = Yii::$app->controller->id;
	    $action = Yii::$app->controller->action->id;
	    $permissionName = $controller.'/'.$action;    
		//var_dump($permissionName); exit;

	    if(!\Yii::$app->user->can($permissionName) && Yii::$app->getErrorHandler()->exception === null){
	        throw new \yii\web\UnauthorizedHttpException('Sorry, You do not have permission');
	    }
	    return true;
	}

	public static function getMonth($first,$last,$type)
    {
        $start_time = strtotime($first);

        $end_time =  strtotime($last);

        for($i=$start_time; $i<$end_time; $i+=86400)
        {
        	switch ($type) {
        		case '1':
        			$list[] = date('Y-m-d', $i);
        			break;
        		case '2':
        			$list[date('Y-m-d', $i)] = 0;
        			break;
        		default:
        			# code...
        			break;
        	}
           
         
           
        }
        
        return $list;
    }

    public static function convertToArray($object)
    {
        $data= [];
     
        $arrayKey = array_keys($object);
        foreach($arrayKey as $k=> $key)
        {
            $data[$k] = $object[$key];        
        }
        return $data;
    }
}