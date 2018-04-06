<?php

namespace frontend\modules\delivery\controllers;

use yii\web\Controller;
use yii\filters\AccessControl;
use yii\helpers\ArrayHelper;
use yii\data\Pagination;
use Yii;
use frontend\controllers\OrderController;
use frontend\controllers\CommonController;
use frontend\modules\notification\controllers\NoticController;
use frontend\modules\Restaurant\controllers\ProfitController;
use frontend\models\OrderHistorySearch;
use common\models\Order\{Orderitem,Orders,StatusType};
use common\models\Company\Company;
use common\models\User;

class DeliveryorderController extends CommonController
{
	public function behaviors()
    {
         return [
         	'verbs' => [
	            'class' => \yii\filters\VerbFilter::className(),
	            'actions' => [
	               'mutiple-pick'  => ['POST'],
	               'mutiple-complete' => ['POST'],
	            ],
	        ],
             'access' => [
                 'class' => AccessControl::className(),
                 //'only' => ['logout', 'signup','index'],
                 'rules' => [
                     [
                         'actions' => ['mutiple-pick','mutiple-complete','pickup','order','history',
                         'update-pickedup','update-completed','complete'],
                         'allow' => true,
                         'roles' => ['rider'],
                     ],
                 ]
             ]
        ];
    }

    /*
    * show all deliveryman order
    */
    public function actionOrder()
    {
        $query = Orderitem::find()->where('deliveryman = :dman ',[':dman'=>Yii::$app->user->identity->id])->andWhere(['in','Orders_Status',[2,3,4,5,11]])->joinWith(['address','order']);
        $dman = array();
        foreach($query->each() as $key => $data)
        {
           $dman[$data->Delivery_ID]['order'] = $data->order;
           unset($data->order);
           $dman[$data->Delivery_ID]['address'] = $data->address;
           unset($data->address);
           $dman[$data->Delivery_ID]['item'][] = $data;
        }
       
        //$dman = Orders::find()->where('deliveryman = :dman and Orders_Status != 6 and Orders_Status != 7 and Orders_Status !=8 and Orders_Status != 9', [':dman'=>Yii::$app->user->identity->id])->orderBy(['Delivery_ID'=>SORT_ASC])->joinWith(['address'])->all();
        $statusid = ArrayHelper::map(StatusType::find()->all(),'id','label');
        $record = DailySignInController::getDailyData(1);
        $link = CommonController::createUrlLink(5);
        
        return $this->render('order', ['dman'=>$dman,'record'=>$record,'link'=>$link,'statusid'=>$statusid]);
    }

    /*
    * combine data in status pickup
    * for multiple pass data
    */
    public function actionPickup()
    {
    	$link = CommonController::createUrlLink(5);
		$orderitem = Orderitem::find()->where('deliveryman = :u and OrderItem_Status = 4 ',[':u'=> Yii::$app->user->identity->id])->joinWith(['address','order','food.restaurant'])->all();
		$data = [];
		foreach($orderitem as $item)
		{
			$restaurantName = CommonController::getRestaurantName($item->food->restaurant->Restaurant_ID);
			$companyName = Company::findOne($item->address->cid)->name;
			$data[$restaurantName]['address'] = "http://maps.google.com/maps?daddr=".$item->food->restaurant->Restaurant_Street.",".$item->food->restaurant->Restaurant_Area.",".$item->food->restaurant->Restaurant_Postcode.",Malaysia&amp;ll=";
			$data[$restaurantName][$companyName][] = $item->Order_ID.','.$item->Delivery_ID;
			//$data[$restaurantName][$companyName]['companyaddress'] = $item->address->location;
			//$data[$restaurantName][$companyName]['restaurantaddress'] = $item->address->location;
		//var_dump($data);exit;
		}
       
		return $this->render('pickup', ['data'=>$data,'link'=>$link]);
    }

    /*
    * combine data in status complete
    * for multiple pass data
    */
    public function actionComplete()
    {
    	$link = CommonController::createUrlLink(5);
    	$orders = Orders::find()->where('deliveryman = :u and Orders_Status = 5',[':u'=> Yii::$app->user->identity->id])->joinWith(['address'])->all();
    	$test=new Orderitem();
		
               
    	$data = [];
    	foreach($orders as $order)
    	{
    		$company = Company::findOne($order->address->cid);
    		$data[$company->name]['address'] = "http://maps.google.com/maps?daddr=".$company->address.",".$company->area.",".$company->postcode.",Malaysia&amp;ll=";
    		
    		$singleprice = $order->Orders_PaymentMethod == 'Cash on Delivery' ? $order->Orders_TotalPrice : 0;
    		if(!array_key_exists('collectprice',$data[$company->name]))
            {
                $data[$company->name]['collectprice'] = $singleprice;
            }
            else
            {
            	$data[$company->name]['collectprice'] += $singleprice;
            }
            $data[$company->name]['id'][$order->Delivery_ID] =  $singleprice;

    	}
    	//var_dump($data);exit;
    	return $this->render("complete",['data'=>$data,'link'=>$link,'test'=>$test]);
    }

