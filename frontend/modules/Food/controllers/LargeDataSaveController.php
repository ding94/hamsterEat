<?php

namespace frontend\modules\Food\controllers;

use Yii;
use yii\web\Controller;
use frontend\controllers\CartController;
use common\models\food\Foodselection;

class LargeDataSaveController extends Controller
{
	public static function selectionSave($array,$id)
	{
		if($array['isvalid'])
		{
			$transaction = Yii::$app->db->beginTransaction();
			try{
				$flag = true;
				foreach($array['type'] as $i=>$type)
				{
					if(!empty($array['sdelete']))
                    {
                        foreach($array['sdelete'] as $value)
                        {
                            Foodselection::updateAll(['Status'=>'-1'],'ID = :id',[':id' => $value]);
                        }
                        
                    }
					$type->Food_ID = $id;
					if($type->save())
					{
						$array['tname'][$i]->id = $type->ID;
						if(!$array['tname'][$i]->save())
						{
							$flag = false;
							break;
						}
					}
					else
					{
						$flag = false;
						break;
					}
					foreach($array['selection'][$i] as $k=>$selection)
					{
						$selection->Type_ID = $type->ID;
						$selection->Food_ID = $id;
						$selection->Price = CartController::actionDisplay2decimal($selection->Price);
						$selection->BeforeMarkedUp = CartController::actionDisplay2decimal($selection->Price/1.3);
						if($selection->save())
						{
							$array['sname'][$i][$k]->id = $selection->ID;
							if(!$array['sname'][$i][$k]->save())
							{
								$flag = false;
								break;
							}
						}
						else
						{
							$flag = false;
							break;
						}
					}
				}

				if($flag)
				{
					$transaction->commit();
	                Yii::$app->session->setFlash('success',Yii::t('food',"Success edited"));
	                return true;
				}
				else
				{
					$transaction->rollBack();
                    Yii::$app->session->setFlash('warning', Yii::t('cart',"Failed to edit!"));
				}
			}
			catch(Exception $e)
            {
            	$transaction->rollBack();
                Yii::$app->session->setFlash('warning', Yii::t('cart',"Failed to edit!"));
            	return false;
               
            }
		}

		Yii::$app->session->setFlash('warning', Yii::t('cart',"Failed to edit!"));
		return false;
	}

	public static function trasNameSave($array)
	{
		$isValid = $array['valid'];

		if($isValid)
		{
			$isValid = true;
			$transaction = Yii::$app->db->beginTransaction();
			try
			{
				foreach($array['name'] as $single)
				{
					if(!$single->save())
					{
						$isValid = false;
						break;
					}
				}
				if(!empty($array['type']))
				{

					foreach($array['type'] as $index=>$data)
					{
						
						foreach($data as $single)
						{
							
							if(!$single->save())
							{
								$isValid = false;
								break;
							}
						}
						foreach($array['selection'][$index] as $k => $data)
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
}