<?php

namespace frontend\modules\Restaurant\controllers;

use yii;
use yii\web\Controller;
use yii\helpers\ArrayHelper;
use common\models\Restauranttypejunction;

class RestauranttypeController extends Controller
{
	public static function newRestaurantJunction($data,$id)
	{
		foreach ($data as $typeid) {
            $newtype = new Restauranttypejunction;
                               
            $newtype->Restaurant_ID =$id;
            $newtype->Type_ID = $typeid;
            $newtype->save();
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
}