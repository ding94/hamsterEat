<?php

namespace frontend\controllers;

use Yii;
use yii\web\Controller;
use yii\data\Pagination;
use yii\web\NotFoundHttpException;
use common\models\Order\Orders;
use common\models\Order\Orderitem;
use common\models\Order\Orderitemselection;
use common\models\Order\StatusType;
use common\models\food\Food;
use common\models\Restaurant;
use frontend\controllers\NotificationController;
use kartik\mpdf\Pdf;
use yii\helpers\ArrayHelper;
use frontend\controllers\CommonController;
use yii\filters\AccessControl;
use common\models\food\Foodselection;
use common\models\food\Foodselectiontype;
use common\models\Rmanagerlevel;
use common\models\Company\Company;
use common\models\Profit\RestaurantProfit;
use frontend\modules\delivery\controllers\DailySignInController;
use frontend\modules\Restaurant\controllers\ProfitController;
use common\models\Order\DeliveryAddress;

class OrderController extends CommonController
{
    public function behaviors()
    {
         return [
             'access' => [
                 'class' => AccessControl::className(),
                 'rules' => [
                    [
                        'actions' => ['my-orders','order-details','invoice-pdf','my-order-history'],
                        'allow' => true,
                        'roles' => ['@'],

                    ],
                    [
                        'actions' => ['restaurant-orders','restaurant-order-history','update-preparing','update-readyforpickup','orderlist'],
                        'allow' => true,
                        'roles' => ['restaurant manager'],
                    ],
                    [
                        'actions' => ['deliveryman-orders','deliveryman-order-history','update-pickedup','update-completed','deliveryman-pickup'],
                        'allow' => true,
                        'roles' => ['rider'],
                    ]
                    //['actions' => [''],'allow' => true,'roles' => ['?'],],
                    
                 ]
             ]
        ];
    }
//--This function loads all the user's orders
    public function actionMyOrders($status = "")
    {    
        $countOrder = $this->getTotalOrder();
        $query = Orders::find()->where('User_Username = :uname and Orders_Status != "Not Placed" ', [':uname'=>Yii::$app->user->identity->username])->orderBy(['Delivery_ID'=>SORT_DESC]);
        $statusid = ArrayHelper::map(StatusType::find()->all(),'type','id');

        if(!empty($status))
        {
            switch ($status) {
                case 6:
                    $query->andWhere(['or',['Orders_Status'=> 7],['Orders_Status'=> $status],])->orderBy(['Delivery_ID'=>SORT_DESC]);
                    break;

                case 8:
                    $query->andWhere(['or',['Orders_Status'=> 9],['Orders_Status'=> $status],])->orderBy(['Delivery_ID'=>SORT_DESC]);
                    break;
                
                default:
                    $query->andWhere('Orders_Status = :status',[':status' => $status])->orderBy(['Delivery_ID'=>SORT_DESC]);
                    break;
            }
            $status = StatusType::find()->where(['id'=>$status])->one()->type;
        }

        $pagination = new Pagination(['totalCount'=>$query->count(),'pageSize'=>10]);
        $order = $query->offset($pagination->offset)
        ->limit($pagination->limit)
        ->all();
        $link = CommonController::createUrlLink(3);
        $this->layout = 'user';
        return $this->render('myorders', ['order'=>$order,'pagination' => $pagination,'countOrder'=>$countOrder,'link'=> $link ,'status' => empty($status) ? "All" : $status,'statusid'=>$statusid]);
    }

    /*
    * count all order status order
    * if empty let it empty
    * Completed and Rating Done Count as One
    */
    public static function getTotalOrder()
    {
        $countOrder['Not Paid']['total'] = 0;
        $countOrder['Pending']['total'] = 0;
        $countOrder['Canceled']['total'] = 0;   
        $countOrder['Preparing']['total'] = 0;   
        $countOrder['Pick Up in Process']['total'] = 0;   
        $countOrder['On The Way']['total'] = 0;   
        $countOrder['Completed']['total'] = 0;  
        $query = Orders::find()->where('User_Username = :uname', [':uname'=>Yii::$app->user->identity->username])->all();
        foreach($query as $data)
        {
            switch ($data['Orders_Status']) {

                case 6:
                    $countOrder['Completed']['total'] += 1;
                    break;

                case 7:
                    $countOrder['Completed']['total'] += 1;
                    break;

                case 8:
                    $countOrder['Canceled']['total'] += 1;
                    break;

                case 9:
                    $countOrder['Canceled']['total'] += 1;
                    break;
                
                default:
               
                    $status = StatusType::find()->where('id=:id',[':id'=>$data['Orders_Status']])->one()->type;
                    $countOrder[$status]['total'] += 1;
                    break;
            }
          
        }

        foreach($countOrder as $i=> $data)
        {
            $countOrder[$i]['total'] = $data['total'] == 0 ? "" : $data['total'];
        }
       
        return $countOrder;
    }

