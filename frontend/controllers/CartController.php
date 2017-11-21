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
use common\models\Area;
use common\models\Vouchers;
use common\models\UserVoucher;
use common\models\VouchersType;
use common\models\user\Userdetails;
use common\models\user\Useraddress;
use common\models\Ordersstatuschange;
use common\models\Orderitemstatuschange;
use common\models\Restaurant;
use common\models\Account\Accountbalance;
use common\models\Cart\Cart;
use common\models\Cart\CartSelection;
use frontend\models\Deliveryman;
use frontend\controllers\PaymentController;
use frontend\controllers\MemberpointController;
use frontend\controllers\NotificationController;
use yii\helpers\Json;
use frontend\modules\delivery\controllers\DailySignInController;
use frontend\modules\UserPackage\controllers\SelectionTypeController;
use frontend\controllers\CommonController;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\filters\AccessControl;
use common\models\Object;
use yii\web\Session;

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
                        'actions' => ['addto-cart','checkout','delete','view-cart','aftercheckout','getdiscount','newaddress','editaddress','getaddress','assign-delivery-man','addsession','get-area'],

                        'allow' => true,
                        'roles' => ['@'],
                    ],
                    //['actions' => [],'allow' => true,'roles' => ['?'],],
                    
                 ]
             ]
        ];
    }

//--This function continues on from FoodController's actionFoodDetails and adds a food item to cart
    public function actionAddtoCart($id)
    {
        $cart = new Cart;
        $cartSelection = new CartSelection;
        $post = Yii::$app->request->post();
        $cart->load(Yii::$app->request->post());
        //$cartSelection->load(Yii::$app->request->post());
        if(empty($post))
        {
            Yii::$app->session->setFlash('error', "Something Went Wrong!");
            return $this->redirect(['site/index']);
        }

        $session = Yii::$app->session;
        $food = food::find()->where('food.Food_ID = :id',[':id'=> $id])->joinWith(['restaurant','foodSelection'])->one();
        
        if(empty($session['group']) || $session['group'] != $food['restaurant']['Restaurant_AreaGroup'])
        {
            Yii::$app->session->setFlash('error', "This item is in a different area from your area. Please re-enter your area.");
            return $this->redirect(['site/index']);
        }

        $minMaxValidate = self::detectEmptySelection($post,$id);

        if($minMaxValidate == 3)
        {
            return $this->redirect(Yii::$app->request->referrer);
        }
       
        $price = self::cartPrice($post,$food);

        $cart->area = $session['group'];
        $cart->fid = $id;
        $cart->uid = Yii::$app->user->identity->id;
        $cart->price = $price[0];
        $cart->selectionprice = $price[1];

        $valid = $cart->validate();

        if($valid)
        {
            $transaction = Yii::$app->db->beginTransaction();
            try{
                $cart->save();
                if($minMaxValidate == 2)
                {
                    $selection = CommonController::removeNestedArray($post['CartSelection']);
                    foreach($selection as $selectionid)
                    {
                        $cartSelection = new cartSelection;
                        $cartSelection->cid = $cart->id;
                        $cartSelection->selectionid = $selectionid;
                        if($cartSelection->save())
                        {
                            $valid = true;
                        }
                        else
                        {
                            break;
                        }
                        
                    }
                }

                if($valid)
                {
                    $transaction->commit();
                     Yii::$app->session->setFlash('success', 'Food item has been added to cart. '.Html::a('<u>Go to my Cart</u>', ['/cart/view-cart']).'.');
                    return $this->redirect(Yii::$app->request->referrer);
                }
                $transaction->rollBack();
            }
            catch(Exception $e)
            {
                $transaction->rollBack();
            }

        }

        Yii::$app->session->setFlash('warning', "Fail To Add To Cart Please try later");
        return $this->redirect(Yii::$app->request->referrer);
    }

