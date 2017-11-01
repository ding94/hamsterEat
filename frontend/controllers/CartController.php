<?php
namespace frontend\controllers;
use Yii;
use yii\web\Controller;
use common\models\Orderitem;
use common\models\User;
use common\models\food\Food;
use common\models\Orders;
use common\models\Orderitemselection;
use common\models\food\Foodselectiontype;
use common\models\food\Foodselection;
use common\models\Vouchers;
use common\models\UserVoucher;
use common\models\user\Userdetails;
use common\models\Ordersstatuschange;
use common\models\Orderitemstatuschange;
use common\models\Restaurant;
use common\models\Account\Accountbalance;
use frontend\models\Deliveryman;
use frontend\controllers\PaymentController;
use frontend\controllers\MemberpointController;
use frontend\controllers\NotificationController;
use yii\helpers\Json;
use frontend\modules\delivery\controllers\DailySignInController;
use frontend\controllers\CommonController;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\filters\AccessControl;

class CartController extends CommonController
{
    public function behaviors()
    {
        return [
             'access' => [
                 'class' => AccessControl::className(),
                 //'only' => ['logout', 'signup','index'],
                 'rules' => [
                    [
                        'actions' => ['addto-cart','checkout','delete','view-cart','getdiscount','aftercheckout'],
                        'allow' => true,
                        'roles' => ['@','?'],

                    ],
                    //['actions' => [],'allow' => true,'roles' => ['?'],],
                    
                 ]
             ]
        ];
    }
    
    public function actionAddtoCart($Food_ID,$quantity,$finalselected,$remarks,$rid,$sessiongroup)
    {
        if (Yii::$app->user->isGuest) 
        {
            $this->redirect(['site/login']);
        }

        else
        {

            $session = Yii::$app->session;
            $cart = orders::find()->where('User_Username = :uname',[':uname'=>Yii::$app->user->identity->username])->andwhere('Orders_Status = :status',[':status'=>'Not Placed'])->one();

            if (empty($cart))
            {
                $newcart = new Orders;

                $newcart->User_Username = Yii::$app->user->identity->username;
                $newcart->Orders_Status = 'Not Placed';
                $newcart->Orders_SessionGroup = $sessiongroup;

                $newcart->save();
                $cart = orders::find()->where('User_Username = :uname',[':uname'=>Yii::$app->user->identity->username])->andwhere('Orders_Status = :status',[':status'=>'Not Placed'])->one();
            }

            $orderitem = new Orderitem;
            $findfood = Food::find()->where('Food_ID = :fid', [':fid'=>$Food_ID])->one();
            $findfoodprice = $findfood['Price'];
            $foodareagroup = Restaurant::find()->where('Restaurant_ID = :rid', [':rid'=>$findfood['Restaurant_ID']])->one();
            $foodareagroup = $foodareagroup['Restaurant_AreaGroup'];

            if ($foodareagroup == $cart['Orders_SessionGroup'])
            {
                $orderitem->Delivery_ID = $cart['Delivery_ID'];
                $orderitem->Food_ID = $Food_ID;
                $orderitem->OrderItem_Quantity = $quantity;
                $linetotal = $findfoodprice * $quantity;
				//Foodselection::find()->where('ID = :sid',[':sid'=>$selected2])->one();
				//$orderitem->OrderItem_UP = $findfoodprice + $;
			
                $orderitem->OrderItem_LineTotal = $linetotal;
                $orderitem->OrderItem_Status = 'Not Placed';
                $orderitem->OrderItem_Remark = $remarks;
			//	var_dump($orderitem);exit;
                $orderitem->save();

                $findorderid = Orderitem::find()->where('Delivery_ID = :did',[':did'=>$cart['Delivery_ID']])->all();
                $oid = 0;
                foreach ($findorderid as $orderid) :
                    if ($orderid['Order_ID'] > $oid)
                    {
                        $oid = $orderid['Order_ID'];
                    }
                endforeach;

                if ($finalselected != '')
                {
                    $selected = JSON::decode($finalselected);
                    //var_dump($selected);exit;
                    $selectionprice = 0;
                    $selectiontotalprice = 0;
                    foreach ($selected as $selected2) :
                        if(is_array($selected2))
                        {
                            foreach($selected2 as $select):
                            $orderitemselection = new Orderitemselection;
                            $orderitemselection->Order_ID = $oid;
                            $orderitemselection->Selection_ID = (int)$select;
                            //var_dump($orderitemselection->Selection_ID);exit;
                            $foodtypeid = Foodselection::find()->where('ID = :sid',[':sid'=>$orderitemselection->Selection_ID])->one();
                            $foodtypeid = $foodtypeid['Type_ID'];
							
                            $orderitemselection->FoodType_ID = $foodtypeid;
                            //var_dump($orderitemselection->Selection_ID);exit;
                            $foodselectionprice = Foodselection::find()->where('ID = :sid',[':sid'=>$orderitemselection->Selection_ID])->one();
							//$up = $foodselectionprice['Price'] + $findfoodprice;
                            //var_dump($up);exit;
                            $selectiontotalprice = $selectiontotalprice + $foodselectionprice['Price'];
                            $orderitemselection->save();
                            endforeach;
                        }
                        else
                        {
                            $orderitemselection = new Orderitemselection;
                            $orderitemselection->Order_ID = $oid;
                            $orderitemselection->Selection_ID = (int)$selected2;
                            $foodtypeid = Foodselection::find()->where('ID = :sid',[':sid'=>$selected2])->one();
                            $foodtypeid = $foodtypeid['Type_ID'];
                            $orderitemselection->FoodType_ID = $foodtypeid;
                            $foodselectionprice = Foodselection::find()->where('ID = :sid',[':sid'=>$selected2])->one();
                            $selectiontotalprice = $selectiontotalprice + $foodselectionprice['Price'];
                            $orderitemselection->save();
                        }
                    endforeach;
					//var_dump($foodselectionprice['Price']);exit;
                    $selectiontotalprice = $selectiontotalprice * $quantity;
                    $linetotal = $linetotal + $selectiontotalprice;
                    $linetotalupdate = "UPDATE orderitem SET OrderItem_LineTotal = ".$linetotal.", OrderItem_SelectionTotal = ".$selectiontotalprice." WHERE Order_ID = ".$oid."";
                    Yii::$app->db->createCommand($linetotalupdate)->execute();
                }

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

                Yii::$app->session->setFlash('success', 'Food item has been added to cart. '.Html::a('<u>Go to my Cart</u>', ['/cart/view-cart']).'.');
                return $this->redirect(['/Restaurant/default/restaurant-details', 'rid' => $rid]);
            }
            else
            {
                Yii::$app->session->setFlash('error', 'Failed to add item to cart. This item is in a different area from the item(s) in your cart. Please empty your cart to start ordering from a new area.');
                return $this->redirect(['/Restaurant/default/restaurant-details', 'rid' => $rid]);
            }
        }
    }

