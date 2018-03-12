<?php

namespace frontend\modules\Food\controllers;

use Yii;
use yii\web\Controller;
use common\models\Model;
use yii\helpers\ArrayHelper;
use frontend\controllers\CartController;
use common\models\food\{Foodtypejunction,Foodtype,Foodstatus,Foodselectiontype,FoodSelectiontypeName};

class TypeAndStatusController extends Controller
{
	public static function createStatus($food,$status)
	{
      
		$food->Ingredient = "empty";
		$food->Price = CartController::actionDisplay2decimal($food->Price);
        $food->BeforeMarkedUp =  CartController::actionRoundoff1decimal($food->Price / 1.3);
		$data['food']  = $food;

		if($status->isNewRecord)
		{
			$status = new Foodstatus;
	    	$status->Status = 1;
	    	
		}
	    $data['status'] = $status;
	    return $data;
	}

	public static function detectJunction($junction)
    {
    	$post = Yii::$app->request->post();
    	$id = Foodtype::findOne($post['Foodtypejunction']['Type_ID']);
    	$name = Foodtype::find()->where('Type_Desc = :id',[':id'=>$post['Foodtypejunction']['Type_ID']])->one();
    	$data['message'] = 1;
    	$data['value'] = "";
    	
    	if(empty($id) && empty($name))
    	{
    		$type = new Foodtype;
    		$type->Type_Desc = $post['Foodtypejunction']['Type_ID'];

    		if($type->save())
    		{
    			$junction->Type_ID = $type->ID;
    		}
    		else
    		{
    			$data['mesage'] = 0;
    		}
    	}
    	else
    	{
    		if(!empty($name))
    		{
    			$post['Foodtypejunction']['Type_ID'] = $name->ID;
    			$junction->load($post);
    		}
    		else
    		{
    			$junction->load($post);
    		}
    		
    	}
    	
    	$data['value'] = $junction;
    	return $data;
    	
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

    public static function genType($type,$typeName)
    {
    	$post = Yii::$app->request->post();
    	
    	$newType =  Model::createMultiple(Foodselectiontype::classname(), $type,"ID");
  		$newName = Model::createMultiple(FoodSelectiontypeName::classname(), $typeName,"id");
  	
        Model::loadMultiple($newType,Yii::$app->request->post());

        Model::loadMultiple($newName,Yii::$app->request->post());
        $isvalid = Model::validateMultiple($newType) && Model::validateMultiple($newName);

        $array['isvalid'] = $isvalid;
        $array['type'] = $newType;
        $array['tname'] = $newName;
      
        return $array;
    }

  
}