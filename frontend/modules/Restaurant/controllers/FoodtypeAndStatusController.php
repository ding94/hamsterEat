<?php

namespace frontend\modules\Restaurant\controllers;

use yii;
use yii\web\Controller;
use yii\helpers\ArrayHelper;
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

    /*
    * diff between old data and new junction data
    * 0 => need delete data
    * 1 => new data
    */
    public static function diffStatus($data,$typeID)
    {
        $oldFoodTypeId = ArrayHelper::map($data,'Type_ID','Type_ID');

        $deletedFoodTypeId = array_diff($oldFoodTypeId, $typeID);

        $newFoodTypeId = array_diff($typeID,$oldFoodTypeId);

        $data[0] = $deletedFoodTypeId;
        $data[1] = $newFoodTypeId;

        return $data;
    }

    /*
    * detect whether the food is food pack
    */
    public static function getFoodPack($data)
    {
        foreach($data as $type)
        {
            if($type->ID == 5)
            {
                return true;
            }
        }
        return false;
    }
}