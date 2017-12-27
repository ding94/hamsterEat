<?php

namespace frontend\modules\delivery\controllers;

use yii\web\Controller;
use yii\filters\AccessControl;
use yii\helpers\ArrayHelper;
use Yii;
use frontend\controllers\OrderController;
use frontend\controllers\CommonController;
use frontend\controllers\NotificationController;
use frontend\modules\Restaurant\controllers\ProfitController;
use common\models\Order\Orderitem;
use common\models\Order\Orders;
use common\models\Company\Company;
use common\models\Order\StatusType;

class DeliveryorderController extends CommonController
{
	public function behaviors()
    {
         return [
         	'verbs' => [
	            'class' => \yii\filters\VerbFilter::className(),
	            'actions' => [
	                'mutiple-pick'  => ['POST'],
	               
	            ],
	        ],
             'access' => [
                 'class' => AccessControl::className(),
                 //'only' => ['logout', 'signup','index'],
                 'rules' => [
                     [
                         'actions' => ['mutiple-pick','pickup','order','history',
                         'update-pickedup','update-completed'],
                         'allow' => true,
                         'roles' => ['rider'],
                     ],
                 ]
             ]
        ];
    }

    public function actionPickup()
    {
    	$link = CommonController::createUrlLink(5);
		$orderitem = Orderitem::find()->where('deliveryman = :u and OrderItem_Status = 4 ',[':u'=> Yii::$app->user->identity->id])->joinWith(['address','order','food.restaurant'])->all();
		$data = [];
		foreach($orderitem as $item)
		{
			$restaurantName = $item->food->restaurant->Restaurant_Name;
			$companyName = Company::findOne($item->address->cid)->name;
			$data[$restaurantName]['address'] = "http://maps.google.com/maps?daddr=".$item->food->restaurant->Restaurant_Street.",".$item->food->restaurant->Restaurant_Area.",".$item->food->restaurant->Restaurant_Postcode.",Malaysia&amp;ll=";
			$data[$restaurantName][$companyName][] = $item->Order_ID.','.$item->Delivery_ID;
			//$data[$restaurantName][$companyName]['companyaddress'] = $item->address->location;
			//$data[$restaurantName][$companyName]['restaurantaddress'] = $item->address->location;
		//var_dump($data);exit;
		}
       
		return $this->render('pickup', ['data'=>$data,'link'=>$link]);
    }

    public function actionOrder()
    {
        $dman = Orders::find()->where('deliveryman = :dman and Orders_Status != :status and Orders_Status != :status1', [':dman'=>Yii::$app->user->identity->id, ':status'=>6, ':status1'=>7])->orderBy(['Delivery_ID'=>SORT_ASC])->joinWith(['address'])->all();
        $statusid = ArrayHelper::map(StatusType::find()->all(),'id','label');
        $record = DailySignInController::getDailyData(1);
        $link = CommonController::createUrlLink(5);

		$orderitem = Orderitem::find()->where('deliveryman = :u',[':u'=> Yii::$app->user->identity->id])->joinWith(['address','order','food.restaurant'])->all();

        return $this->render('order', ['dman'=>$dman,'record'=>$record,'link'=>$link,'orderitem'=>$orderitem,'statusid'=>$statusid]);
    }

    public function actionHistory()
    {
        $dman = Orders::find()->where('deliveryman = :dman and Orders_Status = :status or Orders_Status = :status2', [':dman'=>Yii::$app->user->identity->id, ':status'=>6, ':status2'=>7])->orderBy(['Delivery_ID'=>SORT_ASC])->joinWith(['address'])->all();

        $statusid = ArrayHelper::map(StatusType::find()->all(),'id','label');
        $link = CommonController::createUrlLink(5);

        return $this->render('history', ['dman'=>$dman,'link'=>$link,'statusid'=>$statusid]);
    }


	public function actionMutiplePick()
	{
		$post = Yii::$app->request->post();
		$message = "";
		
        foreach($post['order'] as $order)
        {
        	$valid = $this->pickup($order['oid'],$order['did']);
        	if(!$valid){
        		$message .= "Order ID ".$order['oid']. " fail<br>";
        	}
        }

        if(!empty($message))
        {
        	Yii::$app->session->setFlash('warning', $message);
        }
        
        return $this->redirect(Yii::$app->request->referrer);
	}

	//This function updates the orders status to on the way and specific order item status to picked up
    public function actionUpdatePickedup($oid, $did)
    {
        
       $valid = $this->pickup($oid,$did);
       return $this->redirect(Yii::$app->request->referrer);
    }

//--This function updates the order's status to completed
    public function actionUpdateCompleted($oid, $did)
    {
        $order = OrderController::findOrder($did);
        
        $order->Orders_Status = 6;
        $profit = ProfitController::getProfit($order,$did);

        $itemProfit = ProfitController::getItemProfit($did);

        $isValid = $itemProfit == -1 ? false: true;

        $isValid = $profit->validate() && $isValid && $order->validate();
        
        if($isValid)
        {
            $order->save();
            $profit->save();
            foreach($itemProfit as $item)
            {
                $item->save();
            }
        }
        
        NotificationController::createNotification($did,4);
        return $this->redirect(Yii::$app->request->referrer);
    }

    protected static function pickup($oid,$did)
    {
    	$updateOrder = false;
        $orderitem = OrderController::findOrderitem($oid,10);
        $orderitem->OrderItem_Status = 10;
       
        $orderitem->save();
        $order = OrderController::findOrder($orderitem->Delivery_ID);

        if ($order['Orders_Status'] == 3)
        {
            $order->Orders_Status = 11;
            if(!$order->save())
            {
            	return false;
            }
        }

        $allitem = OrderItem::find()->where('Delivery_ID =:did',[':did' => $orderitem->Delivery_ID])->all();
        foreach ($allitem as $item) {
            $updateOrder = $item->OrderItem_Status == 10 ? true : false && $updateOrder;
        }

        if($updateOrder)
        {
            $order = OrderController::findOrder($orderitem->Delivery_ID);
            $order->Orders_Status = 5;
            if($order->save()){
            	NotificationController::createNotification($did,4);
            	return true;
            }
          
        }
        else{
        	 return true;
        }
       return false;
    }
}
