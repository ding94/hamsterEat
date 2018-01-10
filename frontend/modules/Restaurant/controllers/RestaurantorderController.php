<?php
namespace frontend\modules\Restaurant\controllers;

use yii\web\Controller;
use yii\filters\AccessControl;
use Yii;
use frontend\controllers\CommonController;
use frontend\controllers\OrderController;
use common\models\Order\Orderitem;
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
                        'actions' => ['mutiple-order'],
                        'allow' => true,
                        'roles' => ['restaurant manager'],
 
                    ],
                ]
            ]
        ];
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