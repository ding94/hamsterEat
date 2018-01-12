<?php
namespace frontend\modules\Restaurant\controllers;

use yii\web\Controller;
use yii\filters\AccessControl;
use yii\helpers\ArrayHelper;
use yii\data\Pagination;
use Yii;
use frontend\controllers\CommonController;
use frontend\controllers\OrderController;
use common\models\Order\Orderitem;
use common\models\Order\Orders;
use common\models\Order\StatusType;
use common\models\Company\Company;
use common\models\Restaurant;
use common\models\food\Food;
use common\models\Profit\RestaurantProfit;
use frontend\controllers\NotificationController;

class RestaurantorderController extends CommonController
{
	public function behaviors()
    {
         return [
         	'verbs' => [
		            'class' => \yii\filters\VerbFilter::className(),
		            'actions' => [
                       'mutiple-order'  => ['POST'],
		               'history'  => ['GET'],
		            ],
		    ],
            'access' => [
                'class' => AccessControl::className(),
                //'only' => ['logout', 'signup','index'],
                'rules' => [
                    [
                        'actions' => ['mutiple-order','index','preparing','singleReadyforpickup','history','readyforpickup'],
                        'allow' => true,
                        'roles' => ['restaurant manager'],
 
                    ],
                ]
            ]
        ];
    }

    public function actionIndex($rid,$status=0,$mode = 1)
    {
        $linkData = CommonController::restaurantPermission($rid);
        $link = CommonController::getRestaurantOrdersUrl($rid);

        $item ="";
        $result = $this->countOrder($rid,$status);
        $countItem = $result['count'];
        $query = $result['query'];

        if($status !=0)
        {
            $query->andWhere('OrderItem_Status = :s',[':s' => $status]);
        }

        foreach ($query->each() as $key => $data) 
        {
            $company = Company::findOne($data->address->cid);

            if(empty($company))
            {
                $item['No Company'][$data->Delivery_ID][] = $data;
            }
            else
            {
                $item[$company->name][$data->Delivery_ID][] = $data;
            }
        }
        $title = Restaurant::findOne($rid)->Restaurant_Name ."'s Order";
        $allstatus = ArrayHelper::map(StatusType::find()->all(),'type','id');

        return $this->render('index',['count'=>$countItem,'item'=>$item ,'title'=>$title,'link'=>$link,'rid'=>$rid,'allstatus'=>$allstatus,'status'=>$status,'mode'=>$mode]);
    }

    public function actionHistory($rid)
    {
        $linkData = CommonController::restaurantPermission($rid);
        $link = CommonController::getRestaurantUrl($linkData,$rid);
      
        $restaurant = Restaurant::find()->where('Restaurant_ID = :rid', [':rid'=>$rid])->one();

        if ($restaurant['approval'] != 1) {
            Yii::$app->session->setFlash('warning','Restaurant was waiting admin to approve.');
            return $this->redirect(['/Restaurant/restaurant/restaurant-service']);
        }

      
       
        //$query = Orders::find()->distinct()->Where("Restaurant_ID = :rid",[':rid'=>$rid])->joinWith(['item','item.food'])->orderBy(['Orders_DateTimeMade'=>SORT_DESC]);

        $query = Orderitem::find()->distinct()->where("Restaurant_ID = :rid",[':rid'=>$rid])->joinWith(['food','order'])->orderBy(['orderitem.Delivery_ID'=>SORT_DESC]);
       
        $arrayData = $this->getArrayData($query,$rid);
        
        $get =  Yii::$app->request->get('search');
        
        $getData = $this->getData($get,$query,$arrayData);

        $query = $getData['query'];
        $arrayData = $getData['arrayData'];

        $countQuery = clone $query;

        $pages = new Pagination(['totalCount' => $countQuery->count()]);

        $data = $query->offset($pages->offset)
        ->limit($pages->limit)
        ->all();

        $result ="";

        foreach ($data as $key => $value) { 
            $result[$value->Delivery_ID][0] = $value->order->Orders_Status;
            $result[$value->Delivery_ID][] = $value;   
        }
        
        $title = $restaurant->Restaurant_Name ."'s Orders History";

        $statusid = ArrayHelper::map(StatusType::find()->all(),'id','label');
      
        return $this->render('history', ['rid'=>$rid, 'title'=>$title, 'result'=>$result,'link'=>$link,'pagination'=>$pages,'statusid'=>$statusid,'arrayData'=>$arrayData]);
    }

    public function actionPreparing($oid, $rid)
    {
        CommonController::restaurantPermission($rid);
        $this->singlePreparing($oid,$rid);
       
        return $this->redirect(Yii::$app->request->referrer);
    }

    public function actionReadyforpickup($oid, $rid)
    {
        CommonController::restaurantPermission($rid);
        $this->singleReadyforpickup($oid,$rid);
        return $this->redirect(Yii::$app->request->referrer);
    }

