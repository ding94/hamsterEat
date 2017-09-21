<?php
namespace frontend\controllers;
use Yii;
use yii\web\Controller;
use common\models\Orderitem;
use common\models\User;
use common\models\food;
use common\models\Orders;
use common\models\Orderitemselection;


class CartController extends Controller
{
    public function actionAddtoCart($Food_ID,$quantity,$selection,$foodtypeid)
    {
        $session = Yii::$app->session;
        $cart = orders::find()->where('User_Username = :uname',[':uname'=>Yii::$app->user->identity->username])->andwhere('Orders_Status = :status',[':status'=>'Not Placed'])->one();

        if (empty($cart))
        {
            $newcart = new Orders;

            $newcart->User_Username = Yii::$app->user->identity->username;
            $newcart->Orders_Status = 'Not Placed';

            $newcart->save();
            $cart = orders::find()->where('User_Username = :uname',[':uname'=>Yii::$app->user->identity->username])->andwhere('Orders_Status = :status',[':status'=>'Not Placed'])->one();
        }

        $orderitem = new Orderitem;
        $orderitemselection = new orderitemselection;

        $findfood = food::find()->where('Food_ID = :fid', [':fid'=>$Food_ID])->one();
        $findfoodprice = $findfood['Food_Price'];
        $orderitem->Delivery_ID = $cart['Delivery_ID'];
        $orderitem->Food_ID = $Food_ID;
        $orderitem->OrderItem_Quantity = $quantity;
        $orderitem->OrderItem_LineTotal = $findfoodprice * $quantity;
        $orderitem->OrderItem_Status = 'Not Placed';
        $orderitem->save();

        $items = Orderitem::find()->where('Delivery_ID = :did',[':did'=>$cart['Delivery_ID']])->all();
        $i = 0;
        $linetotal = 0;
        while ($i < count($items))
        {
            $linetotal = $items[$i]['OrderItem_LineTotal'] + $linetotal;
            $i = $i + 1;
        }

        $noofrestaurants = "SELECT DISTINCT food.Restaurant_ID FROM food INNER JOIN orderitem ON orderitem.Food_ID = food.Food_ID WHERE orderitem.Delivery_ID = ".$cart['Delivery_ID']."";
        $result = Yii::$app->db->createCommand($noofrestaurants)->execute();
        $deliverycharge = $result * 5;

        $totalcharge = $deliverycharge + $linetotal;

        $sql = "UPDATE orders SET Orders_SubTotal = ".$linetotal.", Orders_DeliveryCharge = ".$deliverycharge.", Orders_TotalPrice = ".$totalcharge." WHERE Delivery_ID = ".$cart['Delivery_ID']."";
        Yii::$app->db->createCommand($sql)->execute();

        return $this->redirect(['view-cart', 'deliveryid'=>$cart['Delivery_ID']]);
    }

    public function actionViewCart($deliveryid)
    {
        $cartitems = Orderitem::find()->where('Delivery_ID = :did',[':did'=>$deliveryid])->all();

        return $this->render('cart', ['deliveryid'=>$deliveryid, 'cartitems'=>$cartitems]);
    }
}