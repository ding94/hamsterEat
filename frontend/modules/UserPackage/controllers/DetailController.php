<?php

namespace frontend\modules\UserPackage\controllers;

use yii\web\Controller;
use Yii;
use yii\helpers\ArrayHelper;
use common\models\Package\UserPackageDetail;
use common\models\food\Food;

class DetailController extends Controller
{
	/*
	* create package detail format for passing data in  view
	* $post include quantity and fid
	* fid  => food id
	* quantity  => food quantity
	* food => food with filter
	*/
	public static function newPackageDetail($fid,$quantity,$food)
	{
		$data =[];
		$data['fid'] = $fid;
		$data['quantity'] = $quantity;
		$data['totalPrice'] = self::totalPrice($food,$fid) * $quantity;
		
		return $data;
	}

	/*
	* create new package detail and save
	* post => data from function newPackageDetail
	* pid => $food pacakge id
	*/
	public static function createDetail($post,$pid)
	{
		$data = new UserPackageDetail;
		$data->load($post);
		$data->pid = $pid;
		if($data->save())
		{
			return true;
		}
		else
		{
			return false;
		}
	}

	/*
	* calcaulate total price for food price and food selection price
	* empty food selection return food price only
	* else calucalte all selection price
	*/
	public static function totalPrice($food,$fid)
	{
		
		$finalPrice = 0;
		//$food = Food::find()->where('food.Food_ID = :id',[':id' => $fid])->joinWith(['foodSelection'])->one();
		$finalPrice += $food['Price'];


		if(empty($food['foodSelection']))
		{
			return $finalPrice;
		}
		
		foreach($food['foodSelection'] as $selected)
		{

			$finalPrice += $selected['Price'];
		}

		return $finalPrice;
	}

	public static function filterItem($item,$itemSelection)
	{
		$newItem = [];
		if(empty($item))
		{
			return "";
		}

		foreach($item as $i=> $selection)
		{
			foreach ($itemSelection as $k => $selected) 
			{
				if($selected == $selection['ID'])
				{
					$newItem[] = $selection;
				}
			}
		}
		return $newItem;
	}
}