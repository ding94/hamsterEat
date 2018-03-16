<?php
namespace frontend\controllers;

use Yii;
use yii\web\Controller;
use yii\filters\VerbFilter;
use yii\helpers\ArrayHelper;
use yii\filters\AccessControl;
use common\models\vouchers\Vouchers;
use common\models\vouchers\UserVoucher;
use common\models\vouchers\VouchersUsed;
use common\models\vouchers\VouchersSetCondition;
use common\models\vouchers\VouchersConditions;


class DiscountController extends Controller
{
	//price after discount
	public static function discount($post,$price)
	{
		$voucher = Vouchers::find()->where('id =:id',[':id'=>$post])->one();

		if ($voucher['discount_type'] == 1)  {
			$price = $price * ((100 - $voucher['discount']) / 100);
		}
		elseif ($voucher['discount_type'] == 2 ) {
			$price = $price - $voucher['discount'];
		}

		return $price;
	}

	//discounted amount
	public static function reversediscount($post,$price)
	{
		$voucher = Vouchers::find()->where('id =:id',[':id'=>$post])->one();
		
		if ($voucher['discount_type'] == 1)  {
			$price = (($price*$voucher['discount']) / 100);
		}
		elseif ($voucher['discount_type'] ==2) {
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
        $data['value'] = -1;
        $data['data'] = "";
		$uservoucher = UserVoucher::find()->where('code=:c',[':c'=>$code])->one();
        $voucher = Vouchers::find()->where('code=:c',[':c'=>$code])->all();
        
        /* Validations (user and date) */
        $valid = ValidController::DateValidCheck($code,1);

        if ($voucher[0]['discount_type'] == 5) {
            $valid = true;
        }
        
        if($valid == false){
            if ($uservoucher['uid'] != Yii::$app->user->identity->id) {
                Yii::$app->session->setFlash('error', 'Coupon cannot be used.');
                return $data;
            }
            elseif ($uservoucher['uid'] == Yii::$app->user->identity->id){
                Yii::$app->session->setFlash('error', 'Coupon cannot be used again.');
                return $data;
            }
        }
        foreach ($voucher as $k => $vou) 
        {
            if ($vou['status']==3 || $vou['status']==4) {
                $valid = false;
                Yii::$app->session->setFlash('error', 'Coupon was used. Please use another one.');
                return $data;
            }

            //detect special/condition voucher
            $special = VouchersSetCondition::find()->where('vid=:v',[':v'=>$vou['id']])->one();
            if (!empty($special)) {
                $voucherused = VouchersUsed::find()->where('vid=:v',[':v'=>$vou['id']])->one();
                $valid = self::specialVoucherUse($vou,$special,$order['Orders_TotalPrice']);
                if ($valid == false) {
                    return $data;
                }
            }
        }

		/* discounttotal make back 0, do discounts */
		$order['Orders_DiscountTotalAmount'] = 0 ;
		//might faced coupon with multiple function, use loop
		foreach ($voucher as $k => $vou) 
		{
			if ($order['Orders_TotalPrice'] > 0) 
			{
				if ($vou['discount_type'] == 1)  
                {
                	switch ($vou['discount_item']) 
                    {
                        case 1:
                            $order['Orders_DiscountTotalAmount'] += ($order['Orders_Subtotal']* ($vou['discount'] / 100));
                            //$order['Orders_Subtotal'] = $order['Orders_Subtotal']- ($order['Orders_Subtotal']* ($vou['discount'] / 100));
                            $order['Orders_TotalPrice'] =  $order['Orders_Subtotal'] + $order['Orders_DeliveryCharge'];
                            break;

                        case 2:
                            $order['Orders_DiscountTotalAmount'] += ($order['Orders_DeliveryCharge']* ($vou['discount'] / 100));
                            //$order['Orders_DeliveryCharge'] = $order['Orders_DeliveryCharge']-($order['Orders_DeliveryCharge']*($vou['discount'] / 100));
                            $order['Orders_TotalPrice'] =  $order['Orders_Subtotal'] + $order['Orders_DeliveryCharge'];
                            break;

                        case 3:
                        	$order['Orders_TotalPrice'] =  $order['Orders_Subtotal'] + $order['Orders_DeliveryCharge'];
                            $order['Orders_DiscountTotalAmount'] += ($order['Orders_TotalPrice']* ($vou['discount'] / 100));
                            $order['Orders_TotalPrice'] = $order['Orders_TotalPrice'] - ($order['Orders_TotalPrice']*($vou['discount'] / 100));
                            break;
                                     
                        default:
                        	Yii::$app->session->setFlash('error', Yii::t('common','Error!'));
                            return $data;
                            break;
                    }
            	}
            	elseif ($vou['discount_type'] == 2) 
                {
                    switch ($vou['discount_item']) 
                    {
                        case 1:
                            if (($order['Orders_Subtotal']-$vou['discount']) < 0) {
                                $order['Orders_DiscountTotalAmount'] += $order['Orders_Subtotal'];
                                //$order['Orders_Subtotal'] = 0;
                            }
                            else{
                                $order['Orders_DiscountTotalAmount'] += $vou['discount'];
                                //$order['Orders_Subtotal'] = $order['Orders_Subtotal'] - $vou['discount'];
                            }

                            $order['Orders_TotalPrice'] =  $order['Orders_Subtotal'] + $order['Orders_DeliveryCharge'];
                            break;

                        case 2:
                            if (($order['Orders_DeliveryCharge']-$vou['discount']) < 0) {
                                $order['Orders_DiscountTotalAmount'] += $order['Orders_DeliveryCharge'];
                                //$order['Orders_DeliveryCharge'] = 0;
                            }
                            else{
                                $order['Orders_DiscountTotalAmount'] += $vou['discount'];
                                //$order['Orders_DeliveryCharge'] = $order['Orders_DeliveryCharge'] - $vou['discount'];
                            }
                            $order['Orders_TotalPrice'] =  $order['Orders_Subtotal'] + $order['Orders_DeliveryCharge'];
                            break;

                        case 3:
                        	$order['Orders_TotalPrice'] =  $order['Orders_Subtotal'] + $order['Orders_DeliveryCharge'];
                            if (($order['Orders_TotalPrice']-$vou['discount']) < 0) {
                                $order['Orders_DiscountTotalAmount'] += $order['Orders_TotalPrice'];
                                //$order['Orders_TotalPrice'] = 0;
                            }
                            else{
                                $order['Orders_DiscountTotalAmount'] += $vou['discount'];
                                //$order['Orders_TotalPrice'] = $order['Orders_TotalPrice'] - $vou['discount'];
                            }
                            break;
                                     
                        default:
                            Yii::$app->session->setFlash('error', Yii::t('common','Error!'));
                            return $data;
                            break;
                    }
                }
            	else
            	{
            		Yii::$app->session->setFlash('error', Yii::t('discount','Coupon was used.'));
					return $data;
            	}
            	//save voucher status
            	
                $order['Orders_DiscountEarlyAmount'] = 0 ;
			}
		}
        $order['Orders_TotalPrice'] = $order['Orders_TotalPrice'] - $order['Orders_DiscountTotalAmount'];
        if ($order['Orders_TotalPrice'] < 0) {
            $order['Orders_TotalPrice'] = 0;
        }
        
        $order['Orders_Subtotal'] = number_format($order['Orders_Subtotal'],2);
        $order['Orders_DeliveryCharge']= number_format($order['Orders_DeliveryCharge'],2);
        $order['Orders_DiscountTotalAmount']= number_format($order['Orders_DiscountTotalAmount'],2);
        $order['Orders_TotalPrice']= number_format($order['Orders_TotalPrice'],2);
        $data['data'] = $order;
        $data['value'] = 1;
		return $data;
	}

    public static function specialVoucherUse($voucher,$special,$order=0)
    {
        switch ($special['condition_id']) {
            case 1:
                //case 1 = voucher that each user only can use once
                $voucherhistory = VouchersUsed::find()->where('vid=:v',[':v'=>$voucher['id']])->andWhere(['=','uid',Yii::$app->user->identity->id])->one();
                if (!empty($voucherhistory)) {
                    Yii::$app->session->setFlash('warning','You are already used this coupon.');
                    return false;
                }
                return true;
                
                break;

            case 2:
                //case 2 = voucher that with limited purchase
                if ($order>=$special['amount']) {
                    return true;
                }
                Yii::$app->session->setFlash('warning','Purchase amount was not fulfilled.');
                return false;
                break;
            
            default:
                Yii::$app->session->setFlash('warning','Something went wrong.');
                return false;
                break;
        }

    }
}