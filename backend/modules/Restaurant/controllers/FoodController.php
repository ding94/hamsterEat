<?php

namespace backend\modules\Restaurant\controllers;

use Yii;
use yii\web\Controller;
use yii\helpers\Json;
use yii\helpers\ArrayHelper;
use backend\models\FoodSearch;
use backend\controllers\CommonController;
use common\models\food\Food;
use common\models\food\Foodstatus;
use common\models\food\Foodselection;
use common\models\food\Foodtype;
use common\models\Order\Orderitem;
use common\models\Order\Orders;
use common\models\Profit\RestaurantItemProfit;

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

    public function actionFoodRankingPerMonth($month=0)
    {
        if($month == 0){
            $month = date('Y-n',strtotime('this year'));
        }
        $explodemonth = explode("-",$month);
        $food = Food::find()->asArray()->all();

        $months = CommonController::getYear($explodemonth[0]);
        $startend = CommonController::getStartEnd($explodemonth[0]);
        foreach ($startend as $i => $date) {
            foreach ($food as $key => $value) {
                $food_array = ['id'=>(int)$value['Food_ID'],'name'=>$value['Name']];
                $json_food = Json::encode($food_array);
                 
                $model = RestaurantItemProfit::find()->where('fid = :fid',[':fid'=>$json_food])->andWhere(['between','created_at',$date[0],$date[1]])->asArray()->all();
                $modelcount = 0;
                if(empty($model)){
                    $modelcount = 0;
                    $data[$i][$value['Name']] = $modelcount;
                } else {
                    foreach ($model as $k => $v) {
                        $modelcount+= $v['quantity'];
                    }
                    $data[$i][$value['Name']] = $modelcount;
                }
            }
            arsort($data[$i]);
        }
        $data = $data[$explodemonth[1]];
        if (count($data) > 10 ){
            $data = array_slice($data,0,10);
        }
        $num = 0;
        foreach ($data as $count => $rank) {
            $num+=1;
            $newcount = '#'.$num.' '.$count;
            $data[$newcount] = $data[$count];
            unset($data[$count]);
        }
        foreach ($data as $key => $value) {
            $data['fname'][] = $key;
            $data['count'][] = $value;
        }
        $textmonth = date('F Y',strtotime($month));
        return $this->render('ranking',['month'=>$month,'textmonth'=>$textmonth,'data'=>$data]);
    }

    public function actionFoodRankingPerRestaurantPerMonth($month=0,$rid)
    {
        if($month == 0){
            $month = date('Y-n',strtotime('this year'));
        }
        $explodemonth = explode("-",$month);
        $food = Food::find()->where('Restaurant_ID=:rid',[':rid'=>$rid])->asArray()->all();
        $months = CommonController::getYear($explodemonth[0]);
        $startend = CommonController::getStartEnd($explodemonth[0]);
        foreach ($startend as $i => $date) {
            if(!empty($food)){
                foreach ($food as $key => $value) {
                    $food_array = ['id'=>(int)$value['Food_ID'],'name'=>$value['Name']];
                    $json_food = json_encode($food_array);
                    $model = RestaurantItemProfit::find()->where('fid = :fid',[':fid'=>$json_food])->andWhere(['between','created_at',$date[0],$date[1]])->asArray()->all();
                    $modelcount = 0;
                    if(empty($model)){
                        $modelcount = 0;
                        $data[$i][$value['Name']] = $modelcount;
                    } else {
                        foreach ($model as $k => $v) {
                            $modelcount+= $v['quantity'];
                        }
                        $data[$i][$value['Name']] = $modelcount;
                    }
                }
                arsort($data[$i]); 
            } else {
                $data[$i] = [] ;
            }
        }
        $data = $data[$explodemonth[1]];
        if(!empty($data)){
            if (count($data) > 10 ){
                $data = array_slice($data,0,10);
            }
        }
        if(!empty($data)){
            foreach ($data as $key => $value) {
                $data['fname'][] = $key;
                $data['count'][] = $value;
            }
        } else {
            $data['fname'] = [];
            $data['count'] = [];
        }
        $textmonth = date('F Y',strtotime($month));
        return $this->render('ranking',['month'=>$month,'textmonth'=>$textmonth,'data'=>$data]);
    }
}

