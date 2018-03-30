<?php

namespace frontend\modules\Food\controllers;

use Yii;
use yii\web\Controller;
use yii\helpers\ArrayHelper;
use common\models\problem\{ProblemOrder,ProblemStatus};
use common\models\Order\{Orderitem,Orderitemselection};
use common\models\food\{Foodstatus,Foodselectiontype,Foodselection};
use frontend\controllers\CommonController;
use frontend\modules\Restaurant\controllers\{RestaurantController,CancelController};

class ServiceController extends CommonController
{
	public function actionOnOff($id,$rid)
	{
		$selectiondata = [];
        $model = Foodstatus::find()->where('foodstatus.Food_ID = :id and foodstatus.Status != -1 ',[':id'=>$id])->joinWith(['selection'])->one();
      
        if(empty($model))
        {
            Yii::$app->session->setFlash('warning', Yii::t('m-restaurant',"Food Already Deleted"));
            return $this->redirect(['/Food/default/menu','rid'=>$rid,'page'=>'menu']); 
        }
        foreach($model->selection as $selection)
        {
            if($selection->Status != -1)
            {
                $food = Foodselectiontype::findOne($selection->Type_ID);
                $selectiondata[$food->originName][] = $selection;
            }
        }
        
        return $this->render("onoff",['model'=>$model,'rid'=>$rid,'selectiondata'=>$selectiondata]);
	}

	public function actionActive($id,$rid,$type)
	{
		if($type == 1)
		{
			$model = Foodstatus::find()->where('Food_ID=:id',[':id'=>$id])->one();
		}
		else
		{
			$model = Foodselection::findOne($id);
		}
		
		if(!empty($model))
		{
			CommonController::restaurantPermission($rid);
			$restaurant = RestaurantController::findModel($rid);
			if ($restaurant['Restaurant_Status'] == 3) {
	            Yii::$app->session->setFlash('error', Yii::t('m-restaurant',"Restaurant was not opening."));
	            return $this->redirect(Yii::$app->request->referrer);
	        }
	        $model->Status = 1;
	        if($model->save())
	        {
	            Yii::$app->session->setFlash('success', Yii::t('m-restaurant',"Status change to operating."));
	        }
	        else
	        {
	        	Yii::$app->session->setFlash('warning', Yii::t('m-restaurant',"Change status failed."));
	        }
		}
		
        else
        {
            Yii::$app->session->setFlash('warning', Yii::t('m-restaurant',"Change status failed."));
        }
        return $this->redirect(Yii::$app->request->referrer); 
		
	}

	public function actionProvidereason($id,$rid,$item)
	{
		$reason = new ProblemOrder;
        $list = ArrayHelper::map(ProblemStatus::find()->all(),'id','description');

        if (Yii::$app->request->post()) {

            if($item == 3)
            {
                $true = SelectionController::enableOff($id);
                $valid = $true;
                if($true)
                {
                    $valid = self::CancelSelection($id);
                }
                
            }
            else
            {
                $valid = CancelController::CancelOrder($id);
            }
            
            if ($valid == true) {
                RestaurantController::actionDeactive($id,$item);
                Yii::$app->session->setFlash('warning', Yii::t('m-restaurant',"Status changed! Please inform customer service."));
                return $this->redirect(Yii::$app->request->referrer); 
            }
            else
            {
                 return $this->redirect(Yii::$app->request->referrer); 
            }
        }
            
        return $this->renderAjax('reason',['reason'=>$reason,'list'=>$list]);
	}

	protected static function CancelSelection($id)
    {
        $post = Yii::$app->request->post();
        if(empty($post['ProblemOrder']))
        {
            return false;
        }

        $itemselection = Orderitemselection::find()->where('Selection_ID = :id and OrderItem_Status = 2',[':id'=>$id])->joinWith(['item'])->all();
       
        if(empty($itemselection))
        {
            return true;
        }
      
        foreach($itemselection as $selection)
        {
            $did = $selection->item->Delivery_ID;
            //$did = $itemselection[5]->item->Delivery_ID;
           
            $allitem = Orderitem::find()->where('Delivery_ID = :id',[':id'=>$did])->all();

            $count = count($allitem);

            if($count <= 1)
            {
                $isvalid = CancelController::deliveryCancel($selection->item);
               
                if(!$isvalid)
                {
                    break;
                }
            }
            else
            {
                //self::selectionCancel($selection->item);
                $isvalid=  CancelController::orderCancel($selection->item);
                if(!isvalid)
                {
                    break;
                }
            }
            
        }
        
        return $isvalid;
    }
}