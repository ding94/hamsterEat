<?php

namespace backend\modules\Restaurant\controllers;

use Yii;
use yii\web\Controller;
use yii\helpers\Json;
use yii\helpers\ArrayHelper;
use yii\data\ActiveDataProvider;
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
		if($isvalid && $model->validate())
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
                $isvalid = CancelController::OrderorDeliveryCancel($value->Delivery_ID,$value);
                if(!$isvalid)
                {
                    break;
                    return false;
                }
            }
        }
        Yii::$app->session->setFlash('Success', "Food Status changed!");
        return true;
    }

    public function actionFoodSold($first = 0,$last = 0,$fid)
    {
        if($first == 0 && $last == 0)
        {
            $first = date("Y-m-d", strtotime("first day of this month"));
            $last = date("Y-m-d", strtotime("+1 days")); 
        }

        $days= CommonController::getMonth($first,$last,1);

        $food = Food::find()->where('Food_ID=:fid',[':fid'=>$fid])->one();

        $food_array = ['id'=>(int)$food['Food_ID'],'name'=>$food['transName']];
        $json_food = Json::encode($food_array);

        $model = RestaurantItemProfit::find()->where('fid = :fid',[':fid'=>$json_food])->andWhere(['between','created_at',strtotime($first),strtotime($last)])->select(['created_at','quantity'])->asArray()->all();
        $modelcount = 0;
        $count = CommonController::getMonth($first,$last,2);
        if(!empty($model))
        {
            foreach ($model as $key => $value) {
                $modelcount+=$value['quantity'];
                $date = date("Y-m-d", $value['created_at']);
                $count[$date] += (int)$value['quantity'];
            } 
        }
        $data['date'] = $days;
        $data['count'] = CommonController::convertToArray($count);
        $data['totalcount'] = $modelcount;

        return $this->render('foodsold',['data'=>$data,'first'=>$first,'last'=>$last]);
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
            $data[$i] = self::countFoodSold($food,$date[0],$date[1]);
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
                $data[$i] = self::countFoodSold($food,$date[0],$date[1]);
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
        $num = 0;
        foreach ($data as $count => $rank) {
            $num+=1;
            $newcount = '#'.$num.' '.$count;
            $data[$newcount] = $data[$count];
            unset($data[$count]);
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

    public static function countFoodSold($food,$start,$end)
    {
        foreach ($food as $key => $value) {
            $food_array = ['id'=>(int)$value['Food_ID'],'name'=>$value['Name']];
            $json_food = Json::encode($food_array);
             
            $model = RestaurantItemProfit::find()->where('fid = :fid',[':fid'=>$json_food])->andWhere(['between','created_at',$start,$end])->asArray()->all();
            $modelcount = 0;
            if(empty($model)){
                $modelcount = 0;
                $data[$value['Name']] = $modelcount;
            } else {
                foreach ($model as $k => $v) {
                    $modelcount+= $v['quantity'];
                }
                $data[$value['Name']] = $modelcount;
            }
        }
        arsort($data);
        return $data;
    }
}

