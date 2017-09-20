<?php
namespace frontend\controllers;
use Yii;
use yii\web\Controller;
use common\models\Orderitem;
use common\models\User;
use common\models\food;
use common\models\Orders;

class CartController extends Controller
{
    public function actionAddtoCart($Food_ID,$quantity)
    {
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
        $findfood = food::find()->where('Food_ID = :fid', [':fid'=>$Food_ID])->one();
        $findfoodprice = $findfood['Food_Price'];
        $orderitem->Delivery_ID = $cart['Delivery_ID'];
        $orderitem->Food_ID = $Food_ID;
        $orderitem->OrderItem_Quantity = $quantity;
        $orderitem->OrderItem_LineTotal = $findfoodprice * $quantity;
        $orderitem->OrderItem_Status = 'Not Placed';
        $orderitem->save();
        var_dump('a');exit;
    }
}