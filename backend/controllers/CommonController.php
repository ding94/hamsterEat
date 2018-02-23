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
        $module = Yii::$app->controller->module->id;
	    
        if($module != 'app-backend')
        {
            $permissionName = $module.'/'.$controller.'/'.$action;
        }else
        {
             $permissionName =$controller.'/'.$action;
        }
		

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

    public static function getYear($year)
    {
        for($m=1; $m<13; $m+=1)
        {
            $month = date('F',strtotime('01-'.$m.'-'.$year.''));
            $list[] = $month;
        }
        return $list;
    }

    public static function getStartEnd($year)
    {
        for($m=1; $m<13; $m+=1)
        {
            $nm = $m+1;
            $start = strtotime('01-'.$m.'-'.$year.'');
            $end = strtotime('01-'.$nm.'-'.$year.'');
            $list[$m]= [$start,$end];
        }
        $nyear = $year+1;
        $list[12][1] = strtotime('01-01-'.$nyear.'');
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

    public static function roundoff1decimal($price)
    {
         return self::display2decimal(number_format((float)$price,1,'.',''));
    }

    public static function display2decimal($price)
    {
        return number_format((float)$price,2,'.','');
    }
}