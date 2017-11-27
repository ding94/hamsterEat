<?php
namespace frontend\controllers;

use Yii;
use yii\web\Controller;
use yii\filters\VerbFilter;
use yii\helpers\ArrayHelper;
use yii\filters\AccessControl;
use common\models\Vouchers;
use common\models\UserVoucher;


class DiscountController extends Controller
{
	//price after discount
	public static function discount($post,$price)
	{
		$voucher = Vouchers::find()->where('id =:id',[':id'=>$post])->one();

		if (($voucher['discount_type'] >= 1 && $voucher['discount_type']<= 3) || $voucher['discount_type'] == 100)  {
			$price = $price * ((100 - $voucher['discount']) / 100);
		}
		elseif ($voucher['discount_type'] >= 4 && $voucher['discount_type'] <= 6 || $voucher['discount_type'] == 101) {
			$price = $price - $voucher['discount'];
		}

		return $price;
	}

	//discounted amount
	public static function reversediscount($post,$price)
	{
		$voucher = Vouchers::find()->where('id =:id',[':id'=>$post])->one();
		
		if (($voucher['discount_type'] >= 1 && $voucher['discount_type']<= 3) || $voucher['discount_type'] == 100)  {
			$price = (($price*$voucher['discount']) / 100);
		}
		elseif ($voucher['discount_type'] >= 4 && $voucher['discount_type'] <= 6 || $voucher['discount_type'] == 101) {
			if ($voucher['discount'] >= $price) {
				$price = $price;
			}
			else
			{
				$price = $voucher['discount'];
			}
			
		}

		return $price;
	}

	public static function orderdiscount($code,$order)
	{
		$uservoucher = UserVoucher::find()->where('code=:c',[':c'=>$code])->one();
        $voucher = Vouchers::find()->where('code=:c',[':c'=>$code])->all();
        
        /* Validations (user and date) */
        $valid = ValidController::DateValidCheck($code,1);
        if ($voucher[0]['discount_type'] == 100 || $voucher[0]['discount_type']== 101) {
            $valid = true;
        }
        
        if($valid == false){
            if ($uservoucher['uid'] != Yii::$app->user->identity->id) {
                Yii::$app->session->setFlash('error', 'Coupon cannot be used.');
                return false;
            }
            elseif ($uservoucher['uid'] == Yii::$app->user->identity->id){
                Yii::$app->session->setFlash('error', 'Coupon cannot be used again.');
                return false;
            }
        }

		/* discounttotal make back 0, do discounts */
		$order['Orders_DiscountTotalAmount'] = 0 ;
		//might faced coupon with multiple function, use loop
		foreach ($voucher as $k => $vou) 
		{
			if ($order['Orders_TotalPrice'] > 0) 
			{
				if ($vou['discount_type'] == 2 || $vou['discount_type'] == 100)  
                {
                	switch ($vou['discount_item']) 
                    {
                        case 7:
                            $order['Orders_DiscountTotalAmount'] += ($order['Orders_Subtotal']* ($vou['discount'] / 100));
                            $order['Orders_Subtotal'] = $order['Orders_Subtotal']- ($order['Orders_Subtotal']* ($vou['discount'] / 100));
                            $order['Orders_TotalPrice'] =  $order['Orders_Subtotal'] + $order['Orders_DeliveryCharge'];
                            break;

                        case 8:
                            $order['Orders_DiscountTotalAmount'] += ($order['Orders_DeliveryCharge']* ($vou['discount'] / 100));
                            $order['Orders_DeliveryCharge'] = $order['Orders_DeliveryCharge']-($order['Orders_DeliveryCharge']*($vou['discount'] / 100));
                            $order['Orders_TotalPrice'] =  $order['Orders_Subtotal'] + $order['Orders_DeliveryCharge'];
                            break;

                        case 9:
                        	$order['Orders_TotalPrice'] =  $order['Orders_Subtotal'] + $order['Orders_DeliveryCharge'];
                            $order['Orders_DiscountTotalAmount'] += ($order['Orders_TotalPrice']* ($vou['discount'] / 100));
                            $order['Orders_TotalPrice'] = $order['Orders_TotalPrice'] - ($order['Orders_TotalPrice']*($vou['discount'] / 100));
                            break;
                                     
                        default:
                        	Yii::$app->session->setFlash('error', 'error.');
                            return false;
                            break;
                    }
            	}
            	elseif ($vou['discount_type'] == 5 || $vou['discount_type'] == 101) 
                {
                    switch ($vou['discount_item']) 
                    {
                        case 7:
                            if (($order['Orders_Subtotal']-$vou['discount']) < 0) {
                                $order['Orders_DiscountTotalAmount'] += $order['Orders_Subtotal'];
                                $order['Orders_Subtotal'] = 0;
                            }
                            else{
                                $order['Orders_DiscountTotalAmount'] += $vou['discount'];
                                $order['Orders_Subtotal'] = $order['Orders_Subtotal'] - $vou['discount'];
                            }

                            $order['Orders_TotalPrice'] =  $order['Orders_Subtotal'] + $order['Orders_DeliveryCharge'];
                            break;

                        case 8:
                            if (($order['Orders_DeliveryCharge']-$vou['discount']) < 0) {
                                $order['Orders_DiscountTotalAmount'] += $order['Orders_DeliveryCharge'];
                                $order['Orders_DeliveryCharge'] = 0;
                            }
                            else{
                                $order['Orders_DiscountTotalAmount'] += $vou['discount'];
                                $order['Orders_DeliveryCharge'] = $order['Orders_DeliveryCharge'] - $vou['discount'];
                            }
                            $order['Orders_TotalPrice'] =  $order['Orders_Subtotal'] + $order['Orders_DeliveryCharge'];
                            break;

                        case 9:
                        	$order['Orders_TotalPrice'] =  $order['Orders_Subtotal'] + $order['Orders_DeliveryCharge'];
                            if (($order['Orders_TotalPrice']-$vou['discount']) < 0) {
                                $order['Orders_DiscountTotalAmount'] += $order['Orders_TotalPrice'];
                                $order['Orders_TotalPrice'] = 0;
                            }
                            else{
                                $order['Orders_DiscountTotalAmount'] += $vou['discount'];
                                $order['Orders_TotalPrice'] = $order['Orders_TotalPrice'] - $vou['discount'];
                            }
                            break;
                                     
                        default:
                            Yii::$app->session->setFlash('error', 'error.');
                            return false;
                            break;
                    }
                }
            	else
            	{
            		Yii::$app->session->setFlash('error', 'Coupon was used.');
					return false;
            	}
            	//save voucher status
            	VouchersController::endvoucher($code);
			}
		}

        $order['Orders_Subtotal'] = number_format($order['Orders_Subtotal'],2);
        $order['Orders_DeliveryCharge']= number_format($order['Orders_DeliveryCharge'],2);
        $order['Orders_DiscountTotalAmount']= number_format($order['Orders_DiscountTotalAmount'],2);
        $order['Orders_TotalPrice']= number_format($order['Orders_TotalPrice'],2);
        
		return $order;
	}
}