    public function actionMutipleOrder($status,$rid)
    {
    	CommonController::restaurantPermission($rid);
    	$post = Yii::$app->request->post();
    	
    	if(empty($post['oid']))
    	{
    		Yii::$app->session->setFlash('danger', "Please Select One!!");
            return $this->redirect(Yii::$app->request->referrer);
    	}

    	foreach($post['oid'] as $oid)
    	{
    		
    		$isValid = self::detectStatus($status,$rid,$oid);
    		if(!$isValid)
    		{
    			 $message .= "Order ID ".$oid. " fail<br>";
    		}
    	}

    	if(!empty($message))
        {
           Yii::$app->session->setFlash('warning', $message);
        }
        
        return $this->redirect(Yii::$app->request->referrer);
    	//foreach($post[did] as $did => $)
    	
    }

    protected static function countOrder($rid,$status)
    {
        $countItem['Pending'] = 0;
        $countItem['Preparing'] = 0;
        $countItem['Ready for Pickup'] = 0;
       
        $query =Orderitem::find()->where('food.Restaurant_ID = :rid and OrderItem_Status != 1 and OrderItem_Status != 10 and OrderItem_Status != 8 and OrderItem_Status != 9',[':rid'=>$rid])->joinWith(['food.restaurant','address']);

        foreach ($query->each() as $key => $item) 
        {
            $status = StatusType::findOne($item->OrderItem_Status);
            $countItem[$status->type] += 1; 
        }
       
        $data['count'] = $countItem;
        $data['query'] = $query;
        return $data;
    }

    protected static function detectStatus($status,$rid,$oid)
    {
    	switch ($status) {
    		case 2:
    			$isValid = self::singlePreparing($oid,$rid);
    			break;
    		case 3:
    			$isValid = self::singleReadyforpickup($oid,$rid);
    			break;
    		default:
    			$isValid = false;
    			break;
    	}
    	return $isValid;
    }

    protected static function singlePreparing($oid, $rid)
    {
    	$updateOrder = false;
        $orderitem = OrderController::findOrderitem($oid,3);
      
        $orderitem->OrderItem_Status = 3;
      
        if(!$orderitem->save())
        {
        	return false;
        }

        $allitem = OrderItem::find()->where('Delivery_ID =:did',[':did' => $orderitem->Delivery_ID])->all();

        foreach ($allitem as $item) {
            $updateOrder = $item->OrderItem_Status == 3 ? true : false && $updateOrder;
        }
      
        if($updateOrder)
        {
            $order = OrderController::findOrder($orderitem->Delivery_ID);

            $order->Orders_Status = 3;
            if(!$order->save())
            {
            	return false;
            }
        }
        
        NotificationController::createNotification($oid,2);
        return true;
    }

    protected static function singleReadyforpickup($oid, $rid)
    {
        $orderitem = OrderController::findOrderitem($oid,4);
        $orderitem->OrderItem_Status = 4;
       	if($orderitem->save())
       	{
       		NotificationController::createNotification($orderitem->Delivery_ID,3);
       		return true;
       	}
       	return false;
        //return $this->redirect(Yii::$app->request->referrer);
    }

    protected static function getArrayData($query,$rid)
    {
        $arrayData =[];
        foreach ($query->each() as $key => $order) 
        {
            $arrayData['did'][$order->Delivery_ID] = $order->Delivery_ID;
     
            $arrayData['oid'][$order->Order_ID] = $order->Order_ID;

        }

        if(empty($arrayData))
        {
            $arrayData['did'][] = "Empty";
            $arrayData['oid'][] = "Empty";
        }
       
        $food = Food::find()->where("Restaurant_ID = :rid",[":rid"=>$rid])->all();
        $arrayData['fid'] = ArrayHelper::map($food,'Food_ID','Name');
          
        return $arrayData;
    }

    protected static function getData($data,$query,$arrayData)
    {
        $get['first'] = date("Y-m-d", strtotime("first day of this month"));
        $get['last'] = date("Y-m-d", strtotime("last day of this month"));
        $get['did'] = "";
        $get['oid'] = "";
        $get['fid'] = "";

        if(!empty($data))
        {
            if(!empty($data['did']))
            {
                $query->andWhere('orderitem.Delivery_ID = :did',[':did'=>$data['did']]);
                $get['did'] = $data['did'];
            }

            if(!empty($data['oid']))
            {
                $query->andWhere('Order_ID = :oid',[':oid'=>$data['oid']]);
                $get['oid'] = $data['oid'];
            }

            if(!empty($data['fid']))
            {
                $query->andWhere('orderitem.Food_ID = :fid',[':fid'=>$data['fid']]);
                $get['fid'] = $data['fid'];
            }

            if(!empty($data['first']) || !empty($data['last']))
            {
                $get['first'] = $data['first'];
                $get['last'] = $data['last'];
            }
           
        }

        $query->andWhere(['between','Orders_DateTimeMade',strtotime($get['first']),strtotime($get['last'])]);


        $arrayData['select']['fid'] = $get['fid'];
        $arrayData['select']['oid'] = $get['oid'];
        $arrayData['select']['did'] = $get['did'];
        $arrayData['select']['first'] = $get['first'];
        $arrayData['select']['last'] = $get['last'];

        $data['query'] = $query;
        $data['arrayData'] = $arrayData;
        return $data;
    }
}