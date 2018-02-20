<?php

namespace frontend\modules\Restaurant\controllers;

use yii;
use yii\web\Controller;
use yii\helpers\ArrayHelper;
use yii\base\Model;
use frontend\controllers\CartController;
use frontend\controllers\FoodNameController;
use common\models\food\Foodselection;
use common\models\food\FoodSelectionName;
use common\models\food\Foodselectiontype;
use common\models\food\Food;

class FoodselectionController extends Controller
{
	public static function validatefoodselection()
	{
        $post = Yii::$app->request->post();
     
        $valid = true;
		foreach ($post['Foodselection'] as $i => $foodtypes) {
            foreach ($foodtypes as $ix => $foodselections) {
                $sel['Foodselection'] = $foodselections;
                $modelfoodselection = new Foodselection;

                $modelfoodselection->load($sel);

                $selection[$i][$ix] = $modelfoodselection;
                $valid = $modelfoodselection->validate() && $valid;
            }
        }

        $data['valid'] = $valid;
        $data['data'] =$selection ;  
           
        return $data;
	}

	public static function createfoodselection($foodtype,$foodselection,$id)
	{
        
		foreach ($foodtype as $i => $modelfoodtype) {

            $modelfoodtype->Food_ID = $id;
                                
            if (!($flag = $modelfoodtype->save())) {
                                    
                return false;
            }

            if (isset($foodselection[$i]) && is_array($foodselection[$i])) 
            {
                foreach ($foodselection[$i] as $ix => $modelfoodselection) {
                    $modelfoodselection->Type_ID = $modelfoodtype->ID;
                    $modelfoodselection->Food_ID = $id;
                    $modelfoodselection->Name = "aaa";
                    $modelfoodselection->Price = CartController::actionDisplay2decimal($modelfoodselection->Price);
                    $modelfoodselection->BeforeMarkedUp =  CartController::actionRoundoff1decimal($modelfoodselection->Price / 1.3);;
                   
                    if (!($flag = $modelfoodselection->save())) {
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
            $foodSelection = Foodselection::find()->where('Type_ID = :tid and Status != -1',[':tid'=>$select->ID])->joinWith(['transName'])->all();
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

    public static function allSelection($id)
    {
        $query = Foodselection::find()->where('Type_ID = :tid',['tid'=>$id])->joinWith(['allName']);
        foreach ($query->each() as $key => $value) 
        {
            $selection[] = FoodNameController::createName($value->allName,2);
        }
       
        return $selection;
    }

    public static function mutipleTypeSelection($type,$data,$i=0)
    {
        $post= Yii::$app->request->post();
        $arrayData = self::detectTypes($type,$i,$data);
        $name = $arrayData['name'];
        $index = $arrayData['index'];
        $convertData = $arrayData['data'];
       
        $isValid = true;
       
        foreach ($convertData as $key => $value) 
        {
            $postData[$name] = $index[$key];
           
            Model::loadMultiple($value, $postData);
            
            $isValid = Model::validateMultiple($value) && $isValid;
        }
        
        $return['valid'] = $isValid;
        $return['data'] = $data;
        return $return;
    }

    public static function detectTypes($type,$i=0,$value)
    {
        $post= Yii::$app->request->post();  
        $data="";
        switch ($type) {
            case 1:
                $name ="FoodSelectiontypeName";
                $index = $post[$name];
                $value = $value;
                break;
            case 2:
                $name ="FoodSelectionName";
                $index = $post[$name][$i];
                $value = $value[$i];
                break;
            default:
                # code...
                break;
        }

        $data['name']=$name;
        $data['index'] = $index;
        $data['data'] = $value;
        return $data;
    }
}
