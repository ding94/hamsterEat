<?php

namespace frontend\modules\Restaurant\controllers;

use yii;
use yii\web\Controller;
use common\models\food\Foodtypejunction;
use common\models\food\Foodstatus;

class FoodtypeAndStatusController extends Controller
{
	public static function newFoodJuntion($data,$id)
	{
		foreach ($data as $typeid) {
            $newtype = new Foodtypejunction;
                               
            $newtype->Food_ID =$id;
            $newtype->Type_ID = $typeid;
            $newtype->save();
        }
	}

	public static function newStatus($id)
	{
		$newstatus = new Foodstatus;

        $newstatus->Food_ID = $id;
        $newstatus->Status = 1;
        if($newstatus->save())
        {
        	return true;
        }
        else
        {
        	return false;
        }
	}
}