    public static function getTotalOrderRestaurant($rid)
    {
        $countOrder['Pending']['total'] = 0;   
        $countOrder['Canceled']['total'] = 0;   
        $countOrder['Preparing']['total'] = 0;   
        //$countOrder['Ready for Pickup']['total'] = 0;   
        $countOrder['Pick Up in Process']['total'] = 0;   
        $countOrder['On The Way']['total'] = 0;
        $count = 0;   
        $query = Orders::find()->where('Restaurant_ID = :rid and Orders_Status != 1', [':rid'=>$rid])->joinWith('order_item')->joinWith('order_item.food')->all();
        foreach($query as $data)
        {
            switch ($data['Orders_Status']) {
                case 6:
                    $count += 1;
                    break;

                case 7:
                    $count += 1;
                    break;

                case 8:
                    $countOrder['Canceled']['total'] += 1;
                    break;

                case 9:
                    $countOrder['Canceled']['total'] += 1;
                    break;
                
                default:
                    $status = StatusType::find()->where('id=:id',[':id'=>$data['Orders_Status']])->one()->type;
                    $countOrder[$status]['total'] += 1;
                    break;
            }
          
        }

        foreach($countOrder as $i=> $data)
        {
            $countOrder[$i]['total'] = $data['total'] == 0 ? "" : $data['total'];
        }
        return $countOrder;
    }

//--This function loads the specific user's order details
    public function actionOrderDetails($did)
    {
        $order = Orders::find()->where("orders.Delivery_ID = :id",[':id'=>$did])->joinWith(['address'])->one();
        $orderitems = Orderitem::find()->where('Delivery_ID = :did', [':did'=>$did])->all();
        $label = StatusType::find()->asArray()->all();

        
        date_default_timezone_set("Asia/Kuala_Lumpur");
        
        $order['Orders_Subtotal'] = number_format($order['Orders_Subtotal'],2);
        $order['Orders_DeliveryCharge'] = number_format($order['Orders_DeliveryCharge'],2);
        $order['Orders_TotalPrice'] = number_format($order['Orders_TotalPrice'],2);
        $order['Orders_DiscountTotalAmount'] = number_format($order['Orders_DiscountTotalAmount'],2);
        $order['Orders_DiscountEarlyAmount'] = number_format($order['Orders_DiscountEarlyAmount'],2);

        $this->layout = 'user';
        return $this->render('orderdetails', ['order'=>$order, 'orderitems'=>$orderitems, 'did'=>$did, 'label'=>$label]);
    }

//--This function loads all the restaurant's running orders (not completed)
    public function actionRestaurantOrders($rid,$status = "",$mode = 1)
    {
        $staff = Rmanagerlevel::find()->where('User_Username = :uname and Restaurant_ID = :id', [':uname'=>Yii::$app->user->identity->username, ':id'=>$rid])->one();
        if(empty($staff))
        {
             throw new NotFoundHttpException('Wrong Request!.');
        }
       
        $countOrder = $this->getTotalOrderRestaurant($rid);
		
        $foodid = Food::find()->where('Restaurant_ID = :rid', [':rid'=>$rid])->all();
        $restaurantname = Restaurant::find()->where('Restaurant_ID = :rid', [':rid'=>$rid])->one();
		$statusid = ArrayHelper::map(StatusType::find()->all(),'type','id');
		
		//$delid = DeliveryAddress::find()->where('delivery_id=:did',[':did'=>$did])->one();
		//$delid = DeliveryAddress::find()->one()->cid;
		//$companyname=Company::find()->where('id=:cid',[':cid'=>$delid])->one()->name;
		//var_dump($companyname);exit;
		
        $result = Orderitem::find()->distinct()->where('Restaurant_ID = :rid',[':rid'=>$restaurantname['Restaurant_ID']])->joinWith(['food','order','address']);;
		
		
        if(!empty($status))
        {
            $result->andWhere(['Orders_Status'=>$status]);
        }

        $result->andWhere("Orders_Status != 1 and Orders_Status != 6");
        
        $pagination = new Pagination(['totalCount'=>$result->count(),'pageSize'=>10]);
        $result = $result->offset($pagination->offset)
        ->limit($pagination->limit)
        ->all();


        $linkData = CommonController::restaurantPermission($rid);

        $link = CommonController::getRestaurantOrdersUrl($rid);

        return $this->render('restaurantorders', ['rid'=>$rid, 'foodid'=>$foodid, 'restaurantname'=>$restaurantname, 'result'=>$result,'link'=>$link,'pagination'=>$pagination,'status'=>$status,'countOrder'=>$countOrder, 'mode'=>$mode,'statusid'=>$statusid]);
    }

//--This function loads all the specific delivery man's assigned orders (not completed)
    public function actionDeliverymanOrders()
    {
        $dman = Orders::find()->where('deliveryman = :dman and Orders_Status != :status and Orders_Status != :status1', [':dman'=>Yii::$app->user->identity->id, ':status'=>6, ':status1'=>7])->orderBy(['Delivery_ID'=>SORT_ASC])->joinWith(['address'])->all();
        $statusid = ArrayHelper::map(StatusType::find()->all(),'id','label');
        $record = DailySignInController::getDailyData(1);
        $link = CommonController::createUrlLink(5);


		$orderitem = Orderitem::find()->where('deliveryman = :u',[':u'=> Yii::$app->user->identity->id])->joinWith(['address','order','food.restaurant'])->all();

        return $this->render('deliverymanorder', ['dman'=>$dman,'record'=>$record,'link'=>$link,'orderitem'=>$orderitem,'statusid'=>$statusid]);

    }
	