    public function actionViewCart()
    {
        if (Yii::$app->user->isGuest) 
        {
            $this->redirect(['site/login']);
        }

        else
        {
        $cart = orders::find()->where('User_Username = :uname',[':uname'=>Yii::$app->user->identity->username])->andwhere('Orders_Status = :status',[':status'=>'Not Placed'])->one();
        $did = $cart['Delivery_ID'];
		//$did = Orders::find()->where('Delivery_ID = :did',[':did'=>$did])->one();
		//$restaurant = Restaurant::find()->where('Restaurant_ID = :rid', [':rid'=>$findfood['Restaurant_ID']])->one();
		//$foodselectionprice = Foodselection::find()->where('ID = :sid',[':sid'=>$selected2])->one();
		//$selectiontotalprice = $selectiontotalprice + $foodselectionprice['Price'];
		$cartitems = Orderitem::find()->where('Delivery_ID = :did',[':did'=>$did])->all();
		foreach($cartitems as $k => $cartitem): 
		//$findf = food::find()->where('Food_ID=:fid',[':fid'=>$cartitem['Food_ID']])->one()->Restaurant_ID;
		// $fooddetails = Food::find()->where('Food_ID = :fid',[':fid'=>$cartitem['Food_ID']])->one();
		//var_dump($cartitems);exit;
		endforeach; 
        $voucher = new Vouchers;
		
        if (Yii::$app->request->post()) 
        {
            $data = Yii::$app->request->post();
            $session = Yii::$app->session;
            if (is_null($session['area']) || is_null($session['postcode']))
            {
                Yii::$app->session->setFlash('error', 'Checkout failed. Please provide your delivery postcode and area first.');
                return $this->redirect(['site/index']);
            }
            elseif ($session['group'] != $cart['Orders_SessionGroup'])
            {
                Yii::$app->session->setFlash('error', 'Checkout failed. The postcode and area you entered are not the same with the item(s) in your cart. Please empty your cart to change your delivery area.');
                return $this->redirect(['site/index']);
            }
      //  var_dump($data);exit;
            return $this->redirect(['checkout','did'=>$did, 'discountcode'=>$data['Orders']['Orders_TotalPrice']]);
        }
        return $this->render('cart', ['did'=>$did,'cartitems'=>$cartitems,'voucher'=>$voucher]);
    }
    }

