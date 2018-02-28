<?php

namespace frontend\modules\Restaurant\controllers;

use yii;
use yii\web\Controller;
use yii\helpers\ArrayHelper;
use common\models\food\Foodtypejunction;
use common\models\food\Foodstatus;

class FoodtypeAndStatusController extends Controller
{
	public static function newFoodJuntion($typeid,$id)
	{

        $newtype = new Foodtypejunction;
        $newtype->Food_ID =$id;
        $newtype->Type_ID = $typeid;
        $newtype->save();
        return $newtype;
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
    public static function diffStatus($prevData,$typeID)
    {
        $data[0] ="";
        $data[1] ="";
        
        if(empty($prevData))
        {
            $data[1] = $typeID;
        }
        else
        {
            if($prevData[0]->Type_ID != $typeID)
            {
                $data[0] = $prevData[0]->Type_ID;
                $data[1] = $typeID;
            }
        }
        

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

    /*
    * detect max and min choice
    */
    public static function detectMinMax($type,$selection)
    {
        $valid = false;
        foreach ($type as $key => $value) {

            $count = count($selection[$key]);
             
            if($value['Min'] > $count )
            {
                Yii::$app->session->setFlash('warning', "Minumun Choice Cannot more Then Food Selection");
                $valid = true;
                break;
            }

            if($value['Min'] > $value['Max'])
            {
                Yii::$app->session->setFlash('warning', "Minumun Choice Cannot more Then Maximun Choice");
                $valid = true;
                break;
            }
        }
        return $valid;
    }
}