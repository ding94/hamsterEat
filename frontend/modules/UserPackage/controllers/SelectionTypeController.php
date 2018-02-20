<?php

namespace frontend\modules\UserPackage\controllers;

use yii\web\Controller;
use Yii;
use common\models\Package\UserPackageSelectionType;
use common\models\food\Foodselectiontype;

class SelectionTypeController extends Controller
{
	/*
	* use for detect selection min max
	* remove empty array and set count become 0
	* data[value] 
	* 1=>true
	* 2=>false
	*/
	public static function detectMinMaxSelecttion($selection,$foodselection)
	{
		$data = [];
		$data['value'] = 1;
		$data['message'] = "";
		$selection = array_filter($selection);

		foreach ($foodselection as $key => $value) {

			if(empty($selection[$value->ID]))
			{
				$count = 0;
			}
			else
			{
				$count = count($selection[$value->ID]);
			}
			
			if($count< $value->Min || $count > $value->Max)
			{
				//Yii::$app->session->setFlash('danger', 'Please select at least '.$value->Min.' items and most '.$value->Max.' items in '.$value->TypeName);
				
				$data['message'] = "Please select at least $value->Min items and most $value->Max items in $value->cookieName";
				$data['value'] =2;
				return $data;
			}
			
		}
		return $data;
	}

	/*
	* loop all selection data
	*/
	public static function newSelection($selection,$pid)
	{
		$returnData =  [];

		foreach($selection as $select)
		{
			$model = new UserPackageSelectionType;
			$model->packagedid = $pid;
			$model->selectionitypeId = $select['selectionitypeId'];
		
			if(!$model->save())
			{
				return false;
			}

		}
		return true;
	}
}