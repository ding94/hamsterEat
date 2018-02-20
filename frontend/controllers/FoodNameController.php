<?php
namespace frontend\controllers;

use Yii;
use yii\web\Controller;
use yii\helpers\ArrayHelper;
use yii\base\Model;
use common\models\food\FoodName;
use common\models\food\FoodSelectiontypeName;
use common\models\food\FoodSelectionName;
use common\models\food\Foodselectiontype;
use frontend\controllers\CommonController;
use frontend\modules\Restaurant\controllers\FoodselectionController;

class FoodNameController extends CommonController
{
	public function actionChange($rid,$fid)
	{
		$name = $this->createName(FoodName::find()->where('id = :id',[':id'=>$fid])->all(),1);
		$type = Foodselectiontype::find()->where('Food_ID = :id',[':id'=>$fid])->joinWith(['allName'])->all();
		$arrayData = [];

		if(!empty($type))
		{
			foreach ($type as $key => $data) 
			{
				$arrayData['type'][$key] = $this->createName($data->allName,3);
				$arrayData['selection'][$key] = FoodselectionController::allselection($data->ID);
			}
		}

		if(Yii::$app->request->isPost)
		{
			$isValid = $this->PostChange($arrayData,$name);
		}

		return $this->render('change',['name'=>$name,'arrayData'=>$arrayData]);
	}

	public static function PostChange($data,$name)
	{
		$selection = $data['selection'];
		$type = $data['type'];

		Model::loadMultiple($name, Yii::$app->request->post());
		$isValid = Model::validateMultiple($name);

		if($isValid)
		{
			//$data = FoodselectionController::mutipleTypeSelection(1,$type);
			//$isValid = $isValid && $data['valid'];
			//$selectionData = $data['data'];

			foreach($type as $i => $value)
			{
				$data = FoodselectionController::mutipleTypeSelection(2,$selection,$i);
				var_dump($data);exit;
			}
			var_dump($selectionData);exit;
			foreach ($post['FoodSelectiontypeName'] as $i => $postType) 
			{

				$postData['FoodSelectiontypeName'] = $postType;
				Model::loadMultiple($type[$i], $postData);
				$isValid = Model::validateMultiple($type[$i])&& $isValid;
				if(!$isValid)
				{
					break;
				}
				foreach($post['FoodSelectionName'][$i] as $k => $postSelection)
				{
					$postData['FoodSelectionName'] = $postSelection;
					Model::loadMultiple($selection[$i][$k],$postData);
					$isValid = Model::validateMultiple($selection[$i][$k]) && $isValid;
					var_dump($isValid);exit;
				}
			}
		}
		return $isValid;
		
		var_dump($selection[0][0]);exit;
	}

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
}