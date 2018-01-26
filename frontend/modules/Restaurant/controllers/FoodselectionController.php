<?php

namespace frontend\modules\Restaurant\controllers;

use yii;
use yii\web\Controller;
use yii\helpers\ArrayHelper;
use frontend\controllers\CartController;
use frontend\controllers\DiscountController;
use frontend\controllers\PaymentController;
use common\models\food\Foodselection;
use common\models\food\Foodselectiontype;
use common\models\vouchers\VouchersUsed;
use common\models\vouchers\Vouchers;
use common\models\Order\Orders;
use common\models\food\Food;
use common\models\User;
use common\models\problem\ProblemOrder;

class FoodselectionController extends Controller
{
	public static function validatefoodselection($post)
	{
		foreach ($post as $i => $foodtypes) {
            foreach ($foodtypes as $ix => $foodselections) {
                $data['Foodselection'] = $foodselections;
                $modelfoodselection = new Foodselection;
                $modelfoodselection->load($data);
                $foodselection[$i][$ix] = $modelfoodselection;
                $valid = $modelfoodselection->validate();
            }
        }
        return $foodselection;
	}

	public static function createfoodselection($foodtype,$foodselection,$id)
	{

		foreach ($foodtype as $i => $modelfoodtype) {

            $modelfoodtype->Food_ID = $id;
                                
            if (!($flag = $modelfoodtype->save(false))) {
                                    
                return false;
            }

            if (isset($foodselection[$i]) && is_array($foodselection[$i])) 
            {
                foreach ($foodselection[$i] as $ix => $modelfoodselection) {
                    $modelfoodselection->Type_ID = $modelfoodtype->ID;
                    $modelfoodselection->Food_ID = $id;
                    
                    $modelfoodselection->Price = CartController::actionDisplay2decimal($modelfoodselection->Price);
                    $modelfoodselection->BeforeMarkedUp =  CartController::actionRoundoff1decimal($modelfoodselection->Price / 1.3);;
                   
                    if (!($flag = $modelfoodselection->save(false))) {
                        return false;
                    }
                }
            }
        }
        return true;
	}

    /*
    * get old food selection data or selection data
    */
    public static function oldData($data,$type)
    {
        $oldSelect = [];
        $modelSelect = [];
        foreach ($data as $i => $select) 
        {
            $foodSelection = $select->foodSelection;
            $modelSelect[$i] = $foodSelection;
            $oldSelect = ArrayHelper::merge(ArrayHelper::index($foodSelection, 'ID'), $oldSelect);
        }
        switch ($type) {
            case 1:
                return $modelSelect;
                break;
            case 2:
                 return $oldSelect;
            default:
                return false;
                break;
        }
       
    }

    /*
    * reverse back the remove order and calculate the price again
    */
    public static function selectionCancel($value)
    {    
        $did = $value->Delivery_ID;

        $reason = new ProblemOrder;
        $reason->load(Yii::$app->request->post());
        $reason->Order_ID = $value->Order_ID;
        $reason->Delivery_ID = $did;
        $reason['status'] = 1;
        $reason['datetime'] = time();

        $order = self::findOrder($did,$value);

        if($order->Orders_DiscountEarlyAmount > 0 )
        {
           $order->Orders_DiscountEarlyAmount = CartController::actionRoundoff1decimal($order->Orders_Subtotal * 0.15);
         
        }

        if($order->Orders_DiscountTotalAmount > 0)
        {
            $user = User::find()->where('username = :n',[':n'=>$order->User_Username])->one();
                
            $vused = VouchersUsed::find()->where('uid = :uid and did = :did',[':uid'=>$user->id,':did'=>$value->Delivery_ID])->one();
            
            $code = Vouchers::findOne($vused->vid)->code;

            $data = DiscountController::orderdiscount($code,$order);
            $order = $data['data'];         
        }
      
        $order->Orders_TotalPrice =  $order->Orders_Subtotal + $order->Orders_DeliveryCharge - $order->Orders_DiscountEarlyAmount - $order->Orders_DiscountTotalAmount; 
       
        $value['OrderItem_Status'] = 8;
        $oldOrder = $order->getOldAttributes();
        if($order->Orders_PaymentMethod == 'Account Balance')
        {
            $refundPrice =  $oldOrder['Orders_TotalPrice'] - $order['Orders_TotalPrice'];
            $reason->refund =  $refundPrice;
            $acc = PaymentController::refund($refundPrice,$order->User_Username,$did,7);
            $value['OrderItem_Status'] = 9;
        }

        $isValid = $value->validate() && $order->validate() && $reason->validate();

        if(!empty($acc))
        {
           $isValid = $acc->validate() && $isValid;
        }

        if($isValid)
        {
            $value->save();
            $order->save();
            $reason->save();
            if(!empty($acc))
            {
               $acc->save();
            }
            return $isValid;
        }
        else
        {
            return $isValid;
        }
      
        //$order->Orders_TotalPrice -= $value->OrderItem_LineTotal;
      
        //$diffPrice = $order->Orders_TotalPrice  -  $order->Orders_Subtotal;
       // $test =  $order->Orders_Subtotal * 1.15;
        //$test1 =  ($order->Orders_Subtotal * 0.15) +$order->Orders_Subtotal ;
        
        
    }

    /*
    * find order and calculate the per resturant per delivery charge
    */
    protected function findOrder($did,$value)
    {
        $order = Orders::find()->where('orders.Delivery_ID = :did',[':did'=>$did])->joinWith(['item'])->one();
        //$item = Orderitem::find();
       
        foreach($order->item as $item)
        {

            $food = Food::findOne($item->Food_ID);
            $totalRestaurant[$food->Restaurant_ID][$item->Order_ID] = $item->Order_ID;
        }

        $unsetData = Food::findOne($value->Food_ID)->Restaurant_ID;
        unset($totalRestaurant[$unsetData][$value->Order_ID]);

        if(empty($totalRestaurant[$unsetData]))
        {
            $order->Orders_DeliveryCharge -= 5;
            if($order->Orders_TotalPrice > 0)
            {
                $order->Orders_TotalPrice -= 5;
            }
        }

        $order->Orders_Subtotal -= $value->OrderItem_LineTotal;
        return $order;
    }

}
