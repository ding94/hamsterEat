<?php

namespace frontend\modules\Food\controllers;

use Yii;
use yii\web\Controller;
use common\models\Model;
use yii\helpers\ArrayHelper;
use frontend\controllers\{CommonController,CartController};
use common\models\food\{Food,FoodSelectionName,FoodSelectiontypeName,Foodselectiontype,Foodselection};


class SelectionController extends CommonController
{
	public function actionCreateEdit($id,$rid,$status = 0)
	{
		CommonController::restaurantPermission($rid);
        if($status == 1)
        {
            var_dump('a');exit;
        }
		$type = Foodselectiontype::find()->where('Food_ID = :id',[':id'=>$id])->joinWith(['transName'])->all();
		$food = Food::findOne($id);
		$new = empty($food) ? 0 :1;
		$link = CommonController::getRestaurantEditUrl($id,$rid,$new);
		if (!empty($type)) 
        {
            $selection = $this->oldData($type,1);
            foreach ($selection as $key => $value) {
                if(empty($value))
                {
                    unset($type[$key]);
                    unset($selection[$key]);
                }
               	else
               	{
               		$typeName[$key] = $type[$key]->transName;
               		foreach ($value as $i => $data) {
               			$selectionName[$key][$i] =$data->transName;            		
               		}
               	}
            }
           
        }
        else
        {
        	$type = [new Foodselectiontype];
        	$selection = [[new Foodselection]];
        	$typeName = [new FoodSelectiontypeName];
        	$selectionName = [[new FoodSelectionName]];
        }
        $type = array_values($type);
        $typeName = array_values($typeName);
        $selection = array_values($selection);
       	$selectionName = array_values($selectionName);

        if(Yii::$app->request->post())
        {

        	$array['type'] = $type;
        	$array['selection'] = $selection;
        	$array['typeName'] = $typeName;
        	$array['selectionName'] = $selectionName;
        	$valid = $this->saveData($array,$id);
        	if($valid)
        	{
        		return $this->redirect(['create-edit','id'=>$id,'rid'=>$rid]);
        	}
        	
        }
		
		return $this->render('crselection',[
			'type' => $type,
			'selection' => $selection,
			'typeName' => $typeName,
			'selectionName' => $selectionName,
			'id'=>$id,
			'rid'=>$rid,
			'link'=>$link,
		]);
	}

	protected static function saveData($data,$id)
	{
		$array = self::centerControlData($data,$id);
		$valid = LargeDataSaveController::selectionSave($array,$id);
		return $valid;
	}

	protected static function centerControlData($data,$id)
	{
		$post = Yii::$app->request->post();
		$type = $data['type'];
		$selection = $data['selection'];
		$typeName = $data['typeName'];
		$selectionName = $data['selectionName'];

		$true = TypeAndStatusController::detectMinMax($post['Foodselectiontype'],$post['Foodselection']);
        if($true)
        {
            return $array['isvalid'] = false;
        }
       	
       	$arrayType = TypeAndStatusController::genType($type,$typeName);

       	$arraySelection = self::genSelection($selection,$selectionName,$type);
       	$isvalid = $arrayType['isvalid'] && $arraySelection['isvalid'];
       	$array = array_merge($arrayType,$arraySelection);
       	$array['isvalid'] = $isvalid;
       	return $array;
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

    /*
    * using model to load post data
    * compire post data with database data
    */
    public static function genSelection($selection,$name,$type)
    {
    	$post = Yii::$app->request->post();
    	$old = [];
    	$isvalid = true;
  	
    	foreach ($post['Foodselection'] as $i => $select) 
    	{
    		$old = ArrayHelper::merge($old, array_filter(ArrayHelper::getColumn($select, 'ID')));
    		$arrayData['Foodselection'] = $select;
    		$arrayData['FoodSelectionName'] = $post['FoodSelectionName'][$i];

    		if(empty($selection[$i]))
    		{
    			$selection[$i][] = new Foodselection;
    			$name[$i][] = new FoodSelectionName;
    		}
  			$newSel[$i] = Model::createMultiple(Foodselection::classname(), $selection[$i] ,"ID",$i);
       	
       		$newName[$i] = Model::createMultiple(FoodSelectionName::classname(), $name[$i] ,"id",$i);

        	Model::loadMultiple($newSel[$i],$arrayData);

        	Model::loadMultiple($newName[$i],$arrayData);
    		$isvalid = Model::validateMultiple($newName[$i]) && Model::validateMultiple($newSel[$i]) && $isvalid;
    	}
    	
    	if (!empty($type)) 
        {
            $oldSelect = self::oldData($type,2);
            $oldSelect = ArrayHelper::getColumn($oldSelect,'ID');
    		$deleted = array_diff($oldSelect, $old);
    		if(!empty($deleted))
    		{
    			$array['sdelete'] = $deleted;
    		}
        }
    	
    	$array['isvalid'] = $isvalid;
    	$array['selection'] = $newSel;
    	$array['sname'] = $newName;
    
    	return $array;
    }

    public static function allSelection($id)
    {
        $selection =[];
        $query = Foodselection::find()->where('Type_ID = :tid and Status != -1',['tid'=>$id])->joinWith(['allName']);
     
        foreach ($query->each() as $key => $value) 
        { 
            $selection[$key] = NameController::createName($value->allName,2);
        }
        
        return $selection;
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

