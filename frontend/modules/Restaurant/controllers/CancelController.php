<?php

namespace frontend\modules\Restaurant\controllers;

use Yii;
use yii\web\Controller;
use common\models\Order\{Orderitem,Orders};
use common\models\problem\ProblemOrder;
use common\models\food\Food;
use common\models\vouchers\{VouchersUsed,Vouchers};
use common\models\User;
use frontend\modules\offer\controllers\ReverseController;
use frontend\controllers\{CommonController,CartController,PaymentController,DiscountController};
use frontend\modules\notification\controllers\NoticController;

class CancelController extends CommonController
{
	public static function CancelOrder($id)
    {
        $item = Orderitem::find()->where('Food_ID=:id AND OrderItem_Status=:s',[':id'=>$id, ':s'=>2])->all();
      
        if (!empty($item)) 
        {
            foreach ($item as $k => $value) 
            {
                $isvalid = self::OrderorDeliveryCancel($value->Delivery_ID,$value);
                if(!$isvalid)
                {
                    return false;
                    break;
                   
                }
            }
            //use this formular for most accurate data protect
            //if (count($orderitem) == ($k+1) ) {}
        }
        Yii::$app->session->setFlash('Success', Yii::t('m-restaurant',"Status changed! Please inform customer service."));
        return true;
    }

    /*
    * detect wheater cancel order or delivery
    * base on orderitem 
    */
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

    /*
    * cancel order
    */
    public static function orderCancel($data)
    {  
        $did = $data->Delivery_ID;
        
        $reason = new ProblemOrder;
        $reason->load(Yii::$app->request->post());
        $reason->Order_ID = $data->Order_ID;
        $reason->Delivery_ID = $did;
        $reason['status'] = 1;
        $reason['datetime'] = time();

        $order = self::findOrder($did,$data);

        if($order->Orders_DiscountEarlyAmount > 0 )
        {
            $order->Orders_DiscountEarlyAmount = CartController::actionRoundoff1decimal($order->Orders_Subtotal * 0.15);
         
        }

        $user = User::find()->where('username = :n',[':n'=>$order->User_Username])->one();

        if($order->Orders_DiscountTotalAmount > 0)
        {
            $order = self::VoucherOrPromotion($order,$data,$user->id);
        }
        
        $order->Orders_TotalPrice =  $order->Orders_Subtotal + $order->Orders_DeliveryCharge - $order->Orders_DiscountEarlyAmount - $order->Orders_DiscountTotalAmount; 
       
        $data['OrderItem_Status'] = 8;
        $oldOrder = $order->getOldAttributes();
        if($order->Orders_PaymentMethod == 'Account Balance')
        {
            $refundPrice =  $oldOrder['Orders_TotalPrice'] - $order['Orders_TotalPrice'];
            $reason->refund =  $refundPrice;
            $acc = PaymentController::refund($refundPrice,$order->User_Username,$did,7);
            $data['OrderItem_Status'] = 9;
        }

        $isValid = $data->validate() && $order->validate() && $reason->validate();

        if(!empty($acc))
        {
           $isValid = $acc->validate() && $isValid;
        }
       
        if($isValid)
        {
            $data->save();
            $order->save();
            $reason->save();
            if(!empty($acc))
            {
               $acc->save();
            }
            NoticController::centerNotic(1,$data['OrderItem_Status'],$data['Order_ID'],$user->id);
            return $isValid;
        }
        else
        {
            return $isValid;
        }
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
     
        $order->Orders_Subtotal -= $value->OrderItem_LineTotal* $value->OrderItem_Quantity;
      
        return $order;
    } 

    public static function deliveryCancel($value)
    {
        $order = Orders::find()->where('Delivery_ID=:id',[':id'=>$value['Delivery_ID']])->one();
       
       /* if ($order['Orders_DateTimeMade'] > strtotime(date('Y-m-d'))) 
        {*/
            $reason = new ProblemOrder; // set new value to db, away from covering value
            $reason['reason'] = 3;
            $reason->load(Yii::$app->request->post());
            $reason['Order_ID'] = $value['Order_ID'];
            $reason['Delivery_ID'] = $value['Delivery_ID'];
            $reason['status'] = 1;
            $reason['datetime'] = time();
            $value['OrderItem_Status'] = 8;
            $order['Orders_Status'] = 8;
                    //check did user use balance to pay
            if ($order['Orders_PaymentMethod'] == 'Account Balance') {
                $reason['refund'] = $order['Orders_TotalPrice'];
                $acc = PaymentController::refund($order['Orders_TotalPrice'],$order['User_Username'],$value['Delivery_ID'],6);
                if ($acc->validate()) {
                    $acc->save();
                    $order['Orders_Status'] = 9;
                    $value['OrderItem_Status'] = 9;
                }
                else{
                    Yii::$app->session->setFlash('Warning', Yii::t('cart',"Something Went Wrong!"));
                            return false;
                    }
            }
             
            $order['Orders_Subtotal'] = 0;
            $order['Orders_TotalPrice'] = 0;
            $order['Orders_DeliveryCharge'] = 0;
            $order['Orders_DiscountEarlyAmount'] = 0;
            $order['Orders_DiscountTotalAmount'] = 0;
            
            if ($reason->validate() && $value->validate() && $order->validate()) {
                $user = User::find()->where('username = :u',[':u'=>$order->User_Username])->one();
                $reason->save();
                $value->save();
                $order->save();
                NoticController::centerNotic(2,$order['Orders_Status'],$order['Delivery_ID'],$user->id);
            }
            else
            {
                Yii::$app->session->setFlash('Warning', Yii::t('cart',"Something Went Wrong!"));
                return false;
            }
           
        
         return true;
    }
    /*
    * detect using voucher or promotion
    */
    protected static function VoucherOrPromotion($order,$data,$uid)
    {
        $vused = VouchersUsed::find()->where('uid = :uid and did = :did',[':uid'=>$uid,':did'=>$data->Delivery_ID])->one();
        if(empty($vused))
        {
            $order = ReverseController::calDiscount($order,$data,$uid);
        }
        else
        {
            $code = Vouchers::findOne($vused->vid)->code;

            $dis = DiscountController::orderdiscount($code,$order);
            $order = $dis['data']; 
        }
        return $order;
    }
}