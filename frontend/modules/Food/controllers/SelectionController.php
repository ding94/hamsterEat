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

		$type = Foodselectiontype::find()->where('Food_ID = :id',[':id'=>$id])->joinWith(['transName'])->all();
		$food = Food::findOne($id);
		$new = empty($food) ? 0 :1;
		$link = CommonController::getRestaurantEditUrl($id,$rid,$new);
        $array = $this->genPassData($type);
		
        $type = $array['type'];
        $typeName = $array['typeName'];
        $selection = $array['selection'];
       	$selectionName = $array['selectionName'];

        if(Yii::$app->request->post())
        {
        	$valid = $this->saveData($array,$id);
        	if($valid)
        	{
                if($status == 1)
                {
                    return $this->redirect(['/Food/image/create','id'=>$id,'rid'=>$rid]);
                }
                else
                {
                    return $this->redirect(['create-edit','id'=>$id,'rid'=>$rid]);
                }
        		
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
            'status'=>$status,
		]);
	}

    /*
    * detect the type and selection is new record
    * and remove the type base on deleted selection
    */
    protected static function genPassData($type)
    {
        if (!empty($type)) 
        {
            $selection = self::oldData($type,1);
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

        if(empty($selection))
        {
            $type = [new Foodselectiontype];
            $selection = [[new Foodselection]];
            $typeName = [new FoodSelectiontypeName];
            $selectionName = [[new FoodSelectionName]];
        }

        $array['type'] = array_values($type);
        $array['selection'] = array_values($selection);
        $array['typeName'] = array_values($typeName);
        $array['selectionName'] = array_values($selectionName);
        return $array;
    }

	protected static function saveData($data,$id)
	{      
        $post = Yii::$app->request->post();
        if(empty($post['Foodselectiontype']) && empty($post['Foodselection']))
        {
           $valid = Foodselection::updateAll(['Status'=> -1],'Food_ID = :id',[':id'=>$id]);
        }
        else
        {
            $array = self::centerControlData($data,$id);
            $valid = LargeDataSaveController::selectionSave($array,$id);
        }
		
		return $valid;
	}

    /*
    * validate type,selection,typeName and selectionName data
    */
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
        $sorting = self::sortingSelection($selection,$name);
        $selection = $sorting['sel'];
        $name = $sorting['name'];
        
    	foreach ($post['Foodselection'] as $i => $select) 
    	{
    		$old = ArrayHelper::merge($old, array_filter(ArrayHelper::getColumn($select, 'ID')));

            foreach($select as $k =>$value)
            {
                $data['Foodselection'] = $value;
                $data['FoodSelectionName'] = $post['FoodSelectionName'][$i][$k];
               
                if($value['ID']== "")
                {
                    $tempSel = new Foodselection;
                    $tempName = new FoodSelectionName;
                }
                else
                {
                    $tempSel = $selection[$value["ID"]];
                    $tempName = $name[$value["ID"]];
                }
                $tempSel->load($data);
                $tempName->load($data);
                $isvalid = $tempSel->validate() && $tempName->validate() && $isvalid;
                $newSel[$i][$k] = $tempSel;
                $newName[$i][$k] = $tempName;
             
            }
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

    protected static function sortingSelection($selection,$name)
    {
        $new = array();
        $newSel = array();
        $newName = array();
        foreach($selection as $i => $sel)
        {
            foreach($sel as $k=>$value)
            {
                $newSel[$value->ID] = $value;
                $newName[$name[$i][$k]->id] = $name[$i][$k];
            }
        }
        $new['sel'] = $newSel;
        $new['name'] = $newName;
        return $new;
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

