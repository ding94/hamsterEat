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
}