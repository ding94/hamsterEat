<?php

namespace frontend\controllers;
use common\models\Orders;
use Yii;
use yii\web\Controller;
use common\models\Orderitem;
use common\models\food\Food;
use common\models\Restaurant;
use kartik\mpdf\Pdf;

class OrderController extends \yii\web\Controller
{
    public function actionMyOrders()
    {
        $orders = Orders::find()->where('User_Username = :uname and Orders_Status != :status', [':uname'=>Yii::$app->user->identity->username, ':status'=>'Not Placed'])->all();
        $this->layout = 'user';

        return $this->render('myorders', ['orders'=>$orders]);
    }

    public function actionMyOrdersHistory()
    {
        $orders = Orders::find()->where('User_Username = :uname and Orders_Status = :status', [':uname'=>Yii::$app->user->identity->username, ':status'=>'Rating Done'])->all();
        $this->layout = 'user';

        return $this->render('myordershistory', ['orders'=>$orders]);
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
        $status = $ordersdetails['Orders_Status'];
        $timeplaced = $ordersdetails['Orders_DateTimeMade'];
        date_default_timezone_set("Asia/Kuala_Lumpur");
        $timeplaced = date('d/m/Y H:i:s', $timeplaced);

        return $this->render('orderdetails', ['ordersdetails'=>$ordersdetails, 'orderitemdetails'=>$orderitemdetails, 'did'=>$did, 'subtotal'=>$subtotal, 'deliverycharge'=>$deliverycharge, 
                             'totalprice'=>$totalprice, 'date'=>$date, 'time'=>$time, 'address'=>$address, 'paymethod'=>$paymethod, 'status'=>$status, 'timeplaced'=>$timeplaced]);
    }

    public function actionOrderHistoryDetails($did)
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
        $status = $ordersdetails['Orders_Status'];
        $timeplaced = $ordersdetails['Orders_DateTimeMade'];
        date_default_timezone_set("Asia/Kuala_Lumpur");
        $timeplaced = date('d/m/Y H:i:s', $timeplaced);

        return $this->render('orderhistorydetails', ['ordersdetails'=>$ordersdetails, 'orderitemdetails'=>$orderitemdetails, 'did'=>$did, 'subtotal'=>$subtotal, 'deliverycharge'=>$deliverycharge, 
                             'totalprice'=>$totalprice, 'date'=>$date, 'time'=>$time, 'address'=>$address, 'paymethod'=>$paymethod, 'status'=>$status, 'timeplaced'=>$timeplaced]);
    }

    public function actionRestaurantOrders($rid)
    {
        $foodid = Food::find()->where('Restaurant_ID = :rid', [':rid'=>$rid])->all();

        $restaurantname = Restaurant::find()->where('Restaurant_ID = :rid', [':rid'=>$rid])->one();

        $deliveryid = "SELECT DISTINCT orderitem.Delivery_ID FROM orderitem INNER JOIN food ON orderitem.Food_ID = food.Food_ID INNER JOIN orders on orderitem.Delivery_ID = orders.Delivery_ID WHERE food.Restaurant_ID = ".$restaurantname['Restaurant_ID']." AND orders.Orders_Status != 'Not Placed' ORDER BY orderitem.Delivery_ID";
        $result = Yii::$app->db->createCommand($deliveryid)->queryAll();

        return $this->render('restaurantorders', ['rid'=>$rid, 'foodid'=>$foodid, 'restaurantname'=>$restaurantname, 'result'=>$result]);
    }

    public function actionDeliverymanOrders()
    {
        $dman = Orders::find()->where('Orders_DeliveryMan = :dman and Orders_Status != :status', [':dman'=>Yii::$app->user->identity->username, ':status'=>'Completed'])->all();

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

        return $this->redirect(['deliveryman-orders']);
    }

    public function actionInvoicePdf($did)
    {
        $ordersdetails = Orders::find()->where('Delivery_ID = :did', [':did'=>$did])->one();
        $orderitemdetails = Orderitem::find()->where('Delivery_ID = :did', [':did'=>$did])->all();
        
        $pdf = new Pdf([
            'mode' => Pdf::MODE_CORE,
            'content' => $this->render('orderhistorydetails',['orderitemdetails' => $orderitemdetails ,'did'=>$did]),
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
}