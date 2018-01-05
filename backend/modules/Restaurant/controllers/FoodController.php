<?php

namespace backend\modules\Restaurant\controllers;

use Yii;
use yii\web\Controller;
use yii\helpers\ArrayHelper;
use backend\models\FoodSearch;
use common\models\food\Foodstatus;
use common\models\food\Foodselection;
use common\models\food\Foodtype;
use common\models\Order\Orderitem;
use common\models\Order\Orders;
 

Class FoodController extends Controller
{
	public function actionIndex($id)
	{
		$searchModel = new FoodSearch();
    	$dataProvider = $searchModel->search(Yii::$app->request->queryParams,$id);

    	if($id == 0)
    	{
    		$searchModel->restaurant = "All Food";
    	}
    	else
    	{
    		$searchModel->restaurant = "Food Detail";
    	}
    	
    	$typeList = ArrayHelper::map(Foodtype::find()->all(),'Type_Desc','Type_Desc');

		return $this->render('index',['model' => $dataProvider , 'searchModel' => $searchModel,'typeList' => $typeList]);
	}

	public function actionFoodControl($id,$status)
	{
		$model = Foodstatus::find()->where('Food_ID = :id',[':id' => $id])->one();
		$isvalid = true;
		if($status == 0)
		{
			$isvalid = self::CancelOrder($id);
		}

		$model->Status = $status;
		if($isvalid && $isvalid)
		{
			$model->save();
			Yii::$app->session->setFlash('success', "Food Change completed");
		}
		else
		{
			Yii::$app->session->setFlash('warning', "Food Change Fail");
		}
		return $this->redirect(Yii::$app->request->referrer);
	}

	public function actionTypeControl($id,$status)
	{
		$model = Foodselection::find()->where('ID = :id',[':id' => $id])->one();
		$model->Status = $status;
		if($model->save())
		{
			Yii::$app->session->setFlash('success', "Type Change completed");
		}
		else
		{
			Yii::$app->session->setFlash('warning', "Type Change Fail");
		}
		return $this->redirect(Yii::$app->request->referrer);
	}

	protected function CancelOrder($id)
    {
        $orderitem = Orderitem::find()->where('Food_ID=:id AND OrderItem_Status=:s',[':id'=>$id, ':s'=>2])->all();

        if (!empty($orderitem)) 
        {
            foreach ($orderitem as $k => $value) 
            {
                $order = Orders::find()->where('Delivery_ID=:id',[':id'=>$value['Delivery_ID']])->one();
                if ($order['Orders_DateTimeMade'] > strtotime(date('Y-m-d'))) 
                {
                    /*$reason = new ProblemOrder; // set new value to db, away from covering value
                    $reason['reason'] = 3;
                    $reason->load(Yii::$app->request->post());
                    $reason['Order_ID'] = $value['Order_ID'];
                    $reason['Delivery_ID'] = $value['Delivery_ID'];
                    $reason['status'] = 1;
                    $reason['datetime'] = time();*/
                    $value['OrderItem_Status'] = 8;
                    $order['Orders_Status'] = 8;

                    //check did user use balance to pay
                    if ($order['Orders_PaymentMethod'] == 'Account Balance') {
                        //$reason['refund'] = $order['Orders_TotalPrice'];
                        $acc = Accountbalance::find()->where('User_Username=:us',[':us'=>$order['User_Username']])->one();
                        $acc['User_Balance'] += $order['Orders_TotalPrice'];
                        $acc['AB_minus'] -= $order['Orders_TotalPrice'];
                        if ($acc->validate()) {
                                $acc->save();
                                $order['Orders_Status'] = 9;
                                $value['OrderItem_Status'] = 9;
                        }
                        else
                        {
                        	break;
                        	return false;
                        }
                    }
                    if ($value->validate() && $order->validate()) {
                        //$reason->save();
                        $value->save();
                        $order->save();
                    }
                    else
                    {
                    	break;
                    	return false;
                    }
                }
            }
            //use this formular for most accurate data protect
            //if (count($orderitem) == ($k+1) ) {}
        }
        return true;
    }
}