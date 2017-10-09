<?php

namespace frontend\modules\Restaurant\controllers;

use yii;
use yii\web\Controller;
use common\models\food\Foodselection;

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
       // $returndata[0] = $foodselection;
        //$returndata[1] = $valid;

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
                   
                    if (!($flag = $modelfoodselection->save(false))) {
                        return false;
                    }
                }
            }
        }
        return true;
	}
}