    public function actionAssignDeliveryMan($did)
    {
       // $purchaser = orders::find()->where('User_Username = :id',[':id'=>Yii::$app->user->identity->username])->one();
      
     
      // $get = deliveryman::find()->all();
  
       $data = DailySignInController::getAllDailyRecord();
       if (!empty($data)) {
           $allData ="" ;
           foreach ($data as $id)
           {
                 
                $sql= User::find()->select(['id','deliveryman.DeliveryMan_Assignment'])->JoinWith(['authAssignment','deliveryman'])->where('item_name = :item_name and id = :id',[':item_name' => 'rider',':id'=>$id])->orderBy(['deliveryman.DeliveryMan_Assignment'=>SORT_ASC])->asArray()->one();
                
               $allData[] = $sql;
             
           }
           return true;
       }
       else
       {
        Yii::$app->session->setFlash('error', 'Sorry! We have insufficient of deliveryman, please try after 10 minutes or contact our customer service for more information.');
        return false;
       }
       
       
        
     
      // $allData = ArrayHelper::getColumn($sql['deliveryman'],'DeliveryMan_Assignment'   );
 
        $arr = "";
        $arr2="";
        foreach ($allData as $i) {
            //var_dump($i);exit;
            $arr = $i['DeliveryMan_Assignment'].'.'.$i['id'].','.$arr;
        }
        
        $array = explode(',',$arr);
        
        for($a=1;$a<count($array);$a++)
        {
            for($j=count($array)-1;$j>=$a;$j--)
            {
                if($array[$j]<$array[$j-1])
                { 
                    $temp = $array[$j-1]; 
                    $array[$j-1] = $array[$j]; 
                    $array[$j] = $temp; 
            }
        }
        }
        foreach (array_keys($array, '',true) as $key)
        {
            unset($array[$key]);
        }
        //var_dump($array[1]);exit;
        $chosendman = $array[1];
    
        
        $assign = substr($chosendman, strpos($chosendman, ".") + 1);
       
        
       

        // foreach ($array as $x)
        // {
             
        //      $deliveryman = $x['id'];
           
        //      $assign = $x['DeliveryMan_Assignment'] + 1;
        // }
        $find = deliveryman::find()->where('User_id = :id',[':id'=>$assign])->one();
      
      $task = $find['DeliveryMan_Assignment'] +1;
     // var_dump($task);exit;
            $sql1 = "UPDATE deliveryman SET DeliveryMan_Assignment = ".$task." WHERE User_id = '".$assign."'";
           
            Yii::$app->db->createCommand($sql1)->execute();
       
            echo "<script type='text/javascript'>alert('The delivery man assigned to this order is ".$assign."');</script>";
            $dname = user::find()->where('id = :uid', [':uid'=>$assign])->one();
            $dname = $dname['username'];

            $sql6 = "UPDATE orders SET Orders_DeliveryMan = '".$dname."' WHERE Delivery_ID = ".$did."";
            Yii::$app->db->createCommand($sql6)->execute();

            return $dname;
    }

