<?php

namespace frontend\modules\Restaurant\controllers;

use yii;
use yii\web\Controller;
use yii\helpers\ArrayHelper;
use frontend\controllers\CartController;
use common\models\food\Foodselection;
use common\models\food\Foodselectiontype;
use common\models\food\Food;

class FoodselectionController extends Controller
{
	public static function validatefoodselection($post)
	{
		foreach ($post as $i => $foodtypes) {
            foreach ($foodtypes as $ix => $foodselections) {
                $data['Foodselection'] = $foodselections;
                $modelfoodselection = new Foodselection;
                $modelfoodselection->load($data);
                $foodselection[$i][$ix] = $modelfoodselection;
                $valid = $modelfoodselection->validate();
            }
        }
        return $foodselection;
	}

	public static function createfoodselection($foodtype,$foodselection,$id)
	{

		foreach ($foodtype as $i => $modelfoodtype) {

            $modelfoodtype->Food_ID = $id;
                                
            if (!($flag = $modelfoodtype->save(false))) {
                                    
                return false;
            }

            if (isset($foodselection[$i]) && is_array($foodselection[$i])) 
            {
                foreach ($foodselection[$i] as $ix => $modelfoodselection) {
                    $modelfoodselection->Type_ID = $modelfoodtype->ID;
                    $modelfoodselection->Food_ID = $id;
                    
                    $modelfoodselection->Price = CartController::actionDisplay2decimal($modelfoodselection->Price);
                    $modelfoodselection->BeforeMarkedUp =  CartController::actionRoundoff1decimal($modelfoodselection->Price / 1.3);;
                   
                    if (!($flag = $modelfoodselection->save(false))) {
                        return false;
                    }
                }
            }
        }
        return true;
	}

    /*
    * get old food selection data or selection data
    */
    public static function oldData($data,$type)
    {
        $oldSelect = [];
        $modelSelect = [];
        foreach ($data as $i => $select) 
        {
            $foodSelection = $select->foodSelection;
            $modelSelect[$i] = $foodSelection;
            $oldSelect = ArrayHelper::merge(ArrayHelper::index($foodSelection, 'ID'), $oldSelect);
        }
        switch ($type) {
            case 1:
                return $modelSelect;
                break;
            case 2:
                 return $oldSelect;
            default:
                return false;
                break;
        }
       
    }

    public static function enableOff($id)
    {
        $selection = Foodselection::findOne($id);
        $type = Foodselectiontype::findOne($selection->Type_ID);
        $currentOn = Foodselection::find()->where('Type_ID = :tid and Status = 1',[':tid'=>$type->ID])->count();
       
        if($type->Min >= $currentOn)
        {
              Yii::$app->session->setFlash('danger', Yii::t('m-restaurant',"Food Selection Type")." : ".$type->TypeName." ".Yii::t('m-restaurant','require at least')." ".$type->Min. " ".Yii::t('m-restaurant',"to be active."));
            return false;
        }
        else
        {
            return true;
        }
       
    }
}
