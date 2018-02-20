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
			if($isValid)
			{
				Yii::$app->session->setFlash('success',Yii::t('cart','Success!'));
			}
			else
			{
				 Yii::$app->session->setFlash('error',Yii::t('cart','Something Went Wrong!'));
			}
		}

		return $this->render('change',['name'=>$name,'rid'=>$rid,'fid'=>$fid,'arrayData'=>$arrayData]);
	}

	public static function PostChange($data,$name)
	{
		if(!empty($data))
		{
			$selection = $data['selection'];
			$type = $data['type'];
		}
		$post= Yii::$app->request->post();  
		Model::loadMultiple($name, Yii::$app->request->post());
		$isValid = Model::validateMultiple($name);

		if(!empty($data))
		{
			$data = FoodselectionController::mutipleTypeSelection(1,$type);
			$isValid = $isValid && $data['valid'];
			$typeData = $data['data'];
				
			if($isValid)
			{
				foreach($type as $i => $value)
				{
					$data = FoodselectionController::mutipleTypeSelection(2,$selection,$i);
					$isValid = $isValid && $data['valid'];
					$selectionData = $data['data'];
				}
			}
		}

		if($isValid)
		{
			$transaction = Yii::$app->db->beginTransaction();
			try
			{
				foreach($name as $single)
				{
					if(!$single->save())
					{
						$isValid = false;
						break;
					}
				}
				if(!empty($typeData))
				{
					foreach($typeData as $index=>$data)
					{
						foreach($data as $single)
						{
							if(!$single->save())
							{
								$isValid = false;
								break;
							}
						}
						foreach($selectionData[$index] as $k => $data)
						{
							foreach($data as $single)
							{
								if(!$single->save())
								{
									$isValid = false;
									break;
								}
							}
						}
							
					}
				}

				if($isValid)
				{
					$transaction->commit();
				}
				else
				{
					$transaction->rollBack();
				}
			}
			catch (Exception $e) {
				$transaction->rollBack();
			}
				
		}
		

		return $isValid;
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