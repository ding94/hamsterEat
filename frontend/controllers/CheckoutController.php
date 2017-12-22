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
use common\models\Order\Orders;
use common\models\Order\Orderitem;
use common\models\Order\Orderstatuschange;
use common\models\Order\Orderitemselection;
use common\models\Order\DeliveryAddress;
use common\models\food\Foodselection;
use common\models\user\Useraddress;
use common\models\Company\Company;
use common\models\Area;
use common\models\User;
use common\models\user\Userdetails;
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
        
        $company = Company::find()->where('area_group = :group',[':group'=>$area])->all();
        $companymap = ArrayHelper::map($company,'id','name');
       	$user = Userdetails::find()->where('User_id=:uid',[':uid'=>Yii::$app->user->identity->id])->one();
       	$username = "";
       	$contact = "";
       	if (!empty($user['User_FirstName']) || !empty($user['User_LastName'])) {
       		$username = $user['User_FirstName'].' '.$user['User_LastName'];
       	}
       	if (!empty($user['User_ContactNo'])) {
       		$contact = $user['User_ContactNo'];
       	}
        $order = new Orders;
        $deliveryAddress = new DeliveryAddress;
		//$address = Useraddress::find()->where('uid = :uid',[':uid'=> Yii::$app->user->identity->id])->orderBy('level DESC')->all();
		//$addressmap = ArrayHelper::map($address,'id','address');
		return $this->render('index',['deliveryaddress'=>$deliveryAddress, 'order'=>$order, 'area'=>$area, 'code'=>$code, 'username'=>$username, 'contact'=>$contact, 'companymap'=>$companymap]);
	}

	public function actionOrder()
	{
		$post = Yii::$app->request->post();

		$deliveyaddress = $this->areaDetect($post['area'],$post);
		
		if($deliveyaddress['value'] == -1)
		{
			return $this->redirect(Yii::$app->request->referrer);
		}
		$address = $deliveyaddress['data'];

		$deliveryman = CartController::assignDeliveryMan($post['area'],$post['DeliveryAddress']['cid']);
<<<<<<< HEAD

		if($deliveryman == -1)
=======
	
		if($deliveryman == -1   )
>>>>>>> acea78a2ad1bcc435ecf98485d179b51f898e81b
		{
			return $this->redirect(Yii::$app->request->referrer);
		}
		
		$address->deliveryman = $deliveryman;

		$cart = Cart::find()->where('uid = :uid and area = :area',[':uid'=> Yii::$app->user->identity->id,':area'=>$post['area']])->joinWith('selection')->all();

		$allorderitem =$this->createOrderitem($cart,$post['Orders']['Orders_PaymentMethod']);
		
		$isValid = $allorderitem == -1 ? false : true;

		$dataorder = $this->createOrder($post,$deliveryman,$address['area']);

		if($dataorder['value'] == -1)
		{
			return $this->redirect(Yii::$app->request->referrer);
		}

		$order = $dataorder['data'];
		
		$delivery = $this->addDeliveryAssignment($deliveryman);

		$isValid = $order->validate()  && $delivery->validate() && $address->validate() && $isValid;
		
		if($isValid)
		{
			$transaction = Yii::$app->db->beginTransaction();

			try{
				$payment = -1;
				$order->save();
				$did = $order->Delivery_ID;
				$address->delivery_id = $did;
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
				$isValid = $delivery->save() && $address->save() && $isValid;
				
				if($isValid == true)
                {
                    $transaction->commit();
                    NotificationController::createNotification($did,1);
                    CartController::mutipleDelete($cart);
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
	* use for detect wehter is using company address or user adderss
	*/
	protected static function areaDetect($area,$post)
	{
	
		if($post['DeliveryAddress']['cid'] != 0)
		{
			return self::createCompanyAddress($area,$post);
		}
		else
		{
			return self::createUserAddress($area,$post);
		}
	}

	/*
	* use for detect user address postcode is same as the area 
	* area => areagroup that use submit
	* id => user address id
	* -1 => false address
	*/
	protected static function createUserAddress($area,$post)
	{
		$data['value'] = -1;
		$data['data'] = "";
		$groupArea = ArrayHelper::map(Area::find()->where('Area_Group = :group',[':group' => $area])->all() ,'Area_Postcode','Area_Postcode');
		$address = Useraddress::findOne($post['DeliveryAddress']['location']);

		if(!empty($groupArea[$address['postcode']]))
		{
			$deliveryaddress = new DeliveryAddress;
			$deliveryaddress->load($post);
			$deliveryaddress->type = 2;
			$deliveryaddress->location = $address->address;
			$deliveryaddress->postcode = $address->postcode;
			
			$deliveryaddress->area = $address->city;
			$data['value'] = 1;
			$data['data'] = $deliveryaddress;
			return $data;
		}
		else
		{
			Yii::$app->session->setFlash('error', 'The address does not match your cart area.');
			return $data;
		}
	}

	/*
	* create company address
	*/
	protected static function createCompanyAddress($area,$post)
	{
		$data['value'] = -1;
		$data['data'] = "";
		$address = Company::findOne($post['DeliveryAddress']['cid']);
		if(empty($address) || $address->area_group != $area)
		{
			Yii::$app->session->setFlash('error', 'The company address does not match your cart area.');
			return $data;
		}

		$deliveryaddress = new DeliveryAddress;
		$deliveryaddress->load($post);
		$deliveryaddress->type = 1;
		$deliveryaddress->location = $address->address;
		$deliveryaddress->postcode = $address->postcode;
		$deliveryaddress->area = $address->area;
		$data['value'] = 1;
		$data['data'] = $deliveryaddress;
		return $data;
	}

	/*
	* create order
	*/
	protected static function createOrder($data,$deliveryman)
	{
		$data['value'] = -1;
		$data['data'] = "";
 		$subtotal = Cart::find()->where('uid = :uid and area = :area',[':uid'=> Yii::$app->user->identity->id,':area'=>$data['area']])->sum('price * quantity');

		$order = new Orders;
		$order->load($data);
		$order->User_Username = Yii::$app->user->identity->username;
		$order->Orders_Date = date("Y-m-d");
		$order->Orders_Time = date("13:00:00");
		$order->Orders_Status = $order->Orders_PaymentMethod == "Cash on Delivery" ? 2 : 1;
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
			$data = DiscountController::orderdiscount($data['code'],$order);
			if($data['value'] == -1)
			{
				return $data;
			}
			$order  = $data['data'];
		}
		$data['value'] = 1;
		$data['data'] = $order;
		//$order->Orders_TotalPrice=0;
		return $data;
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
			$orderitem->OrderItem_Status = $status == "Cash on Delivery" ? 2 : 1;
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

	protected static function addDeliveryAssignment($id)
	{
		$user = User::findOne($id);
		
		$delivery = Deliveryman::find()->where("User_id = :uid",[':uid' => $user->id])->one();
		$delivery->DeliveryMan_Assignment += 1;
		return $delivery;
	}
}