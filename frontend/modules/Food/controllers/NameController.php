<?php

namespace frontend\modules\Food\controllers;

use Yii;
use yii\web\Controller;
use yii\helpers\ArrayHelper;
use common\models\Model;
use frontend\controllers\CommonController;
use common\models\food\{FoodName,FoodSelectionName,FoodSelectiontypeName,Foodselectiontype};

class NameController extends CommonController
{
	public function actionChange($id,$rid)
	{
		CommonController::restaurantPermission($rid);
		$link = CommonController::getRestaurantEditUrl($id,$rid,1);
		$name = $this->createName(FoodName::find()->where('id = :id',[':id'=>$id])->all(),1);
		$type = Foodselectiontype::find()->where('Food_ID = :id',[':id'=>$id])->joinWith(['allName'])->all();
		$arrayData = [];

		if(!empty($type))
		{
			foreach ($type as $key => $data) 
			{
				$sel = SelectionController::allselection($data->ID); 
				if(!empty($sel))
				{
					$arrayData['type'][$key] = $this->createName($data->allName,3);
					$arrayData['selection'][$key] = $sel; 
				}
			}
			//$arrayData['type'] = array_values($arrayData['type']);
			//$arrayData['selection'] = array_values($arrayData['selection']);
		}
		
		if(Yii::$app->request->isPost)
		{

			$isValid = $this->PostChange($arrayData,$name);
			if($isValid)
			{
				Yii::$app->session->setFlash('success',Yii::t('cart','Success!'));
				return $this->redirect(['change','id'=>$id,'rid'=>$rid]);
			}
			else
			{
				 Yii::$app->session->setFlash('error',Yii::t('cart','Something Went Wrong!'));
			}
		}

		return $this->render('change',['name'=>$name,'rid'=>$rid,'fid'=>$id,'arrayData'=>$arrayData,'link'=>$link]);
	}

	public static function PostChange($data,$name)
	{
		$array = self::genName($data,$name);
		$isValid = LargeDataSaveController::trasNameSave($array);
		return $isValid;
	}

	protected static function genName($data,$name)
	{
		$post = Yii::$app->request->post();

		if(!empty($data))
		{
			$selection = $data['selection'];
			$type = $data['type'];
		}
		
		$array = self::detectNullTranName(Yii::$app->request->post('FoodName'),$name);
		$name = $array['data'];
		$postData['FoodName'] = $array['post'];
		Model::loadMultiple($name, $postData);
		$isValid = Model::validateMultiple($name);
	
		if(!empty($data))
		{
			$data = Self::mutipleTypeSelection(1,$type);
			$isValid = $isValid && $data['valid'];
			$typeData = $data['data'];
		
			if($isValid)
			{
				foreach($type as $i => $value)
				{
					$data = Self::mutipleTypeSelection(2,$selection,$i);

					$isValid = $isValid && $data['valid'];
					$selectionData[$i] = $data['data'];

				}
				$return['selection'] = $selectionData;
				$return['type'] = $typeData;

			}
		}
		
		$return['name'] = $name;
		$return['valid'] = $isValid;
		return $return;
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
            $tranArray = self::detectNullTranName($index[$key],$value);
            $value = $tranArray['data'];
            $postData[$arrayData['name']] = $tranArray['post'];
            Model::loadMultiple($value, $postData);
           	$new[$key] = $value;
            $isValid = Model::validateMultiple($value) && $isValid;
        }
       
        $return['valid'] = $isValid;
        $return['data'] = $new;
        return $return;
    }

    public static function detectTypes($type,$i=0,$value)
    {
        $post= Yii::$app->request->post();  
        $data = array();
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

    /*
    * generte Name is language does not present
    * create new one
    */
	public static function createName($allname,$type)
	{
		$language = ArrayHelper::map($allname,'language','id');

		if(empty($language['zh']))
		{
			switch ($type) {
				case 1:
					$new = new FoodName;
					break;
				case 2:
					$new = new FoodSelectionName;
					break;
				case 3:
					$new = new FoodSelectiontypeName;
					break;
				default:
					# code...
					break;
			}
			$new->id = $allname[0]['id'];
			$new->language = 'zh';
			$allname[count($allname)] = $new;
		}

		foreach ($allname as $key => $name) {
			$name->scenario="copy";
		}
		return $allname;
	}

	/*
	* detect whether translate language is empty
	* empty remove
	*/
	protected static function detectNullTranName($post,$data)
	{
		foreach ($post as $key => $value) 
		{
			if($value['language'] != "en")
			{
				if(empty($value['translation']))
				{
					unset($post[$key]);
					unset($data[$key]);
				}	
			}	
		}
		$array['data'] = $data;
		$array['post'] = $post;
		
		return $array;
	}
}