<?php
namespace frontend\controllers;

use Yii;
use yii\web\Cookie;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\filters\AccessControl;
use frontend\controllers\CommonController;
use frontend\controllers\CartController;
use frontend\controllers\VouchersController;
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
use common\models\DeliverymanCompany;
use common\models\Area;
use common\models\User;
use common\models\user\Userdetails;
use common\models\Deliveryman;


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
	                'process'   => ['POST'],
	            ],
	        ],
	        'access' => [
	        	'class' => AccessControl::className(),
	            'rules' => [
	            	'actions' => [
	            	'actions' => ['index','order','process'],
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
	public function actionIndex()
	{   
		$early = $this->earlyOrder();
		
		$cookies = Yii::$app->request->cookies;
		
		if(empty($cookies['cart']))
      	{
      		Yii::$app->session->setFlash('error', Yii::t('checkout','You Already Submit The Order'));
			return $this->redirect(['order/my-orders']);
      	}

      	$cartData = $cookies->getValue('cart');

		if($early == false)
		{
			Yii::$app->session->setFlash('error', Yii::t('checkout','The allowed time to place order is over. Please place your order in between 8am and 11am daily.'));
			  return $this->redirect(Yii::$app->request->referrer);
		}
        
        $company = Company::find()->where('area_group = :group',[':group'=>$cartData['area']])->all();
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
		return $this->render('index',['deliveryaddress'=>$deliveryAddress, 'order'=>$order, 'username'=>$username, 'contact'=>$contact, 'companymap'=>$companymap]);
	}

	public function actionProcess()
	{
		$post = Yii::$app->request->post();
	
		if(empty($post['cid']))
      	{
      		Yii::$app->session->setFlash('error', Yii::t('checkout','Your Cart is Empty. Please Add item before processing to checkout'));
			return $this->redirect(Yii::$app->request->referrer);
      	}

		$cookieData['cid'] = $post['cid'];
      	$cookieData['area'] = $post['area'];
      	$cookieData['code'] = $post['code'];

      	$cookie =  new Cookie([
            'name' => 'cart',
            'value' => $cookieData,
            'expire' => time() + 3600,
        ]);
       
       
        \Yii::$app->getResponse()->getCookies()->add($cookie);
      
		
		return $this->redirect(['checkout/index']);
	}

	public function actionOrder()
	{
		$post = Yii::$app->request->post();
		$cookies = Yii::$app->request->cookies;

		if(empty($cookies['cart']))
		{
			Yii::$app->session->setFlash('warning', Yii::t('checkout',"Order Expeire Aready. Please Try Again"));
			return $this->redirect(['/cart/view-cart']);
		}

		if(empty($post['DeliveryAddress']) || empty($post['Orders']))
		{
			Yii::$app->session->setFlash('warning', Yii::t('checkout',"Please Fill Out Everything"));
			return $this->redirect(['/cart/view-cart']);
		}
		
		$cartData = $cookies->getValue('cart');
				
		$avaiableCart = true;
		$foodOn = true;
		foreach($cartData['cid'] as $id)
		{
			$query = Cart::find()->where('id = :id and uid = :uid and area = :area',[':id'=>$id ,':uid'=>Yii::$app->user->identity->id,':area'=>$cartData['area']])->one();
			if(empty($query))
			{
				$avaiableCart = false;
				break;
			}

			if($query->status != 1)
			{
				$foodOn = false;
			}
		}
		
		if(!$avaiableCart)
		{
			Yii::$app->session->setFlash('warning', Yii::t('checkout',"The Cart Item Does Not Match You Order Item"));
			return $this->redirect(['/cart/view-cart']);
		}

		if(!$foodOn)
		{
			Yii::$app->session->setFlash('warning', Yii::t('checkout',"One Of The Cart Item Already Finish"));
			return $this->redirect(['/cart/view-cart']);
		}

		$deliveyaddress = $this->areaDetect($cartData['area'],$post);

		$deliveryman = $this->assignDeliveryMan($cartData['area'],$post['DeliveryAddress']['cid']);

		if($deliveyaddress['value'] == -1 || $deliveryman == -1)
		{
			Yii::$app->session->setFlash('warning', Yii::t('checkout',"Currently Now Delivery Man."));
			return $this->redirect(['/cart/view-cart']);
		}
		
		$address = $deliveyaddress['data'];
		$address->deliveryman = $deliveryman;


		//$cart = Cart::find()->where('uid = :uid and area = :area',[':uid'=> Yii::$app->user->identity->id,':area'=>$post['area']])->joinWith('selection')->all();
		
		$dataitem =$this->createOrderitem($cartData['cid'],$post['Orders']['Orders_PaymentMethod']);
		
		$dataorder = $this->createOrder($post,$deliveryman,$cartData);
	
		if($dataorder['value'] == -1 || $dataitem['value'] == -1)
		{
			Yii::$app->session->setFlash('warning', Yii::t('checkout',"Your order Something Went Wrong"));
			return $this->redirect(['/cart/view-cart']);
		}

		$order = $dataorder['data'];
		$allorderitem = $dataitem['data'];
		$delivery = $this->addDeliveryAssignment($deliveryman);

		$isValid = $delivery->validate() && $address->validate() ;

		if($isValid)
		{
			$transaction = Yii::$app->db->beginTransaction();

			try{
				$payment = -1;
				$order->save();
				$did = $order->Delivery_ID;
				$address->delivery_id = $did;
				if (!empty($cartData['code'])) {
					
					$isValid = VouchersController::endvoucher($cartData['code'],$order->Delivery_ID) && $isValid;
				}
				
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
                    CartController::mutipleDelete($cartData['cid']);
                    $cookies = Yii::$app->response->cookies;
                    $cookies->remove('cart');
                    unset($cookies['cart']);
                    NotificationController::createNotification($did,1);
                    
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
		$type = $post['DeliveryAddress']['cid'] != 0 ? 1 : 2;
		
		return self::createAddress($area,$post,$type);
	}

	/*
	* use for detect user address postcode is same as the area 
	* area => areagroup that use submit
	* id => user address id
	* -1 => false address
	*/
	protected static function createAddress($area,$post,$type)
	{
		$data['value'] = -1;
		$data['data'] = "";
		if($type == 2)
		{
			$groupArea = ArrayHelper::map(Area::find()->where('Area_Group = :group',[':group' => $area])->all() ,'Area_Postcode','Area_Postcode');
			$address = Useraddress::findOne($post['DeliveryAddress']['location']);
			if(empty($groupArea[$address['postcode']]))
			{
				Yii::$app->session->setFlash('error', Yii::t('cart','The address does not match your cart area.'));
				return $data;
			}
		}
		else
		{
			$address = Company::findOne($post['DeliveryAddress']['cid']);
			if(empty($address) || $address->area_group != $area)
			{
				Yii::$app->session->setFlash('error', 'The company address does not match your cart area.');
				return $data;
			}
		}
	
		$deliveryaddress = new DeliveryAddress;
		$deliveryaddress->load($post);
		$deliveryaddress->type = $type;
		$deliveryaddress->location = $address->address;
		$deliveryaddress->postcode = $address->postcode;
		
		$area = $type != 0 ? $address->area : $address->city;
		
		$deliveryaddress->area = $area;
		$data['value'] = 1;
		$data['data'] = $deliveryaddress;
		return $data;
	}

	/*
	* create order
	*/
	protected static function createOrder($post,$deliveryman,$cookie)
	{
		
		$data['value'] = -1;
		$data['data'] = "";
		
		$total =0;
		foreach($cookie['cid'] as $cid)
		{
			$cart = Cart::find()->where('cart.id = :id',[':id'=>$cid])->joinWith(['food'])->one();
			
			$countDelivery[$cart->food->Restaurant_ID] = 0;
			$total += $cart->price * $cart->quantity;
		}
 		
 		$deliveryCharge = count($countDelivery) * 5;
		$order = new Orders;
		$order->load($post);
		$order->User_Username = Yii::$app->user->identity->username;
		$order->Orders_Date = date("Y-m-d");
		$order->Orders_Time = date("13:00:00");
		$order->Orders_Status = $order->Orders_PaymentMethod == "Cash on Delivery" ? 2 : 1;
		$order->Orders_DateTimeMade = time();
		$order->Orders_Subtotal = $total;
		$order->Orders_DeliveryCharge = $deliveryCharge;
		$order->Orders_DiscountTotalAmount = 0;
		$order->Orders_TotalPrice = $deliveryCharge + $total;
		
		if(self::earlyOrder())
		{
			$order->Orders_DiscountEarlyAmount = CartController::actionRoundoff1decimal($total * 0.15);
			//$order->Orders_DiscountTotalAmount += $order->Orders_DiscountEarlyAmount;
			$order->Orders_TotalPrice -= $order->Orders_DiscountEarlyAmount;
		}

		if (!empty($cookie['code'])) {
		
			$data = DiscountController::orderdiscount($cookie['code'],$order);
			if($data['value'] == -1)
			{
				return $data;
			}
			$order  = $data['data'];
		}
		if($order->validate())
		{
			$data['value'] = 1;
			$data['data'] = $order;
		}
		
		//$order->Orders_TotalPrice=0;
		return $data;
	}

	/*
	* save mutiple orderitem
	* one fail stop the loop
	* id => order id
	* query => Cart Query
	*/
	protected static function createOrderitem($allCid,$paymentMethod)
	{
		$isValid = true;
		$data['value'] = -1;
		$data['data'] = array();
		foreach($allCid as $i=>$cid)
		{
			$cart = Cart::find()->where('cart.id = :id',[':id'=>$cid])->joinWith(['selection'])->one();
			
			$orderitem = new Orderitem;
			//$orderitem->Delivery_ID = $id;
			$orderitem->Food_ID = $cart->fid;
			$orderitem->OrderItem_Quantity  = $cart->quantity;
			$orderitem->OrderItem_SelectionTotal = $cart->selectionprice;
			$orderitem->OrderItem_LineTotal = $cart->price;
			$orderitem->OrderItem_Status = $paymentMethod == "Cash on Delivery" ? 2 : 1;
			$orderitem->OrderItem_Remark = $cart->remark;
			
			$isValid = $orderitem->validate() && $isValid;
			$allitem[$i] = $orderitem;
			if(!empty($cart['selection']))
			{
				foreach($cart['selection'] as $k=>$selection)
				{
					$orderitemselection = new Orderitemselection;
					//$orderitemselection->Order_ID = $oid;
					$orderitemselection->Selection_ID = $selection->selectionid;
					$foodtype = Foodselection::findOne($selection->selectionid);
					$orderitemselection->FoodType_ID = $foodtype->Type_ID;
					$allitem[$i]->item[$k] = $orderitemselection;
					$isValid = $orderitemselection->validate() && $isValid;
					
				}
			}
		}

		if($isValid)
		{
			$data['value'] = 1;
			$data['data'] = $allitem;
		}
		//return $allitem;
		return $data;	
	}

	protected static function addDeliveryAssignment($id)
	{
		$user = User::findOne($id);
		
		$delivery = Deliveryman::find()->where("User_id = :uid",[':uid' => $user->id])->one();
		$delivery->DeliveryMan_Assignment += 1;
		return $delivery;
	}

	//--This function is to assign a delivery man when an order has been placed
    protected static function assignDeliveryMan($area,$cid)
    {   
        /*$data = DailySignInController::getAllDailyRecord($area);
          
        if(empty($data))
        {s
            Yii::$app->session->setFlash('error', 'Sorry! We have insufficient of deliveryman, please try after 10 minutes or contact our customer service for more information.');
            return -1;
        }*/

        $dc = DeliverymanCompany::find()->where('cid=:cid',[':cid'=>$cid])->one();
        if($dc != null){
            $uid = $dc['uid'];
            return $uid;
        }

        $allData ="" ;
        foreach ($data as $id)
        {
            $sql = Deliveryman::findOne($id);    
            $allData[] = $sql;
        }
            
        $lowest = 0;
        $uid = 0;
        foreach($allData as $i => $delivery)
        {
            if($lowest == 0 )
            {
                $lowest = $delivery->DeliveryMan_Assignment;
                $uid = $delivery->User_id;
            }
            else
            {
                if($delivery->DeliveryMan_Assignment < $lowest)
                {
                    $lowest = $delivery->DeliveryMan_Assignment;
                    $uid = $delivery->User_id;
                }
                   
            }
        }
        return $uid;
            //$user = User::findOne($uid);
           
            //return $user->username;       
    }
}