    public function actionHistory()
    {
        $searchModel = new OrderHistorySearch;
        $query =  $searchModel->search(Yii::$app->request->queryParams,Yii::$app->user->identity->id,1);

        $countQuery = clone $query;
        array_pop($searchModel->keyWordArray);
      
        $pages = new Pagination(['totalCount' => $countQuery->count()]);

        $data = $query->offset($pages->offset)
        ->limit($pages->limit)
        ->all(); 

        $dman =array();
        foreach($data as $value)
        {
            $dman[$value->Delivery_ID]['order'] = $value->order;
            $dman[$value->Delivery_ID]['item'][$value->food->Restaurant_ID][] = $value;
        }

        $statusid = ArrayHelper::map(StatusType::find()->all(),'id','label');
        $status = ArrayHelper::map(StatusType::find()->all(),'id','type');
        $link = CommonController::createUrlLink(5);

        return $this->render('history', ['dman'=>$dman,'pagination'=>$pages,'link'=>$link,'statusid'=>$statusid,'searchModel'=>$searchModel,'status'=>$status]);
    }

	public function actionMutiplePick()
	{
		$post = Yii::$app->request->post();

		$message = array();
        foreach($post['order'] as $order)
        {
        	$valid = $this->singlePickup($order['oid'],$order['did']);
        	if(!$valid){

        		$message .= Yii::t('order',"Order ID")." ".$order['oid']." ".Yii::t('common',"fail")."<br>";
        	}
        }

        if(!empty($message))
        {
        	Yii::$app->session->setFlash('warning', $message);
        }
        
        return $this->redirect(Yii::$app->request->referrer);
	}

    public function actionMutipleComplete()
    {
        $post = Yii::$app->request->post();

        $message = "";
        if(empty($post['did']))
        {
            Yii::$app->session->setFlash('danger', Yii::t('m-delivery',"Please Select One!"));
            return $this->redirect(Yii::$app->request->referrer);
        }

        foreach($post['did'] as $did)
        {
            
            $valid = $this->singleComplete($did);
            if(!$valid)
            {
                $message .= Yii::t('common',"Delivery ID")." ".$order['oid']." ".Yii::t('common',"fail")."<br>";
            }
        }
        if(!empty($message))
        {
            Yii::$app->session->setFlash('warning', $message);
        }
        
        return $this->redirect(Yii::$app->request->referrer);
    }

	//This function updates the orders status to on the way and specific order item status to picked up
    public function actionUpdatePickedup($oid,$did)
    {
       $valid = $this->singlePickup($oid,$did);
       return $this->redirect(Yii::$app->request->referrer);
    }

//--This function updates the order's status to completed
    public function actionUpdateCompleted($did)
    {
        $this->singleComplete($did);
        return $this->redirect(Yii::$app->request->referrer);
    }

    protected static function singleComplete($did)
    {
        $order = OrderController::findOrder($did);
        
        $order->Orders_Status = 6;
        $profit = ProfitController::getProfit($order,$did);

        $itemProfit = ProfitController::getItemProfit($did);

        $isValid = $itemProfit == -1 ? false: true;

        $isValid = $profit->validate() && $isValid && $order->validate();
        
        if($isValid)
        {
            $user = User::find()->where('username = :u',[':u'=>$order->User_Username])->one();
            $order->save();
            $profit->save();
            foreach($itemProfit as $item)
            {
                $item->save();
            }
            NoticController::centerNotic(2,$order->Orders_Status,$did,$user->id); 
           
            return true;
        }
        else
        {
            return false;
        }
         
    }
    protected static function singlePickup($oid,$did)
    {
    	$updateOrder = false;
        $orderitem = OrderController::findOrderitem($oid,10);
        $orderitem->OrderItem_Status = 10;
       
        $order = OrderController::findOrder($orderitem->Delivery_ID);
        $notic = array();
        if ($order['Orders_Status'] == 3)
        {

            $order->Orders_Status = 11;
         
            if(!$order->save() || !$orderitem->save())
            {
            	return false;
            }
            else
            {
                $notic['type'] = 2;
                $notic['status'] = $order->Orders_Status;
                $notic['id'] = $order->Delivery_ID;
            }
        }
        else
        {  
            if(! $orderitem->save())
            {
                return false;
            } 
            else
            {
                $notic['type'] = 1;
                $notic['status'] = $orderitem->OrderItem_Status;
                $notic['id'] = $orderitem->Order_ID;
            }
        }

        $allitem = OrderItem::find()->where('Delivery_ID =:did and OrderItem_Status != 10 and OrderItem_Status != 8 and OrderItem_Status != 9',[':did' => $orderitem->Delivery_ID])->all();
        $order = OrderController::findOrder($orderitem->Delivery_ID);
        $user = User::find()->where('username = :u',[':u'=>$order->User_Username])->one();
        
        if(empty($allitem))
        {
           
            $order->Orders_Status = 5;
            if($order->save()){
              
            	NoticController::centerNotic(2,$order->Orders_Status,$order->Delivery_ID,$user->id);
            	return true;
            }
            return false;
        }
        NoticController::centerNotic($notic['type'],$notic['status'],$notic['id'],$user->id);   
       return true;
    }
}
