<?php
namespace frontend\controllers;

use Yii;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\filters\AccessControl;
use frontend\controllers\CommonController;
use frontend\controllers\CartController;
use frontend\controllers\PaymentController;
use common\models\Cart\Cart;
use common\models\Orders;
use common\models\Orderitem;
use common\models\Orderstatuschange;
use common\models\Orderitemselection;
use common\models\food\Foodselection;
use common\models\user\Useraddress;
use common\models\Area;
use common\models\User;
use frontend\models\Deliveryman;

class CheckoutController extends CommonController
{
	public function behaviors()
	{
	    return [
	        'verbs' => [
	            'class' => \yii\filters\VerbFilter::className(),
	            'actions' => [
	                'index'  => ['GET'],
	                'order'   => ['POST'],
	               
	            ],
	        ],
	        'access' => [
	        	'class' => AccessControl::className(),
	            'rules' => [
	            	'actions' => [
	            	'actions' => ['index','order'],
                    'allow' => true,
                    'roles' => ['@'],
	            	],
	            ],	
	        ],
	    ];
	}
	/*
	* code => coupun id
	*/
	public function actionIndex($area,$code = 0)
	{
		$cart = Cart::find()->where('uid = :uid and area = :area',[':uid'=> Yii::$app->user->identity->id,':area'=>$area])->all();
      	if(empty($cart))
      	{
      		Yii::$app->session->setFlash('error', 'Your Cart is Empty. Please Add item before processing to checkout');
			 return $this->redirect(Yii::$app->request->referrer);
      	}
		$early = $this->earlyOrder();

		if($early == false)
		{
			Yii::$app->session->setFlash('error', 'The allowed time to place order is over. Please place your order in between 8am and 11am daily.');
			  return $this->redirect(Yii::$app->request->referrer);
		}
        
        $order = new Orders;
		$address = Useraddress::find()->where('uid = :uid',[':uid'=> Yii::$app->user->identity->id])->orderBy('level DESC')->all();
		$addressmap = ArrayHelper::map($address,'id','address');
		return $this->render('index',['address'=> $address,'order' =>  $order ,'addressmap' => $addressmap ,'area' => $area,'code'=>$code]);
	}

	public function actionOrder()
	{
		$post = Yii::$app->request->post();
		
		$data = $this->areaDetect($post['area'],$post);

		if($data == -1)
		{
			return $this->redirect(Yii::$app->request->referrer);
		}
		$deliveryman = CartController::assignDeliveryMan($data['area']);
		
		if($deliveryman == -1   )
		{
			return $this->redirect(Yii::$app->request->referrer);
		}
		
		$isValid;

		$cart = Cart::find()->where('uid = :uid and area = :area',[':uid'=> Yii::$app->user->identity->id,':area'=>$post['area']])->joinWith('selection')->all();

		$allorderitem =$this->createOrderitem($cart,$post['Orders']['Orders_PaymentMethod']);
		
		$isValid = $allorderitem == -1 ? false : true;

		$order = $this->createOrder($data,$deliveryman);
		if ($order == false) {
			return $this->redirect(Yii::$app->request->referrer);
		}
		
		$delivery = $this->addDeliveryAssignment($deliveryman);

		$isValid = $order->validate() && $isValid && $delivery->validate();
		
		if($isValid)
		{
			$transaction = Yii::$app->db->beginTransaction();
			try{
				$payment = -1;
				$order->save();
				$did = $order->Delivery_ID;

				
				foreach($allorderitem as $orderitem)
				{

					$orderitem->Delivery_ID = $did;
					if(!($isValid == $orderitem->save()))
					{
						break;
					}
					if(!is_null($orderitem['item']))
					{
	
						foreach($orderitem['item'] as $item)
						{

							$item->Order_ID = $orderitem->Order_ID;
							if(!($isValid = $item->save()))
		                    {
		                           break;
		                    }
						}
						
					}
				}
				
				//$isValid = $this->createOrderitem($query,$did) && $isValid;
				$isValid = $delivery->save() && $isValid;
				
				if($isValid == true)
                {
                    $transaction->commit();
                    NotificationController::createNotification($did,3);
                    CartController::mutipleDelete($cart);
                    Yii::$app->session->setFlash('success', 'Order Success');
                   	return $this->redirect(['/cart/aftercheckout','did'=>$did]);
                }
                else
                {
                 	$transaction->rollBack();
                }
			}
			catch(Exception $e)
            {
                $transaction->rollBack();
            }
		}

		//Yii::$app->session->setFlash('warning', "Order Fail. Please Try Again Later");
        return $this->redirect(Yii::$app->request->referrer);
	}

