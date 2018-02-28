<?php

namespace backend\modules\Restaurant\controllers;

use Yii;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\data\ActiveDataProvider;
use common\models\food\Foodselection;
use common\models\food\Foodselectiontype;
use common\models\food\FoodSelectionName;
use common\models\food\FoodSelectiontypeName;
use common\models\Order\Orderitemselection;
use common\models\Order\Orderitem;
use backend\controllers\CommonController;
use backend\modules\Restaurant\controllers\FoodController;

class TypeController extends CommonController
{
	public function actionIndex($id)
	{
		$query = Foodselection::find()->where('foodselection.Food_ID = :id',[':id'=>$id])->joinWith(['selectedtpye']);
        $dataProvider = new ActiveDataProvider([
                'query' => $query,
        ]);
       return $this->render('index',['dataProvider'=>$dataProvider]);
	}

	public function actionUpdateType($id)
	{
		$allname = FoodController::createName($this->findName($id,2),2);
		$type = Foodselectiontype::findOne($id);
		if(Yii::$app->request->isPost)
		{
			$isvalid = FoodController::saveData($type,$allname,1);
			if($isvalid)
			{
				Yii::$app->session->setFlash('success', "Food Selection Change completed");
				return $this->redirect(['index','id'=>$type->Food_ID]);
			}
		}
		return $this->render('updatetype',['allname'=>$allname,'type'=>$type]);
	}

	public function actionUpdate($id)
	{
		$selection = $this->findSelection($id);
		$allname = FoodController::createName($this->findName($selection->ID,1),1);
		if(Yii::$app->request->isPost)
		{
			
			$isvalid =  FoodController::saveData($selection,$allname);
			if($isvalid)
			{
				Yii::$app->session->setFlash('success', "Food Selection Change completed");
				return $this->redirect(['index','id'=>$selection->Food_ID]);
			}
		}
		return $this->render('update',['selection'=>$selection,'allname'=>$allname]);
	}

	public function actionControl($id,$status)
	{
		$model = $this->findSelection($id);

		$isvalid = true;

		if($status == 0)
		{
			$isvalid = self::CancelSelection($id);
		}
	
		$model->Status = $status;
		if($isvalid && $model->validate())
		{
			$model->save();
			Yii::$app->session->setFlash('success', "Food Selection Change completed");
		}
		else
		{
			Yii::$app->session->setFlash('warning', "Food Selection Change Fail");
		}
		return $this->redirect(Yii::$app->request->referrer);
		
	}

	public function actionRecover($id)
	{
		$data = $this->findSelection($id);
		if($data->Status == -1)
		{
			$data->Status = 1;
			if($data->save())
			{
				Yii::$app->session->setFlash('success', "Food Selection Data Recover Back");

			}
			else
			{
				Yii::$app->session->setFlash('warning', "Fail");
			}
		}
		else
		{
			Yii::$app->session->setFlash('warning', "Fail");
		}
		return $this->redirect(Yii::$app->request->referrer);
	}

	public static function CancelSelection($id)
	{
		$items = Orderitemselection::find()->where('Selection_ID = :id and OrderItem_Status = 2',[':id'=>$id])->joinWith(['item'])->all();
	
		if(empty($items))
		{
			return true;
		}

		foreach($items as $item)
		{
			$did = $item->item->Delivery_ID;
			$allorder = Orderitem::find()->where('Delivery_ID = :did',[':did'=>$did])->all();

			if(count($allorder) <= 1)
			{
				$isvalid = CancelController::deliveryCancel($item->item);
			}
			else
			{
				$isvalid=  CancelController::orderCancel($item->item);
			}
			
		}
		return $isvalid;
	}

	public static function detectMinMax($data)
	{
		$count = Foodselection::find()->where('Food_ID = :id',[':id'=>$data->Food_ID])->count();

		if($data->Min > $count || $data->Min > $data->Max)
		{
			return false;
		}
		else
		{
			return true;
		}
	}

	public static function findSelection($id)
	{

        if (($model = Foodselection::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
	}

	public static function findName($id,$type)
	{
		switch ($type) {
			case 1:
				$query =  FoodSelectionName::find();
				break;
			case 2:
				$query = FoodSelectiontypeName::find();
				break;
			default:
				# code...
				break;
		}
		$query->where('id = :id',[':id'=>$id]);
		if(empty($query->all()))
		{
			throw new NotFoundHttpException('The requested page does not exist.');
		}
		else
		{
			return $query->all();
		}
	}
}