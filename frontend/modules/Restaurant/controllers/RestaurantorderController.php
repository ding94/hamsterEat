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
use common\models\Order\StatusType;
use common\models\Company\Company;
use common\models\Restaurant;
use common\models\food\Food;
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
		            ],
		    ],
            'access' => [
                'class' => AccessControl::className(),
                //'only' => ['logout', 'signup','index'],
                'rules' => [
                    [
                        'actions' => ['mutiple-order','index','preparing','singleReadyforpickup','history'],
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
        
        $restaurantname = Restaurant::find()->where('Restaurant_ID = :rid', [':rid'=>$rid])->one();
        if ($restaurantname['approval'] != 1) {
            Yii::$app->session->setFlash('warning','Restaurant was waiting admin to approve.');
            return $this->redirect(['/Restaurant/restaurant/restaurant-service']);
        }

        $foodid = Food::find()->where('Restaurant_ID = :rid', [':rid'=>$rid])->all();
        
        $result = Orderitem::find()->distinct()
        ->where('Restaurant_ID = :rid',[':rid'=>$restaurantname['Restaurant_ID']])
        ->andwhere(['or',['Orders_Status'=>6],['Orders_Status'=>7]])->joinWith('food')->joinWith('order');
        
        /* Code to generate pagination */
        $pagination = new Pagination(['totalCount'=>$result->count(),'pageSize'=>10]);
        $result = $result->offset($pagination->offset)
        ->limit($pagination->limit)
        ->all();
        /* end.. */

        $statusid = ArrayHelper::map(StatusType::find()->all(),'id','label');
      
        return $this->render('history', ['rid'=>$rid, 'restaurantname'=>$restaurantname, 'result'=>$result,'link'=>$link,'pagination'=>$pagination,'statusid'=>$statusid]);
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
       
        $query =Orderitem::find()->where('food.Restaurant_ID = :rid and OrderItem_Status != 1 and OrderItem_Status != 10 and OrderItem_Status != 8',[':rid'=>$rid])->joinWith(['food.restaurant','address']);

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
}