//--This function load's the user's current cart and its details
    public function actionViewCart()
    {
        $time['now'] = Yii::$app->formatter->asTime(time());
        $time['early'] = date('08:00:00');
        $time['late'] = date('23:00:59');

        $groupCart = [];
        
        $cart = Cart::find()->where('uid = :uid',[':uid' => Yii::$app->user->identity->id])->joinWith(['food','selection'])->all();

        $voucher = ArrayHelper::map(UserVoucher::find()->where('uid=:uid',[':uid'=>Yii::$app->user->identity->id])->andWhere(['>=','endDate',time(date("Y-m-d"))])->all(),'code','code');
        $ren = new VouchersType;

        foreach($cart as $i=> $single)
        {
            if(!empty($single['selection']))
            {
                foreach($single['selection'] as $selection)
                {
                    $data = foodSelection::find()->where('foodselection.ID = :id',[':id' => $selection->selectionid])->joinWith('selectedtpye')->one();

                        //$count = count($groupSelection);
                        /*if($count > 0)
                        {
                             $groupSelection[$data['selectedtpye']['TypeName']] .= ',';
                        }*/
                    $groupSelection[$data['selectedtpye']['TypeName']][] = $data['Name'];
                }
                
               $cart[$i]->groupselection = $groupSelection;  
            }

           $groupSelection = [];
        }

        foreach($cart as $single)
        {
            $groupCart[$single['area']][] = $single;
        }


        return $this->render('cart',['groupCart' => $groupCart,'time' => $time,'voucher'=>$voucher,'ren'=>$ren]);
    }

    public function actionAddsession()
    {
        $model = new Area;
        $postcodeArray = ArrayHelper::map(Area::find()->all(),'Area_Postcode','Area_Postcode');
        $this->layout = 'content';
        if (Yii::$app->request->post()) 
        {
            $model->load(Yii::$app->request->post());
            $groupArea = Area::find()->where('Area_Postcode = :p and Area_Area = :a',[':p'=> $model['Area_Postcode'] , ':a'=>$model['Area_Area']])->one()->Area_Group;
            $session = new Session;
            $session->open();
            $session['postcode'] = $model['Area_Postcode'];
            $session['area'] = $model['Area_Area'];
            $session['group'] = $groupArea;

            return $this->redirect(['/cart/view-cart']);
        }
        return $this->render('addsession',['model'=>$model,'postcodeArray'=>$postcodeArray]);
    }

    /* Function for dependent dropdown in frontend index page. */
    public function actionGetArea()
    {
    if (isset($_POST['depdrop_parents'])) {
        $parents = $_POST['depdrop_parents'];
        if ($parents != null) {
            $cat_id = $parents[0];
            $out = self::getAreaList($cat_id); 
            echo json_encode(['output'=>$out, 'selected'=>'']);
            return;
        }
    }
    echo json_encode(['output'=>'', 'selected'=>'']);
    }

    public static function getAreaList($postcode)
    {
        $area = Area::find()->where(['like','Area_Postcode' , $postcode])->select(['Area_ID', 'Area_Area'])->all();
        $areaArray = [];
        foreach ($area as $area) {
            $object = new Object();
            $object->id = $area['Area_Area'];
            $object->name = $area['Area_Area'];

            $areaArray[] = $object;
        }
        return $areaArray;
    }

