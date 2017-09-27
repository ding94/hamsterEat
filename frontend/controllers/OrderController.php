<?php

namespace frontend\controllers;
use common\models\Orders;
use Yii;
use yii\web\Controller;
use common\models\Orderitem;

class OrderController extends \yii\web\Controller
{
    public function actionMyOrders()
    {
        $orders = Orders::find()->where('User_Username = :uname and Orders_Status != :status', [':uname'=>Yii::$app->user->identity->username, ':status'=>'Not Placed'])->all();
        
        return $this->render('myorders', ['orders'=>$orders]);
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
        var_dump($time);exit;

        return $this->render('orderdetails', ['ordersdetails'=>$ordersdetails, 'orderitemdetails'=>$orderitemdetails, 'did'=>$did, 'subtotal'=>$subtotal, 'deliverycharge'=>$deliverycharge, 
                             'totalprice'=>$totalprice, 'date'=>$date, 'time'=>$time, 'address'=>$address, 'paymethod'=>$paymethod, 'status'=>$status, 'timeplaced'=>$timeplaced]);
    }

}