	public function actionDeliverymanPickup()
	{
		$link = CommonController::createUrlLink(5);
		$orderitem = Orderitem::find()->where('deliveryman = :u',[':u'=> Yii::$app->user->identity->id])->joinWith(['address','order','food.restaurant'])->all();
		$checklist= new Orderitem();
		$data = [];
		foreach($orderitem as $item)
		{
			$restaurantName = $item->food->restaurant->Restaurant_Name;
			$companyName = Company::findOne($item->address->cid)->name;
			$data[$restaurantName]['address'] = "http://maps.google.com/maps?daddr=".$item->food->restaurant->Restaurant_Street.",".$item->food->restaurant->Restaurant_Area.",".$item->food->restaurant->Restaurant_Postcode.",Malaysia&amp;ll=";
			$data[$restaurantName][$companyName][] = $item->Order_ID;
			//$data[$restaurantName][$companyName]['companyaddress'] = $item->address->location;
			//$data[$restaurantName][$companyName]['restaurantaddress'] = $item->address->location;
		//var_dump($data);exit;
		}
		return $this->render('deliverymanorderTEST', ['data'=>$data,'link'=>$link,'checklist'=>$checklist]);
		
	}

//--This function updares the order's status and the specific order item status to preparing
    public function actionUpdatePreparing($oid, $rid)
    {
        $updateOrder = false;
        $orderitem = $this->findOrderitem($oid);
        $orderitem->OrderItem_Status = 3;

        $orderitem->save();
        $allitem = OrderItem::find()->where('Delivery_ID =:did',[':did' => $orderitem->Delivery_ID])->all();
        foreach ($allitem as $item) {
            $updateOrder = $item->OrderItem_Status == 3 ? true : false && $updateOrder;
        }
      
        if($updateOrder)
        {
            $order = $this->findOrder($orderitem->Delivery_ID);
            $order->Orders_Status = 3;
            $order->save();
        }
        
        NotificationController::createNotification($oid,2);
        NotificationController::createNotification($oid,3);
        return $this->redirect(Yii::$app->request->referrer);
    }

//--This function updates the specific order item status to ready for pick up
    public function actionUpdateReadyforpickup($oid, $rid)
    {
        $orderitem = $this->findOrderitem($oid);
        $orderitem->OrderItem_Status = 4;
        $orderitem->save();
        return $this->redirect(Yii::$app->request->referrer);
    }

//This function updates the orders status to on the way and specific order item status to picked up
    public function actionUpdatePickedup($oid, $did)
    {
        $updateOrder = false;
        $orderitem = $this->findOrderitem($oid);
        $orderitem->OrderItem_Status = 10;
       
        $orderitem->save();
        $order = $this->findOrder($orderitem->Delivery_ID);

        if ($order['Orders_Status'] == 3)
        {
            $order->Orders_Status = 11;
            $order->save();
        }

        $allitem = OrderItem::find()->where('Delivery_ID =:did',[':did' => $orderitem->Delivery_ID])->all();
        foreach ($allitem as $item) {
            $updateOrder = $item->OrderItem_Status == 10 ? true : false && $updateOrder;
        }

        if($updateOrder)
        {
            $order = $this->findOrder($orderitem->Delivery_ID);
            $order->Orders_Status = 5;
            $order->save();
            NotificationController::createNotification($did,4);
        }
       
        return $this->redirect(['deliveryman-orders']);
    }

//--This function updates the order's status to completed
    public function actionUpdateCompleted($oid, $did)
    {
        $order = $this->findOrder($did);
        
        
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

//--This loads the order history as an invoice in pdf form
    public function actionInvoicePdf($did)
    {
        $order = Orders::find()->where('Delivery_ID = :did', [':did'=>$did])->one();
        $orderitem = Orderitem::find()->where('Delivery_ID = :did', [':did'=>$did])->all();
        $address = DeliveryAddress::find()->where('delivery_id=:did',[':did'=>$did])->one();
        
        $pdf = new Pdf([
            'mode' => Pdf::MODE_UTF8,
            'content' => $this->renderPartial('orderhistorydetails',['order'=>$order, 'orderitem' => $orderitem ,'address'=>$address,'did'=>$did]),
            'options' => [
                'title' => 'Invoice',
                'subject' => 'Sample Subject',
            ],
            'methods' => [
                'SetHeader' => ['Generated By HAMSTEREAT'],
                'SetFooter' => ['|Page{PAGENO}|'],
            ]
            ]);
        return $pdf->render();
    }

    public function actionOrderlist($rid,$status = "",$mode = 1)
    {
        $restaurant = Restaurant::find()->where('Restaurant_ID = :rid', [':rid'=>$rid])->one();
        // food data with condition of today's orders and other table
        $allData = [];
        $data= Orderitem::find()->where('Restaurant_ID = :id',['id'=>$rid])->joinWith(['item_status'=>function($query){
            $query->where(['>=','Change_PendingDateTime',strtotime(date('Y-m-d'))]);},
            'food','order_selection'=>function($query){ $query->orderby('Selection_ID ASC');} ])->all();

        foreach($data as $item)
        {
            $allData[$item['food']['Food_ID']][] = $item;
        }
        
        return $this->render('orderlistpdf', ['rid'=>$rid,'allData'=>$allData,'restaurant'=>$restaurant]);
    }

//--This function loads the restaurant's orders which have been completed
    public function actionRestaurantOrderHistory($rid)
    {
        $foodid = Food::find()->where('Restaurant_ID = :rid', [':rid'=>$rid])->all();
        
        $restaurantname = Restaurant::find()->where('Restaurant_ID = :rid', [':rid'=>$rid])->one();

        $result = Orderitem::find()->distinct()->where('Restaurant_ID = :rid',[':rid'=>$restaurantname['Restaurant_ID']])->andWhere(['like', 'Orders_Status', 6])->orWhere(['like', 'Orders_Status', 7])->joinWith('food')->joinWith('order');
        
        /* Code to generate pagination */
        $pagination = new Pagination(['totalCount'=>$result->count(),'pageSize'=>10]);
        $result = $result->offset($pagination->offset)
        ->limit($pagination->limit)
        ->all();
        /* end.. */

        $staff = Rmanagerlevel::find()->where('User_Username = :uname and Restaurant_ID = :id', [':uname'=>Yii::$app->user->identity->username, ':id'=>$rid])->one();
        $statusid = ArrayHelper::map(StatusType::find()->all(),'id','label');
        $linkData = CommonController::restaurantPermission($rid);
        $link = CommonController::getRestaurantUrl($linkData[0],$linkData[1],$linkData[2],$rid);
        return $this->render('restaurantorderhistory', ['rid'=>$rid, 'foodid'=>$foodid, 'restaurantname'=>$restaurantname, 'result'=>$result, 'staff'=>$staff,'link'=>$link,'pagination'=>$pagination,'statusid'=>$statusid]);
    }

//--This function loads the delivery man's assigned orders which have been completed
    public function actionDeliverymanOrderHistory()
    {
        $dman = Orders::find()->where('deliveryman = :dman and Orders_Status = :status or Orders_Status = :status2', [':dman'=>Yii::$app->user->identity->id, ':status'=>6, ':status2'=>7])->orderBy(['Delivery_ID'=>SORT_ASC])->joinWith(['address'])->all();

        $statusid = ArrayHelper::map(StatusType::find()->all(),'id','label');
        $link = CommonController::createUrlLink(5);

        return $this->render('deliverymanorderhistory', ['dman'=>$dman,'link'=>$link,'statusid'=>$statusid]);
    }

    protected function findOrder($id)
    {
        if (($model = Orders::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

    protected function findOrderitem($id)
    {
        if (($model = OrderItem::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}