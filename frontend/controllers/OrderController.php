<?php

namespace frontend\controllers;
use common\models\Orders;
use Yii;
use yii\web\Controller;
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

    public function actionMyOrders()
    {
        $order1 = Orders::find()->where('User_Username = :uname and Orders_Status = :status2', [':uname'=>Yii::$app->user->identity->username, ':status2'=>'Pending'])->orderBy(['Delivery_ID'=>SORT_ASC])->all();
        $order2 = Orders::find()->where('User_Username = :uname and Orders_Status = :status2', [':uname'=>Yii::$app->user->identity->username, ':status2'=>'Preparing'])->orderBy(['Delivery_ID'=>SORT_ASC])->all();
        $order3 = Orders::find()->where('User_Username = :uname and Orders_Status = :status2', [':uname'=>Yii::$app->user->identity->username, ':status2'=>'Pick Up in Process'])->orderBy(['Delivery_ID'=>SORT_ASC])->all();
        $order4 = Orders::find()->where('User_Username = :uname and Orders_Status = :status2', [':uname'=>Yii::$app->user->identity->username, ':status2'=>'On The Way'])->orderBy(['Delivery_ID'=>SORT_ASC])->all();
        $order5 = Orders::find()->where('User_Username = :uname and Orders_Status = :status2 or Orders_Status = :status1', [':uname'=>Yii::$app->user->identity->username, ':status2'=>'Completed',':status1'=>'Rating Done'])->orderBy(['Delivery_ID'=>SORT_ASC])->all();

     
          $count = count($order1);
			$count = $count ==0 ? "" : $count;
            $this->view->params['countPending'] = $count;

       
          $count = count($order2);
			$count = $count ==0 ? "" : $count;
            $this->view->params['countPreparing'] = $count;

            
          $count = count($order3);
			$count = $count ==0 ? "" : $count;
            $this->view->params['countPickup'] = $count;

       
          $count = count($order4);
			$count = $count ==0 ? "" : $count;
            $this->view->params['countOntheway'] = $count;

       
          $count = count($order5);
			$count = $count ==0 ? "" : $count;
            $this->view->params['countCompleted'] = $count;




        $this->layout = 'user';
        return $this->render('myorders', ['order1'=>$order1,'order2'=>$order2,'order3'=>$order3,'order4'=>$order4,'order5'=>$order5]);
    }

    public function actionOrderDetails($did)
    {
        $ordersdetails = Orders::find()->where('Delivery_ID = :did', [':did'=>$did])->one();
        $orderitemdetails = Orderitem::find()->where('Delivery_ID = :did', [':did'=>$did])->all();

        $subtotal = $ordersdetails['Orders_Subtotal'];
        $deliverycharge = $ordersdetails['Orders_DeliveryCharge'];
        $totalprice = $ordersdetails['Orders_TotalPrice'];
        $date = $ordersdetails['Orders_Date'];
        $time = $ordersdetails['Orders_Time'];
        $address = $ordersdetails['Orders_Location'].', '.$ordersdetails['Orders_Area'].', '.$ordersdetails['Orders_Postcode'].'.';
        $paymethod = $ordersdetails['Orders_PaymentMethod'];
          if($ordersdetails['Orders_Status']== 'Pending')
            
                {
                    $label='<span class="label label-warning">'.$ordersdetails['Orders_Status'].'</span>';
                }
                elseif($ordersdetails['Orders_Status']== 'Preparing')
                {
                    $label='<span class="label label-info">'.$ordersdetails['Orders_Status'].'</span>';
                }
                 elseif($ordersdetails['Orders_Status']== 'Pick Up in Process')
                {
                    $label='<span class="label label-info">'.$ordersdetails['Orders_Status'].'</span>';
                }
                 elseif($ordersdetails['Orders_Status']== 'On The Way')
                {
                    $label='<span class="label label-info">'.$ordersdetails['Orders_Status'].'</span>';
                }
                elseif($ordersdetails['Orders_Status']== 'Completed')
                {
                    $label='<span class="label label-success">'.$ordersdetails['Orders_Status'].'</span>';
                }
                else
                {
                    $label='<span class="label label-success">Rating Done</span>';
                }
        //$label = $ordersdetails['Orders_Status'];
        $timeplaced = $ordersdetails['Orders_DateTimeMade'];
        date_default_timezone_set("Asia/Kuala_Lumpur");
        $timeplaced = date('d/m/Y H:i:s', $timeplaced);
        $this->layout = 'user';
        return $this->render('orderdetails', ['ordersdetails'=>$ordersdetails, 'orderitemdetails'=>$orderitemdetails, 'did'=>$did, 'subtotal'=>$subtotal, 'deliverycharge'=>$deliverycharge, 
                             'totalprice'=>$totalprice, 'date'=>$date, 'time'=>$time, 'address'=>$address, 'paymethod'=>$paymethod, 'label'=>$label, 'timeplaced'=>$timeplaced]);
    }

    public function actionRestaurantOrders($rid)
    {
        $foodid = Food::find()->where('Restaurant_ID = :rid', [':rid'=>$rid])->all();

        $restaurantname = Restaurant::find()->where('Restaurant_ID = :rid', [':rid'=>$rid])->one();

        $deliveryid = "SELECT DISTINCT orderitem.Delivery_ID FROM orderitem INNER JOIN food ON orderitem.Food_ID = food.Food_ID INNER JOIN orders on orderitem.Delivery_ID = orders.Delivery_ID WHERE food.Restaurant_ID = ".$restaurantname['Restaurant_ID']." AND orders.Orders_Status != 'Not Placed' AND orders.Orders_Status != 'Completed' AND orders.Orders_Status != 'Rating Done' ORDER BY orderitem.Delivery_ID";
        $result = Yii::$app->db->createCommand($deliveryid)->queryAll();
        $staff = Rmanagerlevel::find()->where('User_Username = :uname and Restaurant_ID = :id', [':uname'=>Yii::$app->user->identity->username, ':id'=>$rid])->one();

        return $this->render('restaurantorders', ['rid'=>$rid, 'foodid'=>$foodid, 'restaurantname'=>$restaurantname, 'result'=>$result, 'staff'=>$staff]);
    }

    public function actionDeliverymanOrders()
    {
        $dman = Orders::find()->where('Orders_DeliveryMan = :dman and Orders_Status != :status and Orders_Status != :status1', [':dman'=>Yii::$app->user->identity->username, ':status'=>'Completed', ':status1'=>'Rating Done'])->orderBy(['Delivery_ID'=>SORT_ASC])->all();

        return $this->render('deliverymanorder', ['dman'=>$dman]);
    }

    public function actionUpdatePreparing($oid, $rid)
    {
        $sql = "UPDATE orderitem SET OrderItem_Status = 'Preparing' WHERE Order_ID = ".$oid."";
        Yii::$app->db->createCommand($sql)->execute();

        $order = OrderItem::find()->where('Order_ID = :oid', [':oid'=>$oid])->one();
        $did = $order['Delivery_ID'];

        $status = Orders::find()->where('Delivery_ID = :did', [':did'=>$did])->one();
        if ($status['Orders_Status'] == 'Pending')
        {
            $sql1 = "UPDATE orders SET Orders_Status = 'Preparing' WHERE Delivery_ID = ".$did."";
            Yii::$app->db->createCommand($sql1)->execute();
            $time = time();
            $sql3 = "UPDATE ordersstatuschange SET OChange_PreparingDateTime = ".$time." WHERE Delivery_ID = ".$did."";
            Yii::$app->db->createCommand($sql3)->execute();
        }

        $time = time();
        $sql2 = "UPDATE orderitemstatuschange SET Change_PreparingDateTime = ".$time." WHERE Order_ID = ".$oid."";
        Yii::$app->db->createCommand($sql2)->execute();
        NotificationController::createNotification($oid,2);
        NotificationController::createNotification($oid,3);
        return $this->redirect(['restaurant-orders', 'rid'=>$rid]);
    }

    public function actionUpdateReadyforpickup($oid, $rid)
    {
        $sql = "UPDATE orderitem SET OrderItem_Status = 'Ready For Pick Up' WHERE Order_ID = ".$oid."";
        Yii::$app->db->createCommand($sql)->execute();

        $time = time();
        $sql2 = "UPDATE orderitemstatuschange SET Change_ReadyForPickUpDateTime = ".$time." WHERE Order_ID = ".$oid."";

        Yii::$app->db->createCommand($sql2)->execute();

        return $this->redirect(['restaurant-orders', 'rid'=>$rid]);
    }

    public function actionUpdatePickedup($oid, $did)
    {
        $sql = "UPDATE orderitem SET OrderItem_Status = 'Picked Up' WHERE Order_ID = ".$oid."";
        Yii::$app->db->createCommand($sql)->execute();

        $status = Orders::find()->where('Delivery_ID = :did', [':did'=>$did])->one();
        if ($status['Orders_Status'] == 'Preparing')
        {
            $sql1 = "UPDATE orders SET Orders_Status = 'Pick Up in Process' WHERE Delivery_ID = ".$did."";
            Yii::$app->db->createCommand($sql1)->execute();
            $time = time();
            $sql3 = "UPDATE ordersstatuschange SET OChange_PickUpInProcessDateTime = ".$time." WHERE Delivery_ID = ".$did."";
            Yii::$app->db->createCommand($sql3)->execute();
        }

        $time = time();
        $sql2 = "UPDATE orderitemstatuschange SET Change_PickedUpDateTime = ".$time." WHERE Order_ID = ".$oid."";
        Yii::$app->db->createCommand($sql2)->execute();

        $result = "SELECT * FROM orderitem WHERE Delivery_ID = ".$did."";
        $results = Yii::$app->db->createCommand($result)->execute();

        $result1 = "SELECT * FROM orderitem WHERE Delivery_ID = ".$did." AND OrderItem_Status = 'Picked Up'";
        $results1 = Yii::$app->db->createCommand($result1)->execute();
        //var_dump($results1);exit;
        if ($results == $results1)
        {

            $sql10 = "UPDATE orders SET Orders_Status = 'On The Way' WHERE Delivery_ID = ".$did."";

            Yii::$app->db->createCommand($sql10)->execute();
            NotificationController::createNotification($did,4);
            $time1 = time();
            $sql11 = "UPDATE ordersstatuschange SET OChange_OnTheWayDateTime = ".$time1." WHERE Delivery_ID = ".$did."";
            Yii::$app->db->createCommand($sql11)->execute();
        }
        return $this->redirect(['deliveryman-orders']);
    }

    public function actionUpdateCompleted($oid, $did)
    {
        $sql = "UPDATE orders SET Orders_Status = 'Completed' WHERE Delivery_ID = ".$did."";
        Yii::$app->db->createCommand($sql)->execute();

        $time = time();
        $sql3 = "UPDATE ordersstatuschange SET OChange_CompletedDateTime = ".$time." WHERE Delivery_ID = ".$did."";
        Yii::$app->db->createCommand($sql3)->execute();
        NotificationController::createNotification($did,4);

// This calculates the restaurant's earning in the whole order
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

    public function actionInvoicePdf($did)
    {
        $ordersdetails = Orders::find()->where('Delivery_ID = :did', [':did'=>$did])->one();
        $orderitemdetails = Orderitem::find()->where('Delivery_ID = :did', [':did'=>$did])->all();
        
        $pdf = new Pdf([
            'mode' => Pdf::MODE_UTF8,
            'content' => $this->renderPartial('orderhistorydetails',['orderitemdetails' => $orderitemdetails ,'did'=>$did]),
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

    public function actionRestaurantOrderHistory($rid)
    {
        $foodid = Food::find()->where('Restaurant_ID = :rid', [':rid'=>$rid])->all();
        
        $restaurantname = Restaurant::find()->where('Restaurant_ID = :rid', [':rid'=>$rid])->one();

        $deliveryid = "SELECT DISTINCT orderitem.Delivery_ID FROM orderitem INNER JOIN food ON orderitem.Food_ID = food.Food_ID INNER JOIN orders on orderitem.Delivery_ID = orders.Delivery_ID WHERE food.Restaurant_ID = ".$restaurantname['Restaurant_ID']." AND orders.Orders_Status = 'Completed' OR orders.Orders_Status = 'Rating Done' ORDER BY orderitem.Delivery_ID";
        $result = Yii::$app->db->createCommand($deliveryid)->queryAll();
        $staff = Rmanagerlevel::find()->where('User_Username = :uname and Restaurant_ID = :id', [':uname'=>Yii::$app->user->identity->username, ':id'=>$rid])->one();


        return $this->render('restaurantorderhistory', ['rid'=>$rid, 'foodid'=>$foodid, 'restaurantname'=>$restaurantname, 'result'=>$result, 'staff'=>$staff]);
    }

    public function actionDeliverymanOrderHistory()
    {
        $dman = Orders::find()->where('Orders_DeliveryMan = :dman and Orders_Status = :status or Orders_status = :status2', [':dman'=>Yii::$app->user->identity->username, ':status'=>'Completed', ':status2'=>'Rating Done'])->orderBy(['Delivery_ID'=>SORT_ASC])->all();

        return $this->render('deliverymanorderhistory', ['dman'=>$dman]);
    }

    public function actionMyOrderHistory()
    {
        $orders = Orders::find()->where('User_Username = :uname and Orders_Status = :status', [':uname'=>Yii::$app->user->identity->username, ':status'=>'Rating Done'])->orderBy(['Delivery_ID'=>SORT_ASC])->all();
        $this->layout = 'user';

        return $this->render('myordershistory', ['orders'=>$orders]);
    }
}