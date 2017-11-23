<?php

namespace frontend\controllers;

use Yii;
use yii\web\Controller;
use yii\data\Pagination;
use yii\web\NotFoundHttpException;
use common\models\Orders;
use common\models\Orderitem;
use common\models\food\Food;
use common\models\Restaurant;
use frontend\controllers\NotificationController;
use kartik\mpdf\Pdf;
use frontend\controllers\CommonController;
use yii\filters\AccessControl;
use common\models\Orderitemselection;
use common\models\food\Foodselection;
use common\models\Rmanagerlevel;
use frontend\modules\delivery\controllers\DailySignInController;

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
                        'actions' => ['restaurant-orders','restaurant-order-history','update-preparing','update-readyforpickup'],
                        'allow' => true,
                        'roles' => ['restaurant manager'],
                    ],
                    [
                        'actions' => ['deliveryman-orders','deliveryman-order-history','update-pickedup','update-completed'],
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
        if(!empty($status))
        {
            switch ($status) {
                case 'Completed':
                    $query->andWhere(['or',['Orders_Status'=> 'Rating Done'],['Orders_Status'=> $status],])->orderBy(['Delivery_ID'=>SORT_DESC]);
                    break;

                case 'Canceled':
                    $query->andWhere(['or',['Orders_Status'=> 'Canceled and Refunded'],['Orders_Status'=> $status],])->orderBy(['Delivery_ID'=>SORT_DESC]);
                    break;
                
                default:
                    $query->andWhere('Orders_Status = :status',[':status' => $status])->orderBy(['Delivery_ID'=>SORT_DESC]);
                    break;
            }
        }

        $pagination = new Pagination(['totalCount'=>$query->count(),'pageSize'=>10]);
        $order = $query->offset($pagination->offset)
        ->limit($pagination->limit)
        ->all();
        $link = CommonController::createUrlLink(3);
        $this->layout = 'user';
        return $this->render('myorders', ['order'=>$order,'pagination' => $pagination,'countOrder'=>$countOrder,'link'=> $link ,'status' => empty($status) ? "All" : $status ]);
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
        $query = Orders::find()->where('User_Username = :uname and Orders_Status != "Not Placed"', [':uname'=>Yii::$app->user->identity->username])->all();
        foreach($query as $data)
        {
            switch ($data['Orders_Status']) {
                case 'Completed':
                    $countOrder['Completed']['total'] += 1;
                    break;

                case 'Rating Done':
                    $countOrder['Completed']['total'] += 1;
                    break;

                case 'Canceled':
                    $countOrder['Canceled']['total'] += 1;
                    break;

                case 'Canceled and Refunded':
                    $countOrder['Canceled']['total'] += 1;
                    break;
                
                default:
                    $countOrder[$data['Orders_Status']]['total'] += 1;
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
        $countOrder['Pick Up in Process']['total'] = 0;   
        $countOrder['On The Way']['total'] = 0;
        $count = 0;   
        $query = Orders::find()->where('Restaurant_ID = :rid and Orders_Status != "Not Placed"', [':rid'=>$rid])->joinWith('order_item')->joinWith('order_item.food')->all();
        foreach($query as $data)
        {
            switch ($data['Orders_Status']) {
                case 'Completed':
                    $count += 1;
                    break;

                case 'Rating Done':
                    $count += 1;
                    break;

                case 'Canceled':
                    $countOrder['Canceled']['total'] += 1;
                    break;

                case 'Canceled and Refunded':
                    $countOrder['Canceled']['total'] += 1;
                    break;
                
                default:
                    $countOrder[$data['Orders_Status']]['total'] += 1;
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
        $order = Orders::find()->where('Delivery_ID = :did', [':did'=>$did])->one();
        $orderitems = Orderitem::find()->where('Delivery_ID = :did', [':did'=>$did])->all();

        if($order['Orders_Status']== 'Pending'){
            $label='<span class="label label-warning">'.$order['Orders_Status'].'</span>';
        }
        elseif($order['Orders_Status']== 'Preparing'){
                $label='<span class="label label-info">'.$order['Orders_Status'].'</span>';
        }
        elseif($order['Orders_Status']== 'Pick Up in Process'){
                $label='<span class="label label-info">'.$order['Orders_Status'].'</span>';
        }
        elseif($order['Orders_Status']== 'On The Way'){
            $label='<span class="label label-info">'.$order['Orders_Status'].'</span>';
        }
        elseif($order['Orders_Status']== 'Completed'){
            $label='<span class="label label-success">'.$order['Orders_Status'].'</span>';
        }
        elseif($order['Orders_Status']== 'Not Paid'){
            $label='<span class="label label-warning">'.$order['Orders_Status'].'</span>';
        }
        else{
            $label='<span class="label label-success">Rating Done</span>';
        }
        
        date_default_timezone_set("Asia/Kuala_Lumpur");
        $this->layout = 'user';
        return $this->render('orderdetails', ['order'=>$order, 'orderitems'=>$orderitems, 'did'=>$did, 'label'=>$label]);
    }

//--This function loads all the restaurant's running orders (not completed)
    public function actionRestaurantOrders($rid,$status = "",$mode = 1)
    {
        $countOrder = $this->getTotalOrderRestaurant($rid);
        $foodid = Food::find()->where('Restaurant_ID = :rid', [':rid'=>$rid])->all();
        $restaurantname = Restaurant::find()->where('Restaurant_ID = :rid', [':rid'=>$rid])->one();

        $result = Orderitem::find()->distinct()->where('Restaurant_ID = :rid',[':rid'=>$restaurantname['Restaurant_ID']])->joinWith(['food','order']);

        if(!empty($status))
        {
            $result->andWhere(['Orders_Status'=>$status]);
        }
        $result->andWhere("Orders_Status != 'Not Paid' and Orders_Status != 'Completed'");

        $pagination = new Pagination(['totalCount'=>$result->count(),'pageSize'=>10]);
        $result = $result->offset($pagination->offset)
        ->limit($pagination->limit)
        ->all();

        $staff = Rmanagerlevel::find()->where('User_Username = :uname and Restaurant_ID = :id', [':uname'=>Yii::$app->user->identity->username, ':id'=>$rid])->one();
        $link = CommonController::getRestaurantOrdersUrl($rid);
        return $this->render('restaurantorders', ['rid'=>$rid, 'foodid'=>$foodid, 'restaurantname'=>$restaurantname, 'result'=>$result, 'staff'=>$staff,'link'=>$link,'pagination'=>$pagination,'status'=>$status,'countOrder'=>$countOrder, 'mode'=>$mode]);
    }

//--This function loads all the specific delivery man's assigned orders (not completed)
    public function actionDeliverymanOrders()
    {
        $dman = Orders::find()->where('Orders_DeliveryMan = :dman and Orders_Status != :status and Orders_Status != :status1', [':dman'=>Yii::$app->user->identity->username, ':status'=>'Completed', ':status1'=>'Rating Done'])->orderBy(['Delivery_ID'=>SORT_ASC])->all();

        $record = DailySignInController::getDailyData(1);
        $link = CommonController::createUrlLink(5);

        return $this->render('deliverymanorder', ['dman'=>$dman,'record'=>$record,'link'=>$link]);
    }

//--This function updares the order's status and the specific order item status to preparing
    public function actionUpdatePreparing($oid, $rid)
    {
        $updateOrder = false;
        $orderitem = $this->findOrderitem($oid);
        $orderitem->OrderItem_Status = "Preparing";
       
        $orderitem->save();
        $allitem = OrderItem::find()->where('Delivery_ID =:did',[':did' => $orderitem->Delivery_ID])->all();
        foreach ($allitem as $item) {
            $updateOrder = $item->OrderItem_Status == 'Preparing' ? true : false && $updateOrder;
        }
      
        if($updateOrder)
        {
            $order = $this->findOrder($orderitem->Delivery_ID);
            $order->Orders_Status = 'Preparing';
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
        $orderitem->OrderItem_Status = 'Ready For Pick Up';
        $orderitem->save();
        return $this->redirect(Yii::$app->request->referrer);
    }

//This function updates the orders status to on the way and specific order item status to picked up
    public function actionUpdatePickedup($oid, $did)
    {
        $updateOrder = false;
        $orderitem = $this->findOrderitem($oid);
        $orderitem->OrderItem_Status = "Picked Up";
       
        $orderitem->save();
        $order = $this->findOrder($orderitem->Delivery_ID);

        if ($order['Orders_Status'] == 'Preparing')
        {
            $order->Orders_Status = 'Pick Up in Process';
            $order->save();
        }

        $allitem = OrderItem::find()->where('Delivery_ID =:did',[':did' => $orderitem->Delivery_ID])->all();
        foreach ($allitem as $item) {
            $updateOrder = $item->OrderItem_Status == 'Picked Up' ? true : false && $updateOrder;
        }

        if($updateOrder)
        {
            $order = $this->findOrder($orderitem->Delivery_ID);
            $order->Orders_Status = 'On The Way';
            $order->save();
            NotificationController::createNotification($did,4);
        }
       
        return $this->redirect(['deliveryman-orders']);
    }

//--This function updates the order's status to completed
    public function actionUpdateCompleted($oid, $did)
    {
        $sql = "UPDATE orders SET Orders_Status = 'Completed' WHERE Delivery_ID = ".$did."";
        Yii::$app->db->createCommand($sql)->execute();

        $time = time();
        $sql3 = "UPDATE ordersstatuschange SET OChange_CompletedDateTime = ".$time." WHERE Delivery_ID = ".$did."";
        Yii::$app->db->createCommand($sql3)->execute();
        NotificationController::createNotification($did,4);

//------This calculates the restaurant's earning in the whole order
        $orderids = Orderitem::find()->where('Delivery_ID = :did', [':did'=>$did])->all();
        $thefinalselectionprice = 0;
        $thefinalmoneycollected = 0;
        $thefinalfoodprice = 0;
        foreach ($orderids as $orderids) :
            $thequantity = $orderids['OrderItem_Quantity'];
            $theorderid = $orderids['Order_ID'];
            $thefoodid = $orderids['Food_ID'];

            $thefoodprice = Food::find()->where('Food_ID = :fid', [':fid' => $thefoodid])->one();
            $thefoodprice = $thefoodprice['BeforeMarkedUp'];

            $selectionids = Orderitemselection::find()->where('Order_ID = :oid', [':oid' => $theorderid])->all();
            foreach ($selectionids as $selectionids) :
                $theselectionid = $selectionids['Selection_ID'];

                $theselectionprice = Foodselection::find()->where('ID = :sid', [':sid' => $theselectionid])->one();
                $theselectionprice = $theselectionprice['BeforeMarkedUp'];
                
                $thefinalselectionprice = $thefinalselectionprice + $theselectionprice;
            endforeach;
            
            $thefinalfoodprice = $thefinalfoodprice + $thefoodprice;
            $themoneycollected = ($thefinalfoodprice + $thefinalselectionprice) * $thequantity;
        endforeach;
        $thefinalmoneycollected = $thefinalmoneycollected + $themoneycollected;

        $order = Orders::find()->where('Delivery_ID = :did', [':did' => $did])->one();
        $order->Orders_RestaurantEarnings = $thefinalmoneycollected;
        $order->save();

// This calculates the restaurant's earning per order item
        $orderitems = Orderitem::find()->where('Delivery_ID = :did', [':did'=>$did])->all();
        foreach ($orderitems as $orderitem) :
            $foodselectiontotalprice = 0;
            $foodbeforemarkedup = Food::find()->where('Food_ID = :fid', [':fid'=>$orderitem['Food_ID']])->one();
            $foodbeforemarkedup = $foodbeforemarkedup['BeforeMarkedUp'];

            $selectionz = Orderitemselection::find()->where('Order_ID = :oid',[':oid'=>$orderitem['Order_ID']])->all();
            foreach ($selectionz as $selectionz) :
                $selectionnamez = Foodselection::find()->where('ID =:sid',[':sid'=>$selectionz['Selection_ID']])->one();
                if (!is_null($selectionnamez['ID']))
                {
                    $foodselectionprice = $selectionnamez['BeforeMarkedUp'];
                    $foodselectiontotalprice = $foodselectiontotalprice + $foodselectionprice;
                }
            endforeach;
            $foodprice = Food::find()->where('Food_ID = :fid', [':fid'=>$orderitem['Food_ID']])->one();
            $foodprice = $foodprice['BeforeMarkedUp'];
            $orderquantity = $orderitem['OrderItem_Quantity'];
            $orderearnings = ($foodprice + $foodselectiontotalprice) * $orderquantity;

            $updateorderitem = Orderitem::find()->where('Order_ID = :oid', [':oid'=>$orderitem['Order_ID']])->one();
            $updateorderitem->Restaurant_Share = $orderearnings;
            $updateorderitem->save();
        endforeach;


        return $this->redirect(['deliveryman-orders']);
    }

//--This loads the order history as an invoice in pdf form
    public function actionInvoicePdf($did)
    {
        $order = Orders::find()->where('Delivery_ID = :did', [':did'=>$did])->one();
        $orderitem = Orderitem::find()->where('Delivery_ID = :did', [':did'=>$did])->all();
        
        $pdf = new Pdf([
            'mode' => Pdf::MODE_UTF8,
            'content' => $this->renderPartial('orderhistorydetails',['order'=>$order, 'orderitem' => $orderitem ,'did'=>$did]),
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

//--This function loads the restaurant's orders which have been completed
    public function actionRestaurantOrderHistory($rid)
    {
        $foodid = Food::find()->where('Restaurant_ID = :rid', [':rid'=>$rid])->all();
        
        $restaurantname = Restaurant::find()->where('Restaurant_ID = :rid', [':rid'=>$rid])->one();

        $result = Orderitem::find()->distinct()->where('Restaurant_ID = :rid',[':rid'=>$restaurantname['Restaurant_ID']])->andWhere(['like', 'Orders_Status', 'Completed'])->orWhere(['like', 'Orders_Status', 'Rating Done'])->joinWith('food')->joinWith('order');
        
        /* Code to generate pagination */
        $pagination = new Pagination(['totalCount'=>$result->count(),'pageSize'=>10]);
        $result = $result->offset($pagination->offset)
        ->limit($pagination->limit)
        ->all();
        /* end.. */

        $staff = Rmanagerlevel::find()->where('User_Username = :uname and Restaurant_ID = :id', [':uname'=>Yii::$app->user->identity->username, ':id'=>$rid])->one();

        $link = CommonController::getRestaurantUrl($rid,$restaurantname['Restaurant_AreaGroup'],$restaurantname['Restaurant_Area'],$restaurantname['Restaurant_Postcode'],$staff['RmanagerLevel_Level']);
        return $this->render('restaurantorderhistory', ['rid'=>$rid, 'foodid'=>$foodid, 'restaurantname'=>$restaurantname, 'result'=>$result, 'staff'=>$staff,'link'=>$link,'pagination'=>$pagination]);
    }

//--This function loads the delivery man's assigned orders which have been completed
    public function actionDeliverymanOrderHistory()
    {
        $dman = Orders::find()->where('Orders_DeliveryMan = :dman and Orders_Status = :status or Orders_status = :status2', [':dman'=>Yii::$app->user->identity->username, ':status'=>'Completed', ':status2'=>'Rating Done'])->orderBy(['Delivery_ID'=>SORT_ASC])->all();
        $link = CommonController::createUrlLink(5);

        return $this->render('deliverymanorderhistory', ['dman'=>$dman,'link'=>$link]);
    }

//--This function loads the user's orders history in normal form
    public function actionMyOrderHistory()
    {
        $orders = Orders::find()->where('User_Username = :uname and Orders_Status = :status', [':uname'=>Yii::$app->user->identity->username, ':status'=>'Rating Done'])->orderBy(['Delivery_ID'=>SORT_ASC])->all();
        $this->layout = 'user';

        return $this->render('myordershistory', ['orders'=>$orders]);
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