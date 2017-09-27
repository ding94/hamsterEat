<?php
namespace frontend\controllers;
use Yii;
use yii\web\Controller;
use common\models\Orderitem;
use common\models\User;
use common\models\Food;
use common\models\Orders;
use common\models\Orderitemselection;
use common\models\Foodtype;
use common\models\Foodselection;
use common\models\user\Userdetails;


class CartController extends Controller
{
    public function actionAddtoCart($Food_ID,$quantity,$finalselected)
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

        $findfood = Food::find()->where('Food_ID = :fid', [':fid'=>$Food_ID])->one();
        $findfoodprice = $findfood['Food_Price'];
        $orderitem->Delivery_ID = $cart['Delivery_ID'];
        $orderitem->Food_ID = $Food_ID;
        $orderitem->OrderItem_Quantity = $quantity;
        $linetotal = $findfoodprice * $quantity;
        $orderitem->OrderItem_LineTotal = $linetotal;
        $orderitem->OrderItem_Status = 'Not Placed';
        $orderitem->save();

        $findorderid = Orderitem::find()->where('Delivery_ID = :did',[':did'=>$cart['Delivery_ID']])->all();
        $oid = 0;
        foreach ($findorderid as $orderid) :
            if ($orderid['Order_ID'] > $oid)
            {
                $oid = $orderid['Order_ID'];
            }
        endforeach;

        $selected = explode(',', $finalselected);
        $selectionprice = 0;
        $selectiontotalprice = 0;
        foreach ($selected as $selected2) :
            $orderitemselection = new Orderitemselection;
            $orderitemselection->Order_ID = $oid;
            $orderitemselection->Selection_ID = $selected2;
            $foodtypeid = Foodselection::find()->where('Selection_ID = :sid',[':sid'=>$selected2])->one();
            $foodtypeid = $foodtypeid['FoodType_ID'];
            $orderitemselection->FoodType_ID = $foodtypeid;
            $foodselectionprice = Foodselection::find()->where('Selection_ID = :sid',[':sid'=>$selected2])->one();
            $selectiontotalprice = $selectiontotalprice + $foodselectionprice['Selection_Price'];
            $orderitemselection->save();
        endforeach;
        $selectiontotalprice = $selectiontotalprice * $quantity;
        $linetotal = $linetotal + $selectiontotalprice;
        $linetotalupdate = "UPDATE orderitem SET OrderItem_LineTotal = ".$linetotal.", OrderItem_SelectionTotal = ".$selectiontotalprice." WHERE Order_ID = ".$oid."";
        Yii::$app->db->createCommand($linetotalupdate)->execute();

        $items = Orderitem::find()->where('Delivery_ID = :did',[':did'=>$cart['Delivery_ID']])->all();
        $i = 0;
        $subtotal = 0;
        while ($i < count($items))
        {
            $subtotal = $items[$i]['OrderItem_LineTotal'] + $subtotal;
            $i = $i + 1;
        }

        $noofrestaurants = "SELECT DISTINCT food.Restaurant_ID FROM food INNER JOIN orderitem ON orderitem.Food_ID = food.Food_ID WHERE orderitem.Delivery_ID = ".$cart['Delivery_ID']."";
        $result = Yii::$app->db->createCommand($noofrestaurants)->execute();
        $deliverycharge = $result * 5;

        $totalcharge = $deliverycharge + $subtotal;

        $sql = "UPDATE orders SET Orders_SubTotal = ".$subtotal.", Orders_DeliveryCharge = ".$deliverycharge.", Orders_TotalPrice = ".$totalcharge." WHERE Delivery_ID = ".$cart['Delivery_ID']."";
        Yii::$app->db->createCommand($sql)->execute();

        return $this->redirect(['view-cart', 'deliveryid'=>$cart['Delivery_ID']]);
    }

    public function actionViewCart($deliveryid)
    {
        $cartitems = Orderitem::find()->where('Delivery_ID = :did',[':did'=>$deliveryid])->all();

        return $this->render('cart', ['deliveryid'=>$deliveryid, 'cartitems'=>$cartitems]);
    }

    public function actionCheckout($did)
    {
        $mycontact = Userdetails::find()->where('User_Username = :uname',[':uname'=>Yii::$app->user->identity->username])->one();
        $mycontactno = $mycontact['User_ContactNo'];
        $myemail = User::find()->where('username = :username',[':username'=>Yii::$app->user->identity->username])->one();
        $myemail = $myemail['email'];
        $fullname = $mycontact['User_FirstName'].' '.$mycontact['User_LastName'];
        //var_dump($fullname);exit;
        $checkout = new Orders;
        $session = Yii::$app->session;

        if ($checkout->load(Yii::$app->request->post()))
        {
            $unitno = $checkout->Orders_Location;
            $street = $checkout->Orders_Area;
            $paymethod = $checkout->Orders_PaymentMethod;

            $location = $unitno.', '.$street;
            $time = time();

            date_default_timezone_set("Asia/Kuala_Lumpur");
            $setdate = date("Y-m-d");
            $settime = "13:00:00";

            $sql = "UPDATE orders SET Orders_Location= '".$location."', Orders_Area = '".$session['area']."', Orders_Postcode = ".$session['postcode'].", Orders_PaymentMethod = '".$paymethod."', Orders_Status = 'Pending', Orders_DateTimeMade = ".$time.", Orders_Date = '".$setdate."', Orders_Time = '".$settime."' WHERE Delivery_ID = ".$did."";
            Yii::$app->db->createCommand($sql)->execute();
            $sql2 = "UPDATE orderitem SET OrderItem_Status = 'Pending' WHERE Delivery_ID = '".$did."'";
            Yii::$app->db->createCommand($sql2)->execute();

            $timedate = Orders::find()->where('Delivery_ID = :did', [':did'=>$did])->one();
            return $this->render('aftercheckout', ['did'=>$did, 'timedate'=>$timedate]);
        }
        return $this->render('checkout', ['did'=>$did, 'mycontactno'=>$mycontactno, 'myemail'=>$myemail, 'fullname'=>$fullname, 'checkout'=>$checkout, 'session'=>$session]);
    }

    public function actionAssignDeliveryMan()
    {
        $sql= User::find()->innerJoinWith('auth_assignment','user.id = auth_assignment.user_id')->andWhere(['auth_assignment.item_name'=>'rider'])->innerJoinWith('deliveryman','user.id = deliveryman.User_id')->all();
        var_dump($sql);exit;
    }

}