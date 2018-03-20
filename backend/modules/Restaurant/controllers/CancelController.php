<?php

namespace backend\modules\Restaurant\controllers;

use Yii;
use yii\web\Controller;
use common\models\Order\Orders;
use common\models\Order\Orderitem;
use common\models\food\Food;
use common\models\problem\ProblemOrder;
use common\models\Account\Accountbalance;
use common\models\User;
use common\models\vouchers\VouchersUsed;
use common\models\vouchers\Vouchers;
use backend\controllers\CommonController;
use frontend\controllers\DiscountController;

Class CancelController extends Controller
{
	public static function OrderorDeliveryCancel($did,$item)
    {
        $query = Orderitem::find()->where('Delivery_ID = :did and OrderItem_Status = 2',[':did'=>$did]);
        $count = $query->count();
       
        if($count <= 1)
        { 
            $isValid = self::deliveryCancel($item);
            
        }
        else
        {
            $isValid = self::orderCancel($item);
        }
        return $isValid;
    }

	public static function DeliveryCancel($value)
	{
		$isValid = true;
		$order = Orders::findOne($value->Delivery_ID);

		$value['OrderItem_Status'] = 8;
		$order['Orders_Status'] = 8;	

		$reason = self::reason($value['Delivery_ID'],$value['Order_ID']);

        if($order->Orders_PaymentMethod == "Account Balance")
        {
        	$reason['refund'] = $order->Orders_TotalPrice;
        	$refund = self::refund($order->Orders_TotalPrice,$order,6);
        	$order['Orders_Status'] = 9;
        	$value['OrderItem_Status'] = 9;
        	$isValid = $refund->validate();
        }

        $order['Orders_Subtotal'] = 0;
        $order['Orders_TotalPrice'] = 0;
        $order['Orders_DeliveryCharge'] = 0;
        $order['Orders_DiscountEarlyAmount'] = 0;
        $order['Orders_DiscountTotalAmount'] = 0;

        $isValid = $order->validate() && $value->validate() && $reason->validate() && $isValid;
		if($isValid)
		{
			$reason->save();
            $value->save();
            $order->save();
            if(!empty($refund))
            {
            	$refund->save();
            }
			return true;
		}
		Yii::$app->session->setFlash('Warning',"Something Went Wrong!");
        return false;
	}

	public static function orderCancel($value)
	{
		$isValid = true;
		$reason = self::reason($value['Delivery_ID'],$value['Order_ID']);
		$order = self::order($value);
		
		$value['OrderItem_Status'] = 8;
        $oldOrder = $order->getOldAttributes();

        if($order->Orders_PaymentMethod == 'Account Balance')
        {
            $refundPrice =  $oldOrder['Orders_TotalPrice'] - $order['Orders_TotalPrice'];
            $reason->refund =  $refundPrice;
            $acc = self::refund($refundPrice,$order,7);
            $value['OrderItem_Status'] = 9;
            $isValid = $acc->validate();
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
         
        }

        return $isValid;
	}

	protected static function refund($refundPrice,$order,$type)
	{
		$acc = Accountbalance::find()->where('User_Username=:us',[':us'=>$order->User_Username])->one();
		$acc->type = $type;
		$acc->defaultAmount = $refundPrice;
		$acc->deliveryid = $order->Delivery_ID;

		$acc['User_Balance'] += $refundPrice;
        $acc['AB_minus'] -= $refundPrice;

        return $acc;
	}

	protected static function reason($did,$oid)
	{
		$reason = new ProblemOrder;
		$reason->reason =4;
        $reason['Order_ID'] = $oid;
        $reason['Delivery_ID'] = $did;
        $reason['status'] = 1;
        $reason['datetime'] = time();
        return $reason;
	}

	protected static function order($value)
	{
		$order = Orders::find()->where('orders.Delivery_ID = :did',[':did'=>$value->Delivery_ID])->joinWith(['item'])->one();

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

        $order = self::revertDiscount($order);
		return $order;
	}

	protected static function revertDiscount($order)
	{
		if($order->Orders_DiscountEarlyAmount > 0 )
        {
           $order->Orders_DiscountEarlyAmount = CommonController::roundoff1decimal($order->Orders_Subtotal * 0.15);
        }

        if($order->Orders_DiscountTotalAmount > 0)
        {
            $user = User::find()->where('username = :n',[':n'=>$order->User_Username])->one();
                
            $vused = VouchersUsed::find()->where('uid = :uid and did = :did',[':uid'=>$user->id,':did'=>$order->Delivery_ID])->one();
            
            $code = Vouchers::findOne($vused->vid)->code;

            $data = DiscountController::orderdiscount($code,$order);
            $order = $data['data'];         
        }
      
        $order->Orders_TotalPrice =  $order->Orders_Subtotal + $order->Orders_DeliveryCharge - $order->Orders_DiscountEarlyAmount - $order->Orders_DiscountTotalAmount; 

        return $order;
	}
}