	/*
	* detect is early order or not
	*/
	protected static function earlyOrder()
	{
		$early = date('08:00:00');
		$timenow = Yii::$app->formatter->asTime(time());
        //$last = date('11:00:59');
        $last = date('23:00:59');

        if($early >= $timenow || $last <= $timenow)
        {
            return false;
        }
        else
        {
        	return true;
        }
	}

	/*
	* use for detect user address postcode is same as the area 
	* area => areagroup that use submit
	* id => user address id
	* -1 => false address
	*/
	protected static function areaDetect($area,$post)
	{
		$isValid;
		$groupArea = ArrayHelper::map(Area::find()->where('Area_Group = :group',[':group' => $area])->all() ,'Area_Postcode','Area_Postcode');
		$address = Useraddress::findOne($post['Orders']['Orders_Location']);

		if(!empty($groupArea[$address['postcode']]))
		{
			$post['Orders']['Orders_Location'] = $address->address;
			$post['Orders']['Orders_Postcode'] = $address->postcode;
			$post['Orders']['Orders_Area'] = $address->city;
			$post['Orders']['Orders_SessionGroup'] = $area;
			return $post;
		}
		else
		{
			Yii::$app->session->setFlash('error', 'The address does not match your cart area.');
			return -1;
		}
	}

	/*
	* create order
	*/
	protected static function createOrder($data,$deliveryman)
	{
		$subtotal = Cart::find()->where('uid = :uid and area = :area',[':uid'=> Yii::$app->user->identity->id,':area'=>$data['area']])->sum('price * quantity');

		$order = new Orders;
		$order->load($data);
	
		$order->User_Username = Yii::$app->user->identity->username;
		$order->Orders_Deliveryman = $deliveryman;
		$order->Orders_Date = date("Y-m-d");
		$order->Orders_Time = date("13:00:00");
		$order->Orders_Status = $order->Orders_PaymentMethod == "Cash on Delivery" ? "Pending" : "Not Paid";
		$order->Orders_DateTimeMade = time();
		$order->Orders_Subtotal = $subtotal;
		$order->Orders_DeliveryCharge = 5;
		$order->Orders_DiscountTotalAmount = 0;
		$order->Orders_TotalPrice = 5 + $subtotal;

		if(self::earlyOrder())
		{
			$order->Orders_DiscountEarlyAmount = CartController::actionRoundoff1decimal($subtotal * 0.2);
			//$order->Orders_DiscountTotalAmount += $order->Orders_DiscountEarlyAmount;
			$order->Orders_TotalPrice -= $order->Orders_DiscountEarlyAmount;
		}

		if (!empty($data['code'])) {
			$order = DiscountController::orderdiscount($data['code'],$order);
		}

		//$order->Orders_TotalPrice=0;
		return $order;
	}

	/*
	* save mutiple orderitem
	* one fail stop the loop
	* id => order id
	* query => Cart Query
	*/
	protected static function createOrderitem($cart,$status)
	{
		$isValid = true;
		foreach($cart as $i=>$detail)
		{
			$orderitem = new Orderitem;
			//$orderitem->Delivery_ID = $id;
			$orderitem->Food_ID = $detail->fid;
			$orderitem->OrderItem_Quantity  = $detail->quantity;
			$orderitem->OrderItem_SelectionTotal = $detail->selectionprice;
			$orderitem->OrderItem_LineTotal = $detail->price;
			$orderitem->OrderItem_Status = $status == "Cash on Delivery" ? "Pending" : "Not Paid";
			$orderitem->OrderItem_Remark = $detail->remark;
			$isValid = $orderitem->validate() && $isValid;
			$allitem[$i] = $orderitem;
			if(!empty($detail['selection']))
			{
				foreach($detail['selection'] as $k=>$data)
				{
					$orderitemselection = new Orderitemselection;
					//$orderitemselection->Order_ID = $oid;
					$orderitemselection->Selection_ID = $data->selectionid;
					$foodtype = Foodselection::findOne($data->selectionid);
					$orderitemselection->FoodType_ID = $foodtype->Type_ID;
					$allitem[$i]->item[$k] = $orderitemselection;
					$isValid = $orderitemselection->validate() && $isValid;
					
				}
			}
		}
		//return $allitem;
		return $isValid == true ? $allitem : -1;	
	}

	protected static function addDeliveryAssignment($name)
	{
		$user = User::find()->where("username = :name",[":name"=> $name])->limit(1)->one();
		$delivery = Deliveryman::find()->where("User_id = :uid",[':uid' => $user->id])->one();
		$delivery->DeliveryMan_Assignment += 1;
		return $delivery;
	}
}