    public function actionCheckout($did,$discountcode)
    {
        $mycontact = Userdetails::find()->where('User_Username = :uname',[':uname'=>Yii::$app->user->identity->username])->one();
        $mycontactno = $mycontact['User_ContactNo'];
        $myemail = User::find()->where('username = :username',[':username'=>Yii::$app->user->identity->username])->one();
        $myemail = $myemail['email'];
        $fullname = $mycontact['User_FirstName'].' '.$mycontact['User_LastName'];
        //var_dump($fullname);exit;
        $order = Orders::find()->where('Delivery_ID = :Delivery_ID',[':Delivery_ID' => $did])->one();

        if($order->Orders_Status !="Not Placed")
        {
            Yii::$app->session->setFlash('error', 'Error');

            return $this->redirect(Yii::$app->request->referrer);
        }

        $checkout = new Orders;
        $userbalance = Accountbalance::find()->where('User_Username = :User_Username',[':User_Username' => $order->User_Username])->one();
        $session = Yii::$app->session;
       
        if (Yii::$app->request->post())
        {
            $checkout->load(Yii::$app->request->post());
            
            if (empty($checkout['Orders_Location']) || empty($checkout['Orders_Area']) || empty($checkout['Orders_PaymentMethod'])) {
                Yii::$app->session->setFlash('error', 'Please fill in information correctly!');
                return $this->render('checkout', ['did'=>$did, 'mycontactno'=>$mycontactno, 'myemail'=>$myemail, 'fullname'=>$fullname, 'checkout'=>$checkout, 'session'=>$session]);
            }
            
            $timenow = Yii::$app->formatter->asTime(time());
            $early = date('08:00:00');
            //$last = date('11:00:59');
            $last = date('23:00:59');

            if ($early <= $timenow && $last >= $timenow)
            {
                $earlydiscount = CartController::actionRoundoff1decimal($order['Orders_Subtotal']) * 0.2;
                $earlydiscount = CartController::actionRoundoff1decimal($earlydiscount);
                $newtotalprice = CartController::actionRoundoff1decimal(CartController::actionRoundoff1decimal($order['Orders_Subtotal']) - $earlydiscount + CartController::actionRoundoff1decimal($order['Orders_DeliveryCharge']));
                
                $early = "UPDATE orders SET Orders_TotalPrice = ".$newtotalprice.", Orders_DiscountEarlyAmount = ".$earlydiscount." WHERE Delivery_ID = ".$did."";
                Yii::$app->db->createCommand($early)->execute();

                $order = Orders::find()->where('Delivery_ID = :Delivery_ID',[':Delivery_ID' => $did])->one();

                $unitno = $checkout->Orders_Location;
                $street = $checkout->Orders_Area;
                $paymethod = $checkout->Orders_PaymentMethod;

                $location = $unitno.', '.$street;
                $time = time();
                //var_dump($order);exit;
                date_default_timezone_set("Asia/Kuala_Lumpur");
                $setdate = date("Y-m-d");
                $settime = "13:00:00";

                if ($checkout->Orders_PaymentMethod == 'Account Balance') 
                {
                    $payment = PaymentController::Payment($did,$order);
                    if(!$payment)
                    {
                         Yii::$app->session->setFlash('warning', 'Payment failed! Insufficient Funds.');
                        
                         return $this->render('checkout', ['did'=>$did, 'mycontactno'=>$mycontactno, 'myemail'=>$myemail, 'fullname'=>$fullname, 'checkout'=>$checkout, 'session'=>$session]);
                    }

                } 
               
                //$valid = $this->actionAssignDeliveryMan($did);
                $valid = true;
                if ($valid == false) {
                    return $this->render('checkout', ['did'=>$did, 'mycontactno'=>$mycontactno, 'myemail'=>$myemail, 'fullname'=>$fullname, 'checkout'=>$checkout, 'session'=>$session]);
                }
                $voucher = Vouchers::find()->where('code = :c',[':c' => $discountcode])->all();
                foreach ($voucher as $k => $vou) 
                {
                    if ($order['Orders_TotalPrice'] >0) 
                    {
                        if (!empty($vou))
                        {
                            $code = $vou->code;
                            $valid = ValidController::DateValidCheck($code,1);
                        }
                        else if (empty($vou))
                        {
                            $valid = false;
                        }
                        if ($valid == true ) 
                        {
                            $user = UserVoucher::find()->where('uid = :person and code = :c', [':person'=>Yii::$app->user->identity->id, ':c'=>$vou['code']])->one();
                            if (!empty($user))
                            {
                                if ($vou['discount_type'] == 2 || $vou['discount_type'] == 5) {
                                    $lasttotal = $order['Orders_TotalPrice'];

                                    // -------------detect discount item, do discount--------------------
                                    if ($vou['discount_item'] == 7) 
                                    {
                                        $disamount = $order['Orders_Subtotal'];
                                        $dis = DiscountController::Discount($vou['id'],$order['Orders_Subtotal']);
                                        $order['Orders_Subtotal'] = $dis;
                                        $order['Orders_TotalPrice'] = $dis + $order['Orders_DeliveryCharge'];
                                    }
                                    elseif ($vou['discount_item'] == 8) 
                                    {
                                        $disamount = $order['Orders_DeliveryCharge'];
                                        $dis = DiscountController::Discount($vou['id'],$order['Orders_DeliveryCharge']);
                                        $order['Orders_DeliveryCharge'] = $dis;
                                        $order['Orders_TotalPrice'] = $order['Orders_Subtotal'] + $dis;
                                    }
                                    elseif ($vou['discount_item'] == 9) 
                                    {
                                        $disamount = $order['Orders_TotalPrice'];
                                        $dis = DiscountController::Discount($vou['id'],$order['Orders_TotalPrice']);
                                        $order['Orders_TotalPrice'] = $dis;
                                    }

                                    // --------------discount cannot become negative number, if does, become 0 ---------------
                                    if ($dis < 0) 
                                    {
                                        switch ($vou['discount_item']) 
                                        {
                                            case 7:
                                                $order['Orders_Subtotal'] = 0.00;
                                                $order['Orders_TotalPrice'] = $dis + $order['Orders_DeliveryCharge'];
                                                break;
                                            case 8:
                                                $order['Orders_DeliveryCharge'] = 0.00;
                                                $order['Orders_TotalPrice'] = $order['Orders_Subtotal'] + $dis;
                                                break;
                                            case 9:
                                                $order['Orders_TotalPrice'] = 0.00;
                                                break;
                                            
                                            default:
                                                Yii::$app->session->setFlash('error', 'Error!');
                                                return $this->render('checkout', ['did'=>$did, 'mycontactno'=>$mycontactno, 'myemail'=>$myemail, 'fullname'=>$fullname, 'checkout'=>$checkout, 'session'=>$session]);
                                                break;
                                        }

                                        if ( $order['Orders_TotalPrice']<=0) {
                                            $order['Orders_TotalPrice'] = 0.00;
                                        }
                                    } 
                                    
                                    // -------------detect code or voucher, record--------------
                                    if ($vou['discount_type'] >= 1 && $vou['discount_type']<= 3) 
                                    {
                                        $d = DiscountController::Reversediscount($vou['id'],$disamount);
                                        $v = (($d/$lasttotal)*100);
                                        $order['Orders_DiscountVoucherAmount'] = ($order['Orders_DiscountVoucherAmount'] + CartController::actionRoundoff1decimal(CartController::actionRoundoff1decimal($v)))/($k+1);
                                        
                                    }
                                    elseif ($vou['discount_type'] >= 4 && $vou['discount_type']<= 6) 
                                    {
                                        $d = DiscountController::Reversediscount($vou['id'],$order['Orders_DeliveryCharge']);
                                        $order['Orders_DiscountCodeAmount'] += CartController::actionRoundoff1decimal(CartController::actionRoundoff1decimal($d));
                                    }

                                    // -----save order-------
                                    $vou['discount_type'] += 1;
                                    $vou['usedTimes'] += 1;
                                    
                                    if ($order->validate() && $vou->validate()) 
                                    {
                                        $vou->save();
                                        $order->save();
                                    }
                                    else
                                    {
                                        Yii::$app->session->setFlash('error', 'Failed to place order! Please contact customer service.');

                                        return $this->render('checkout', ['did'=>$did, 'mycontactno'=>$mycontactno, 'myemail'=>$myemail, 'fullname'=>$fullname, 'checkout'=>$checkout, 'session'=>$session]);
                                    }
                                }
                            }
                        }
                    }
                }

                /* calculating discount for orders 
                
                $checkdiscounts = Orders::find()->where('Delivery_ID = :did', [':did' => $did])->one();
                $totaldiscount = 0;
                if ($checkdiscounts['Orders_DiscountVoucherAmount'] != 0 && $checkdiscounts['Orders_DiscountEarlyAmount'] != 0 && $checkdiscounts['Orders_DiscountCodeAmount'] != 0)
                {
                    $totaldiscount = $checkdiscounts['Orders_DiscountEarlyAmount'] + $checkdiscounts['Orders_DiscountVoucherAmount'] + $checkdiscounts['Orders_DiscountCodeAmount'];
                }
                elseif ($checkdiscounts['Orders_DiscountEarlyAmount'] == 0 && $checkdiscounts['Orders_DiscountVoucherAmount'] != 0 && $checkdiscounts['Orders_DiscountCodeAmount'] != 0)
                {
                    $totaldiscount = $checkdiscounts['Orders_DiscountCodeAmount'] + $checkdiscounts['Orders_DiscountVoucherAmount'];
                }
                elseif ($checkdiscounts['Orders_DiscountEarlyAmount'] != 0 && $checkdiscounts['Orders_DiscountVoucherAmount'] == 0 && $checkdiscounts['Orders_DiscountCodeAmount'] != 0)
                {
                    $totaldiscount = $checkdiscounts['Orders_DiscountCodeAmount'] + $checkdiscounts['Orders_DiscountEarlyAmount'];
                }
                elseif ($checkdiscounts['Orders_DiscountEarlyAmount'] != 0 && $checkdiscounts['Orders_DiscountVoucherAmount'] != 0 && $checkdiscounts['Orders_DiscountCodeAmount'] == 0)
                {
                    $totaldiscount = $checkdiscounts['Orders_DiscountVoucherAmount'] + $checkdiscounts['Orders_DiscountEarlyAmount'];
                }
                elseif ($checkdiscounts['Orders_DiscountEarlyAmount'] != 0 && $checkdiscounts['Orders_DiscountVoucherAmount'] == 0 && $checkdiscounts['Orders_DiscountCodeAmount'] == 0)
                {
                    $totaldiscount = $checkdiscounts['Orders_DiscountEarlyAmount'];
                }
                elseif ($checkdiscounts['Orders_DiscountEarlyAmount'] == 0 && $checkdiscounts['Orders_DiscountVoucherAmount'] != 0 && $checkdiscounts['Orders_DiscountCodeAmount'] == 0)
                {
                    $totaldiscount = $checkdiscounts['Orders_DiscountVoucherAmount'];
                }
                elseif ($checkdiscounts['Orders_DiscountEarlyAmount'] == 0 && $checkdiscounts['Orders_DiscountVoucherAmount'] == 0 && $checkdiscounts['Orders_DiscountCodeAmount'] != 0)
                {
                    $totaldiscount = $checkdiscounts['Orders_DiscountCodeAmount'];
                }
                elseif ($checkdiscounts['Orders_DiscountEarlyAmount'] == 0 && $checkdiscounts['Orders_DiscountVoucherAmount'] == 0 && $checkdiscounts['Orders_DiscountCodeAmount'] == 0)
                {
                    $totaldiscount = 0;
                }
                */
              
                $sql = "UPDATE orders SET Orders_Location= '".$location."', Orders_Area = '".$session['area']."', Orders_Postcode = '".$session['postcode']."', Orders_PaymentMethod = '".$paymethod."', Orders_Status = 'Pending', Orders_DateTimeMade = '".$time."', Orders_Date = '".$setdate."', Orders_Time = '".$settime."', Orders_DiscountTotalAmount = '".$totaldiscount."' WHERE Delivery_ID = '".$did."'";

                Yii::$app->db->createCommand($sql)->execute();
                $sql2 = "UPDATE orderitem SET OrderItem_Status = 'Pending' WHERE Delivery_ID = '".$did."'";
                Yii::$app->db->createCommand($sql2)->execute();

                $timedate = Orders::find()->where('Delivery_ID = :did', [':did'=>$did])->one();

                $ordersstatuschange = new Ordersstatuschange();

                $ordersstatuschange->Delivery_ID = $did;
                $ordersstatuschange->OChange_PendingDateTime = $time;

                $ordersstatuschange->save();

                $orderitems = Orderitem::find()->where('Delivery_ID = :did', [':did'=>$did])->all();
                foreach ($orderitems as $orderitems) :
                    $orderitemstatuschange = new Orderitemstatuschange;

                    $orderitemstatuschange->Order_ID = $orderitems['Order_ID'];
                    $orderitemstatuschange->Change_PendingDateTime = $time;

                    $orderitemstatuschange->save();
                endforeach;

                $orderitemquantity = Orderitem::find()->where('Delivery_ID = :did', [':did'=>$did])->all();

                foreach ($orderitemquantity as $orderitemquantity) :
                    $quantity = $orderitemquantity['OrderItem_Quantity'];

                    $foodprevbought = food::find()->where('Food_ID = :fid', [':fid'=>$orderitemquantity['Food_ID']])->one();
                    $foodprevbought = $foodprevbought['Sales'];

                    $totalbought = $quantity + $foodprevbought;
                    $sqll = "UPDATE food SET Sales = ".$totalbought." WHERE Food_ID = ".$orderitemquantity['Food_ID']."";
                    Yii::$app->db->createCommand($sqll)->execute();
                endforeach;

                $session = Yii::$app->session;
                $session->close();
                NotificationController::createNotification($did,1);
                MemberpointController::addMemberpoint($order->Orders_TotalPrice,1);
               
            }
            else
            {
                Yii::$app->session->setFlash('error', 'The allowed time to place order is over. Please place your order in between 8am and 11am daily.');
            }
            
            return $this->redirect(['aftercheckout','did'=>$did]);
        }
        return $this->render('checkout', ['did'=>$did, 'mycontactno'=>$mycontactno, 'myemail'=>$myemail, 'fullname'=>$fullname, 'checkout'=>$checkout, 'session'=>$session]);
    }