//--This function is to assign a delivery man when an order has been placed
    public static function assignDeliveryMan($area)
    {
       $data = DailySignInController::getAllDailyRecord($area);
      
        if(empty($data))
        {
              Yii::$app->session->setFlash('error', 'Sorry! We have insufficient of deliveryman, please try after 10 minutes or contact our customer service for more information.');
            return -1;
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

        $user = User::findOne($uid);
       
        return $user->username;
    }

//--This function is to process the order officially as the places his order
    public function actionCheckout()
    {
        $checkout = Orders::find()->where('Delivery_ID = :Delivery_ID',[':Delivery_ID' => $did])->one();
        $uid = User::find()->where('username = :u',[':u'=>$checkout['User_Username']])->one()->id;
        $check = ValidController::checkUserValid($uid);
        if ($check == false) {
            return $this->redirect(['site/index']);
        }
        $details = Userdetails::find()->where('User_id = :uid',[':uid'=>Yii::$app->user->identity->id])->one();
        $address = Useraddress::find()->where('uid = :uid',[':uid'=>Yii::$app->user->identity->id])->orderBy('level DESC')->all();
        $addressmap =  ArrayHelper::map($address, 'id', 'address');
        $email = User::find()->where('id = :id',[':id'=>Yii::$app->user->identity->id])->one()->email;
        if($checkout->Orders_Status !="Not Placed")
        {
            Yii::$app->session->setFlash('error', 'Error');

            return $this->redirect(Yii::$app->request->referrer);
        }

        $userbalance = Accountbalance::find()->where('User_Username = :User_Username',[':User_Username' => $checkout->User_Username])->one();
        $session = Yii::$app->session;

        if (Yii::$app->request->post())
        {

            $checkout->load(Yii::$app->request->post());
            if (empty($checkout['Orders_Location']) || empty($checkout['Orders_PaymentMethod']) || empty($checkout['User_contactno']) || empty($checkout['User_fullname'])) {
                Yii::$app->session->setFlash('error', 'Please fill in all information correctly!');
                return $this->render('checkout', ['did'=>$did, 'checkout'=>$checkout, 'session'=>$session,'email'=>$email,'details'=>$details,'address'=>$address,'addressmap'=>$addressmap]);
            }
            $orderlocation = Useraddress::find()->where('id = :id',[':id'=>$checkout['Orders_Location']])->one();
            if ($session['postcode']!= $orderlocation['postcode']) {
                Yii::$app->session->setFlash('error', 'Your address postcode is not the same with delivery postcode!');
                return $this->render('checkout', ['did'=>$did, 'checkout'=>$checkout, 'session'=>$session,'email'=>$email,'details'=>$details,'address'=>$address,'addressmap'=>$addressmap]);
            }
            $checkout['Orders_Location'] = $orderlocation->address;
            $timenow = Yii::$app->formatter->asTime(time());
            $early = date('08:00:00');
            //$last = date('11:00:59');
            $last = date('23:00:59');
//----------Checks if user is eligible for early discount and if user placed his order within the time 8am to 11am
            
            if ($early <= $timenow && $last >= $timenow)
            {

                $earlydiscount = CartController::actionRoundoff1decimal($checkout['Orders_Subtotal']) * 0.2;
                $earlydiscount = CartController::actionRoundoff1decimal($earlydiscount);
                $newtotalprice = CartController::actionRoundoff1decimal(CartController::actionRoundoff1decimal($checkout['Orders_Subtotal']) - $earlydiscount + CartController::actionRoundoff1decimal($checkout['Orders_DeliveryCharge']));
                
                $early = "UPDATE orders SET Orders_TotalPrice = ".$newtotalprice.", Orders_DiscountEarlyAmount = ".$earlydiscount." WHERE Delivery_ID = ".$did."";
                Yii::$app->db->createCommand($early)->execute();

                $order = Orders::find()->where('Delivery_ID = :Delivery_ID',[':Delivery_ID' => $did])->one();

                $time = time();
                date_default_timezone_set("Asia/Kuala_Lumpur");
                $setdate = date("Y-m-d");
                $settime = "13:00:00";

                
//--------------A delivery man is assigned to the order here
                //$valid = $this->actionAssignDeliveryMan($did);
                $valid = true;
                if ($valid == false) {
                    return $this->render('checkout', ['did'=>$did, 'checkout'=>$checkout, 'session'=>$session,'email'=>$email,'details'=>$details,'address'=>$address,'addressmap'=>$addressmap]);
                }

                $voucher = Vouchers::find()->where('code = :c',[':c' => $discountcode])->all();

                if (!empty($voucher)) 
                {
                    $d = 0;
                    foreach ($voucher as $k => $vou) 
                    {
                        if ($order['Orders_TotalPrice'] >0) 
                        {
                            $code = $vou->code;
                            if ($vou['discount_type'] != 100 || $vou['discount_type'] != 101){
                                $valid = true;
                            }
                            elseif (!empty($vou)) {
                                $valid = ValidController::DateValidCheck($code,1);
                            }
                            else if (empty($vou)){
                                $valid = false;
                            }
                            if ($valid == true ) 
                            {
                                $user = UserVoucher::find()->where('uid = :person and code = :c', [':person'=>Yii::$app->user->identity->id, ':c'=>$vou['code']])->one();
                                if (!empty($user) || $vou['discount_type'] == 100 || $vou['discount_type'] == 101)
                                {
                                    if ($vou['discount_type'] == 2 || $vou['discount_type'] == 5 || $vou['discount_type'] == 100 || $vou['discount_type'] == 101) {

                                        // -------------detect discount item, do discount--------------------
                                        if ($vou['discount_item'] == 7) 
                                        {
                                            $dis = DiscountController::Discount($vou['id'],$order['Orders_Subtotal']);
                                            if ($dis <= 0) {
                                                $dis = 0.00;
                                            }
                                            $d = DiscountController::Reversediscount($vou['id'],$order['Orders_Subtotal']);
                                            $order['Orders_Subtotal'] = $dis;
                                            $order['Orders_TotalPrice'] = $dis + $order['Orders_DeliveryCharge'];

                                            
                                        }
                                        elseif ($vou['discount_item'] == 8) 
                                        {
                                            $dis = DiscountController::Discount($vou['id'],$order['Orders_DeliveryCharge']);
                                            if ($dis <= 0) {
                                                $dis = 0.00;
                                            }
                                            $d = DiscountController::Reversediscount($vou['id'],$order['Orders_DeliveryCharge']);
                                            $order['Orders_DeliveryCharge'] = $dis;
                                            $order['Orders_TotalPrice'] = $order['Orders_Subtotal'] + $dis;
                                            
                                        }
                                        elseif ($vou['discount_item'] == 9) 
                                        {
                                            $dis = DiscountController::Discount($vou['id'],$order['Orders_TotalPrice']);
                                            if ($dis <= 0) {
                                                $dis = 0.00;
                                            }
                                            $d = DiscountController::Reversediscount($vou['id'],$order['Orders_TotalPrice']);
                                            $order['Orders_TotalPrice'] = $dis;
                                            
                                            
                                        }
                                        // -------------detect code or voucher, record--------------
                                        if (($vou['discount_type'] >= 1 && $vou['discount_type']<= 3) || $vou['discount_type'] == 100) 
                                        {
                                            $order['Orders_DiscountVoucherAmount'] += CartController::Roundoff($d,2);
                                            
                                        }
                                        elseif (($vou['discount_type'] >= 4 && $vou['discount_type']<= 6) || $vou['discount_type'] == 101) 
                                        {
                                            $order['Orders_DiscountCodeAmount'] += CartController::Roundoff($d,2);
                                        }

                                        if ($vou['discount_type'] != 100) {
                                            if ($vou['discount_type'] != 101) {
                                                $vou['discount_type'] += 1;
                                                $vou['usedTimes'] += 1;
                                            }
                                        }

                                        // -----save voucher-------
                                        if ($vou->validate()) 
                                        {
                                            $vou->save();
                                        }
                                        else
                                        {
                                            Yii::$app->session->setFlash('error', 'Failed to place order! Please contact customer service.');

                                            return $this->render('checkout', ['did'=>$did, 'checkout'=>$checkout, 'session'=>$session,'email'=>$email,'details'=>$details,'address'=>$address,'addressmap'=>$addressmap]);
                                            
                                        }
                                    }
                                }
                            }
                        }
                    }
                    // ---------save order ----------
                    $order['Orders_TotalPrice'] =  $order['Orders_TotalPrice'] -  $order['Orders_DiscountEarlyAmount'];
                    if ($order->validate()) {
                        $order->save();
                    }
                }

                // account balance functions
                if ($checkout->Orders_PaymentMethod == 'Account Balance') 
                {
                    $payment = PaymentController::Payment($did,$order);
                    if(!$payment)
                    {
                         Yii::$app->session->setFlash('warning', 'Payment failed! Insufficient Funds.');
                        
                         return $this->render('checkout', ['did'=>$did, 'checkout'=>$checkout, 'session'=>$session,'email'=>$email,'details'=>$details,'address'=>$address,'addressmap'=>$addressmap]);
                    }

                }

//--------------The total discount for the order is calculated here
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

                $checkout['Orders_Area'] = $session['area'];
                $checkout['Orders_Postcode'] = $session['postcode'];
                $checkout['Orders_Status'] = 'Pending';
                $checkout['Orders_DateTimeMade'] = $time;
                $checkout['Orders_Date'] = $setdate;
                $checkout['Orders_Time'] = $settime;
                $checkout['Orders_DiscountTotalAmount'] = $totaldiscount;

                if ($checkout->validate()) {
                    $checkout->save();
                }
                else{
                    Yii::$app->session->setFlash('error', 'Failed to place order! Please contact customer service.');

                    return $this->render('checkout', ['did'=>$did, 'checkout'=>$checkout, 'session'=>$session,'email'=>$email,'details'=>$details,'address'=>$address,'addressmap'=>$addressmap]);
                }

//--------------The statuses and time for the order and its' order items are updated here
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
                //$session->close();
                NotificationController::createNotification($did,3);
                MemberpointController::addMemberpoint($order->Orders_TotalPrice,1);
               
            }
            else
            {
                
                Yii::$app->session->setFlash('error', 'The allowed time to place order is over. Please place your order in between 8am and 11am daily.');
                return $this->redirect(Yii::$app->request->referrer);
            }
            
            return $this->redirect(['aftercheckout','did'=>$did]);
        }
        return $this->render('checkout', ['did'=>$did, 'checkout'=>$checkout, 'session'=>$session,'email'=>$email,'details'=>$details,'address'=>$address,'addressmap'=>$addressmap]);
    }

    public function actionNewaddress()
    {
        $count = Useraddress::find()->where('uid = :uid',[':uid' => Yii::$app->user->identity->id])->count();
        if($count >= 3)
        {
             Yii::$app->session->setFlash('danger', ' Reach Max Limit 3');
              return $this->redirect(Yii::$app->request->referrer);
        }

        $model = new Useraddress;
        if($model->load(Yii::$app->request->post()))
        {
            $model->uid = Yii::$app->user->identity->id;
                
            if($model->save())
            {
                if($model->level == 1)
                {
                    Useraddress::updateAll(['level' => 0],'uid = :uid AND id != :id',[':uid' => Yii::$app->user->identity->id,':id'=> $model->id]);
                }
                     Yii::$app->session->setFlash('success', 'Successfully create new address');
            }
            else
            {
                Yii::$app->session->setFlash('danger', ' Address Add Fail');
            }
            return $this->redirect(Yii::$app->request->referrer);
        }
        $this->layout = 'user';
        $this->view->title = 'Add New Address';
        return $this->renderAjax('newaddress',['model'=>$model]);
    }

    public function actionEditaddress()
    {
        $model = new Useraddress;
        $address = ArrayHelper::map(Useraddress::find()->where('uid=:id',['id'=>Yii::$app->user->identity->id])->orderBy('level ASC')->all(),'id','address');
        $first = Useraddress::find()->where('uid=:id',['id'=>Yii::$app->user->identity->id])->orderBy('level ASC')->one();

        if (Yii::$app->request->post()) 
        {
            $model->load(Yii::$app->request->post());
            $addr = Useraddress::find()->where('id=:id',['id'=>$model['address']])->one();
            $addr['postcode'] = $model['postcode'];
            $addr['city'] = $model['city'];
            if ($addr->validate()) {
                $addr->save();
                Yii::$app->session->setFlash('success', 'Success!');
            }
            else{
                Yii::$app->session->setFlash('error', 'Failed to edit!');
            }
            return $this->redirect(Yii::$app->request->referrer);
        }

        return $this->renderAjax('editaddress',['model'=>$model,'address'=>$address,'first'=>$first]);
    }

    public function actionGetaddress($addr)
    {
        $add = Useraddress::find()->where('id=:id',['id'=>$addr])->one();

        $value = Json::encode($add);
        return $value;
    }

    /*
    * to prevent post duplicate data
    */
    public function actionAftercheckout($did)
    {
        $order = PaymentController::findOrder($did);
        
        if($order->Orders_Status == "Not Paid")
        {
            return $this->redirect(['/payment/process-payment','did'=>$did]);
        }
        else
        {
            $orderitem = Orderitem::find()->joinWith('food')->where('Delivery_ID=:id',[':id'=>$did])->orderBy('Delivery_ID ASC')->all();
            return $this->render('aftercheckout', ['did'=>$did, 'order'=>$order,'orderitem'=>$orderitem ]);
        }
       
    }

    public function actionGetdiscount($dis,$sub,$deli,$total)
    { // ajax's function must do in one controller, can't pass to second
        if (empty($dis)) {
            $value=  Json::encode(19);
            return $value;
        }
        $valid = UserVoucher::find()->where('code = :c',[':c'=>$dis])->one();
        $voucher = Vouchers::find()->where('code = :c',[':c'=>$dis])->one();
       if ($voucher['discount_type'] == 100 || $voucher['discount_type'] == 101) {
           $valid['endDate'] = date('Y-m-d',strtotime('+1 day'));
       }
       if (!empty($valid)) 
        {
            if ($voucher['discount_type'] == 2 || $voucher['discount_type'] == 5 || $voucher['discount_type'] == 100 || $voucher['discount_type'] == 101)  
            {
                if ($valid['endDate'] > date('Y-m-d')) 
                {
                    $vouchers = Vouchers::find()->where('code = :c',[':c'=>$dis])->all();
                    $value['sub'] = $sub;
                    $value['deli'] = $deli;
                    $value['total'] = $total;
                    $value['discount'] = 0;
                    foreach ($vouchers as $k => $vou) 
                    {
                        if ($vou['discount_type'] == 1 || $vou['discount_type'] == 2 || $vou['discount_type'] == 100)  
                        {
                            switch ($vou['discount_item']) 
                            {
                                case 7:
                                    $value['discount'] += ($value['sub']* ($vou['discount'] / 100));
                                    /* this 1 count with early discount for percentage
                                    $value['total'] = $value['total'] - ($value['sub']* ($vou['discount'] / 100));
                                    */
                                    $value['sub'] = $value['sub']- ($value['sub']* ($vou['discount'] / 100));
                                    $value['total'] =  $value['sub'] + $value['deli'];
                                    break;

                                case 8:
                                    $value['discount'] += ($value['deli']* ($vou['discount'] / 100));
                                    $value['deli'] = $value['deli']-($value['deli']*($vou['discount'] / 100));
                                    $value['total'] =  $value['sub'] + $value['deli'];
                                    break;

                                case 9:
                                    $value['discount'] += ($value['total']* ($vou['discount'] / 100));
                                    $value['total'] = $value['total'] - ($value['total']*($vou['discount'] / 100));
                                    break;
                                     
                                default:
                                    $value = 0;
                                    break;
                            }
                        }
                        elseif ($vou['discount_type'] == 4 || $vou['discount_type'] == 5 || $vou['discount_type'] == 101) 
                        {
                            switch ($vou['discount_item']) 
                            {
                                case 7:
                                    if (($value['sub']-$vou['discount']) < 0) {
                                        $value['discount'] += $value['sub'];
                                        /*for amount
                                        $value['total'] = $value['total'] - $value['sub']; <0
                                        $value['total'] = $value['total'] - $vou['discount']; else
                                        */
                                        $value['sub'] = 0;
                                    }
                                    else{
                                        $value['discount'] += $vou['discount'];
                                        $value['sub'] = $value['sub'] - $vou['discount'];
                                    }

                                    $value['total'] =  $value['sub'] + $value['deli'];
                                    break;

                                case 8:
                                    if (($value['deli']-$vou['discount']) < 0) {
                                        $value['discount'] += $value['deli'];
                                        $value['deli'] = 0;
                                    }
                                    else{
                                        $value['discount'] += $vou['discount'];
                                        $value['deli'] = $value['deli'] - $vou['discount'];
                                    }
                                    $value['total'] =  $value['sub'] + $value['deli'];
                                    break;

                                case 9:
                                    if (($value['total']-$vou['discount']) < 0) {
                                        $value['discount'] += $value['total'];
                                        $value['total'] = 0;
                                    }
                                    else{
                                        $value['discount'] += $vou['discount'];
                                        $value['total'] = $value['total'] - $vou['discount'];
                                    }
                                    break;
                                     
                                default:
                                    $value = 0;
                                    break;
                            }
                        }
                        else
                        {
                            $value = 0;
                        }
                    }
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
       $value=  Json::encode($value);

       return $value;
    }

    public static function mutipleDelete($cart)
    {
        foreach($cart as $data)
        {
            $data->delete();
        }
    }

//--This function runs when an item in the cart is deleted
    public function actionDelete($id)
    {
        $cart = Cart::findOne($id);
        if(!empty($cart))
        {
            $cart->delete();
        }
       
        //Cart::deleteAll('id = :id',[':id' => $id]);

       /* $menu = orderitem::find()->where('Order_ID = :id' ,[':id' => $oid])->one();
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
         }*/

         return $this->redirect(['view-cart']);
    }

    protected static function detectEmptySelection($post,$id)
    {
        $foodselection = Foodselectiontype::find()->where("Food_ID = :id",[':id' => $id])->all();

        if(empty($post['CartSelection']) && empty($foodselection))
        {
            return 1;
        }
        else
        {
            $isValid = SelectionTypeController::detectMinMaxSelecttion($post['CartSelection']['selectionid'],$foodselection);
            if($isValid)
            {
                return 2;
            }
            else
            {
                return 3;
            }
        }
    }

    /*
    * 0 => total Price
    * 1 => food selection Price
    */
    protected static function cartPrice($post,$food)
    {
        $price[0] = 0;
        $price[1] = 0;
        if(!empty($post['CartSelection']))
        {
            $cartSelection = CommonController::removeNestedArray($post["CartSelection"]);
            $foodSelection = ArrayHelper::map($food['foodSelection'],'ID','Price');
            foreach($cartSelection as $selection)
            {
                $price[1] += $foodSelection[$selection];
            }
        }
        $price[0] = $food->Price + $price[1];
        
        return $price;
    }

    public static function actionDisplay2decimal($price)
    {
        return number_format((float)$price,2,'.','');
    }


    public static function actionRoundoff1decimal($price)
    {
        return self::actionDisplay2decimal(number_format((float)$price,1,'.',''));
    }

    public static function Roundoff($post,$digit)
    {
        return number_format((float)$post,$digit,'.','');
    }
}