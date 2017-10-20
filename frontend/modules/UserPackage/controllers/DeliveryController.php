<?php

namespace frontend\modules\UserPackage\controllers;

use yii\web\Controller;
use Yii;
use common\models\Package\UserPackageDeliveryDate;

class DeliveryController extends Controller
{
	/*
	* loop all selected DateTime and save
	* status => 0 
	* 0 = not delivery
	*/
	public static function createDeliveryDate($allDateTime,$pid)
	{
		foreach($allDateTime as $dateime)
		{
			$data = new UserPackageDeliveryDate;
			$data->pid = $pid;
			$data->date = $dateime;
			$data->status = 0;
			
			if(!$data->save())
			{
				return false;
			}
		}
		return true;
	}
}