    /*
    * to prevent post duplicate data
    */
    public function actionAftercheckout($did)
    {
        $timedate =Orders::findOne($did);
        return $this->render('aftercheckout', ['did'=>$did, 'timedate'=>$timedate ]);
    }

    public function actionGetdiscount($dis)
    {
       $valid = UserVoucher::find()->where('code = :c',[':c'=>$dis])->one();
       if (!empty($valid)) 
        {
            $value = Vouchers::find()->where('code = :c',[':c'=>$dis])->one();
            if ($value['discount_type'] == 2 || $value['discount_type'] == 5) 
            {
                if ($valid->endDate > date('Y-m-d')) 
                {
                  $value = Vouchers::find()->where('code = :c',[':c'=>$dis])->one();
                }
                elseif ($valid->endDate < date('Y-m-d')) 
                {
                    $value = 0;
                }
            }
            else
            {
                $value = 0;
            }
            
        }
       elseif(empty($valid)) {
       
        $value = 0;
       }
       $value = Json::encode($value);

       return $value;
    }

    public function actionDelete($oid)
    {
        $menu = orderitem::find()->where('Order_ID = :id' ,[':id' => $oid])->one();
        $linetotal = $menu['OrderItem_LineTotal'];
        $orders = Orders::find()->where('Delivery_ID = :did', [':did'=>$menu['Delivery_ID']])->one();
        $prevtotal = $orders['Orders_TotalPrice'];
        $newtotal = $prevtotal - $linetotal;
        $newsubtotal = $orders['Orders_Subtotal'] - $linetotal;

        $sql1 = "UPDATE orders SET Orders_TotalPrice = ".$newtotal.", Orders_Subtotal = ".$newsubtotal." WHERE Delivery_ID = ".$menu['Delivery_ID']."";
        Yii::$app->db->createCommand($sql1)->execute();

         $sql = "DELETE FROM orderitem WHERE Order_ID = '$oid'";
         Yii::$app->db->createCommand($sql)->execute();

         $noofrestaurants = "SELECT DISTINCT food.Restaurant_ID FROM food INNER JOIN orderitem ON orderitem.Food_ID = food.Food_ID WHERE orderitem.Delivery_ID = ".$menu['Delivery_ID']."";
         $result = Yii::$app->db->createCommand($noofrestaurants)->execute();
         $deliverycharge = $result * 5;

         $sql2 = "UPDATE orders SET Orders_DeliveryCharge = ".$deliverycharge." WHERE Delivery_ID = ".$menu['Delivery_ID']."";
         Yii::$app->db->createCommand($sql2)->execute();

         $neworders = Orders::find()->where('Delivery_ID = :did', [':did'=>$menu['Delivery_ID']])->one();
         $newtotal = $neworders['Orders_Subtotal'] + $neworders['Orders_DeliveryCharge'];

         $sql3 = "UPDATE orders SET Orders_TotalPrice = ".$newtotal." WHERE Delivery_ID = ".$menu['Delivery_ID']."";
         Yii::$app->db->createCommand($sql3)->execute();

         $orders = Orders::find()->where('Delivery_ID = :did', [':did'=>$menu['Delivery_ID']])->one();

         if($orders['Orders_TotalPrice'] == 0 && $orders['Orders_Subtotal'] == 0 && $orders['Orders_DeliveryCharge'] == 0)
         {
             $sql4 = "DELETE FROM orders WHERE Delivery_ID = ".$menu['Delivery_ID']."";
             Yii::$app->db->createCommand($sql4)->execute();
         }

         return $this->redirect(['view-cart']);
    }

    public static function actionDisplay2decimal($price)
    {
        return number_format((float)$price,2,'.','');
    }


    public static function actionRoundoff1decimal($price)
    {
        return self::actionDisplay2decimal(number_format((float)$price,1,'.',''));
    }
}