<?php

namespace frontend\modules\Restaurant\controllers;

use yii;
use yii\web\Controller;
use frontend\controllers\CartController;
use common\models\food\Foodselection;
use common\models\food\Foodselectiontype;

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
                    $beforemarkedup = CartController::actionRoundoff1decimal($modelfoodselection->BeforeMarkedUp);
                    $markedup = $beforemarkedup * 1.3;
                    $markedup = CartController::actionRoundoff1decimal($markedup);
                    $modelfoodselection->BeforeMarkedUp = $beforemarkedup;
                    $modelfoodselection->Price = $markedup;

                    if (!($flag = $modelfoodselection->save(false))) {
                        return false;
                    }
                }
            }
        }
        return true;
	}

    public static function 
}
