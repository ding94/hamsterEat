<?php
namespace frontend\controllers;
use Yii;
use yii\web\Controller;
use common\models\User;
use yii\web\Cookie;
use common\models\food\{Food,Foodselectiontype,Foodselection,Foodstatus};
use common\models\Order\{Orders,Orderitem,DeliveryAddress};
use common\models\Area;
use common\models\vouchers\{Vouchers,DiscountItem,UserVoucher,VouchersSetCondition,VouchersConditions};
use common\models\user\{Userdetails,Useraddress};
use common\models\Restaurant;
use common\models\Cart\{Cart,CartSelection};
use yii\helpers\Json;
use frontend\modules\UserPackage\controllers\SelectionTypeController;
use frontend\controllers\CommonController;
use frontend\modules\offer\controllers\{PromotionController,DetectPromotionController};
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\filters\AccessControl;
use common\models\SelfObject;
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
                        'actions' => ['addto-cart','checkout','delete','view-cart','aftercheckout','getdiscount','newaddress','editaddress','getaddress','assign-delivery-man','addsession','get-area','quantity','totalcart'],

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
        $timevalid = CommonController::getOrdertime();
        if ($timevalid == false) {
            return $this->redirect(Yii::$app->request->referrer);
        }
        $cart = new Cart;
        $cartSelection = new CartSelection;
        $post = Yii::$app->request->post();
        $cart->load(Yii::$app->request->post());
        $data['value'] = 0;
        //$cartSelection->load(Yii::$app->request->post());
        if(empty($post))
        {
            $data['message'] = Yii::t('cart','Exceed Food Order Limit');
            return Json::encode($data);
        }

        $session = Yii::$app->session;
        $food = food::find()->where('food.Food_ID = :id and foodstatus.Status = 1',[':id'=> $id])->joinWith(['restaurant','foodSelection','foodStatus'])->andWhere(['>','food_limit','0'])->one();

        if(empty($food))
        {
            $data['message'] = Yii::t('cart','The Food Is Not Available Or Missing');
            return Json::encode($data);
        }
        
        $detctQuantity = self::detectQuantity($cart->quantity,$food->foodStatus->   food_limit,$id,$session['group']);

        if($detctQuantity['value'] == -1)
        {
            $data['message'] = $detctQuantity['message'];
            return Json::encode($data); 
        }

        if(empty($session['group']) || $session['group'] != $food['restaurant']['Restaurant_AreaGroup'])
        {
            $data['message'] = Yii::t('cart','This item is in a different area from your area. Please re-enter your area.');
            return Json::encode($data);
        }

        $minMaxValidate = self::detectEmptySelection($post,$id);

        if($minMaxValidate['value'] == 3)
        {
            $data['message'] = $minMaxValidate['message'];
           
            return Json::encode($data);
        }

        $availableCart = self::availableCart($id,$post);
        if($availableCart['value'] != 1)
        {
            return Json::encode($availableCart);
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
                if($minMaxValidate['value'] == 2)
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
                    $data['message'] = Yii::t('cart','Food item has been added to cart.').' '.Html::a('<u>'.Yii::t('cart','Go to my Cart').'</u>', ['/cart/view-cart']).'.';
                    $data['value'] = 1;
                    self::generateNickName($cart->quantity,$cart->id);
                    return JSON::encode($data);
                }
                $transaction->rollBack();
            }
            catch(Exception $e)
            {
                $transaction->rollBack();
            }

        }
        $data['message'] = Yii::t('cart','Fail To Add To Cart Please try later');
        $data['value'] = 0;
        
        return JSON::encode($data);
    }

//--This function load's the user's current cart and its details
    public function actionViewCart()
    {
        $groupCart = [];

        $cart = Cart::find()->where('uid = :uid',[':uid' => Yii::$app->user->identity->id])->joinWith(['food','selection','nick'])->all();
        
        foreach($cart as $i=> $single)
        {
            $promotion = PromotionController::getPromotioinPrice($single->price,$single->fid,1);
            if(is_array($promotion))
            {

                $single->promotion_enable = 1;
              
            }
            if(!empty($single['selection']))
            {
                foreach($single['selection'] as $selection)
                {
                    $data = foodSelection::find()->where('foodselection.ID = :id',[':id' => $selection->selectionid])->joinWith('selectedtpye')->one();
                    $groupSelection[$data['selectedtpye']['cookieName']][] = $data['cookieName'];
                }
                
               $cart[$i]->groupselection = $groupSelection;  
            }

           $groupSelection = [];
        }

        foreach($cart as $single)
        {
            $groupCart[$single['area']][] = $single;
           
        }

        return $this->render('cart',['groupCart' => $groupCart]);
    }

    public function actionTotalcart($area)
    {
        $time['now'] = Yii::$app->formatter->asTime(time());
        $query =  Cart::find()->where('uid = :uid and area = :area',[':uid' => Yii::$app->user->identity->id ,':area'=>$area])->joinWith(['food']);
        $price['promotion'] = 0;
        $price['total'] = 0;

        $avaialbePromotion = PromotionController::getPromotion();

        foreach($query->each() as $value)
        {
            if($value->status == 1)
            {
                $price['promotion'] += self::getCartPromotion($value->price,$value->selectionprice,$value->fid)*$value->quantity;
                $price['total'] += $value->price * $value->quantity;
                $countDelivery[$value->food->Restaurant_ID] = 0;
            }
        }
       
        $deliveryCharge = empty($countDelivery)? 0 : count($countDelivery) * Yii::$app->params['deliveryCharge'];
        $price['delivery'] = $deliveryCharge;

        $time['early'] = date('08:00:00');
        $time['late'] = date('11:00:00');

        $voucher = ArrayHelper::map(UserVoucher::find()->where('uid=:uid',[':uid'=>Yii::$app->user->identity->id])->andWhere(['>=','user_voucher.endDate',time(date("Y-m-d"))])->joinWith(['voucher'=>function($query){
                $query->andWhere(['=','status',2]);
            }])->all(),'code','code');
        $ren = new DiscountItem;
        return $this->renderAjax('totalcart',['price'=>$price ,'time' => $time,'voucher'=>$voucher,'ren'=>$ren,'area'=>$area,'avaialbePromotion'=>$avaialbePromotion]);
    }

    public static function getCartPromotion($price,$selprice,$fid)
    {
        $promotion = PromotionController::getPromotioinPrice($price-$selprice,$fid,1);
        $dis = 0;

        if(is_array($promotion))
        {
            $dis = ($price-$selprice)- $promotion['price'];
               
            $seldis = PromotionController::getPromotioinPrice($selprice,$fid,2);
          
            if(is_array($seldis))
            {
                $dis += $selprice-$seldis['price'];
            }

        }

        return $dis;
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
            $object = new SelfObject();
            $object->id = $area['Area_Area'];
            $object->name = $area['Area_Area'];

            $areaArray[] = $object;
        }
        return $areaArray;
    }

    /*
    * to prevent post duplicate data
    */
    public function actionAftercheckout($did)
    {
        $order = Orders::find()->where("orders.Delivery_ID = :id and User_Username = :name",[':id'=>$did,':name'=>Yii::$app->user->identity->username])->joinWith(['address'])->one();

        if(empty($order))
        {
            Yii::$app->session->setFlash('danger', Yii::t('cart','Wrong Format Enter'));
            return $this->redirect(['/site/index']);
        }
        
        if($order->Orders_Status == 1)
        {
            return $this->redirect(['/payment/process-payment','did'=>$did]);
        }
        else
        {

            Yii::$app->session->setFlash('success', Yii::t('cart','Order Success'));
            $requireName = self::detectName($order->Delivery_ID,$order->User_Username);

            $orderitem = Orderitem::find()->joinWith('food')->where('Delivery_ID=:id',[':id'=>$did])->orderBy('Order_ID ASC')->all();

            return $this->render('aftercheckout', ['did'=>$did, 'order'=>$order,'orderitem'=>$orderitem ,'requireName'=>$requireName]);
        }
       
    }

    protected static function detectName($did,$username)
    {
        $data = array();
        $data['value'] = 0;
        $address = DeliveryAddress::findOne($did);
        $userdetail = Userdetails::find()->where('User_Username = :u',[':u'=>$username])->one();
        if($userdetail->fullName != $address->name || $userdetail->User_ContactNo != $address->contactno)
        {
            $data['value'] = 1;
            $data['fullname'] = $address->name;
            $data['contactno'] = $address->contactno;
        }
       
        return $data;
    }
    /*
    *find cart base on cid and uid
    * data['value'] 
    * 0 => error result
    * 1 => correct result
    * data['message'] => error message or success message
    */
    public function actionQuantity($update,$cid)
    {
        $data = [];
        $data['value'] = 0;
        $data['message'] = "";
        $cart = Cart::find()->where('id=:id and uid = :uid',[':id'=>$cid,':uid' => Yii::$app->user->identity->id])->one();
       
        if(empty($cart))
        {
            $data['message'] = Yii::t('cart',"Something Went Wrong!");
            return Json::encode($data);
        }

        $status = Foodstatus::find()->where('Food_ID = :fid',[':fid'=>$cart->fid])->one();
        switch ($update) {
            case 'minus':
                $cart->quantity -= 1;
                break;
 
            case 'plus':
                
                $promotion = $this->detectQuantity(1,$status->food_limit,$cart->fid,$cart->area);
                if($promotion['value'] == -1)
                {
                    $data['message'] = $promotion['message'];
                     return Json::encode($data);
                }
                $cart->quantity += 1;
                break;
            
            default:
                break;
        }
        if ($cart->quantity < 1) {
            $data['message'] = Yii::t('cart',"Food can't order less than 1.");
            return Json::encode($data);
        }

        if($cart->save())
        {
            $data['value'] = 1;
            $data['message'] = $cart;
            return Json::encode($data);
             
        }

        $data['message'] = Yii::t('cart',"Something Went Wrong!");
       
        return Json::encode($data);
    }

/* old version with entering code
    public function actionGetdiscount($dis,$codes,$sub,$deli,$total)
    {
        if (empty($dis)) {
            if (empty($codes)) {
                $value['error'] =  1; // error 1 = error, 0 = pass
                return Json::encode($value);
            }
        }

        if (!empty($codes)){$dis= $codes;}

*/
    public function actionGetdiscount($dis,$sub,$deli,$total)
    { // ajax's function must do in one controller, can't pass to second
        if (empty($dis)) {
            $value['error'] =  1; // error 1 = error, 0 = pass
            return Json::encode($value);
        }

        $special = VouchersSetCondition::find()->where('code=:c',[':c'=>$dis])->one();
        if (!empty($special)) {
            $v = Vouchers::find()->where('code=:c',[':c'=>$dis])->all();
            foreach ($v as $k => $val) {
                $valid = DiscountController::specialVoucherUse($val,$special);
                if ($valid == false) {
                    $value['error']= 1;
                    $value['item'] = 2;
                    $value['condition'] = $special['condition_id'];
                    if ($special['condition_id'] == 2) {
                        $value['amount'] = $special['amount'];
                    }
                    return Json::encode($value);
                }
            }
        }

        $valid = UserVoucher::find()->where('code = :c',[':c'=>$dis])->one();
        $voucher = Vouchers::find()->where('code = :c',[':c'=>$dis])->one();
       if ($voucher['status'] == 5) {
           $valid['endDate'] = date('Y-m-d',strtotime('+1 day')); // valid has ['enddate'] attribute, so was not count as empty
       }
       if (!empty($valid) && ($voucher['status'] == 2 || $voucher['status'] == 5) && $valid['endDate'] > date('Y-m-d')) 
        {
            $vouchers = Vouchers::find()->where('code = :c',[':c'=>$dis])->all();
            $value['code'] = $dis;
            $value['sub'] = $sub;
            $value['deli'] = $deli;
            $value['total'] = $total;
            $value['discount'] = 0;
            foreach ($vouchers as $k => $vou) 
            {
                if ($vou['discount_type'] == 1)  
                {
                    switch ($vou['discount_item']) 
                    {
                        case 1:
                            $value['discount'] += ($value['sub']* ($vou['discount'] / 100));
                            /* this 1 count with early discount for percentage
                            $value['total'] = $value['total'] - ($value['sub']* ($vou['discount'] / 100));
                            */
                            $value['sub'] = $value['sub']- ($value['sub']* ($vou['discount'] / 100));
                            $value['total'] =  $value['sub'] + $value['deli'];
                            break;

                        case 2:
                            $value['discount'] += ($value['deli']* ($vou['discount'] / 100));
                            $value['deli'] = $value['deli']-($value['deli']*($vou['discount'] / 100));
                            $value['total'] =  $value['sub'] + $value['deli'];
                            break;

                        case 3:
                            $value['discount'] += ($value['total']* ($vou['discount'] / 100));
                            $value['total'] = $value['total'] - ($value['total']*($vou['discount'] / 100));
                            break;
                                     
                        default:
                            $value['error']= 1;
                            $value['item'] = 0;
                            break;
                    }
                }
                    
                elseif ($vou['discount_type'] == 2 ) 
                {
                    switch ($vou['discount_item']) 
                    {
                        case 1:
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

                        case 2:
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

                        case 3:
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
                            $value['error']= 1;
                            $value['item'] = 0;
                            break;
                    }
                }
                else{
                    $value['error']= 1;
                    $value['item'] = 0;
                }
            }
        }
       else{
            $value['error']= 1;
            if (empty($valid) || ($voucher['status'] == 2 || $voucher['status'] == 5)) {
                $value['item'] = 0;
            }
            if ($valid['endDate'] > date('Y-m-d')) {
                $value['item'] = 1;
            }
       }
       $value=  Json::encode($value);
       return $value;
    }

    public static function mutipleDelete($cart)
    {
        foreach($cart as $id)
        {
            $cart = Cart::findOne($id);
            $cart->delete();
        }
    }

//--This function runs when an item in the cart is deleted
    public function actionDelete($id)
    {
        $cart = Cart::findOne($id);
        if(!empty($cart))
        {
            if($cart->delete())
            {
                return 1;
            }
        }
        return 0;  
    }

    /*
    * detect either the cart and the food adding is available 
    */
    protected static function availableCart($id,$post)
    {
        $data['value'] = 1 ;
        //$data['value'] = -1;
        $data['message'] = "";

        if(!empty($post['Cart']['remark']))
        {
            return $data;
        }
        
        $addedCart ="";
        $isAvailable = false;
        $allcart = Cart::find()->where("uid = :uid and fid = :fid ",[':uid'=>Yii::$app->user->identity->id,':fid'=>$id])->joinWith(['selection'])->all();
       
        if(empty($allcart))
        {
            return $data;
        }
        foreach($allcart as $i => $cart)
        {
            $isAvailable = self::detectAvailable($cart,$post);
            if($isAvailable)
            {
                //$isAvailable = true;
                $addedCart = $cart;
                break;
            }
        }

        if($isAvailable)
        {
            $oldQuantity = $addedCart->quantity;
            $addedCart->quantity += $post['Cart']['quantity'];
            if($cart->save())
            {
                self::generateNickName($addedCart->quantity-$oldQuantity,$addedCart->id);
                $data['message'] = Yii::t('cart','Food item has been added to cart.').' '.Html::a('<u>'.Yii::t('cart','Go to my Cart').'</u>', ['/cart/view-cart']).'.';
                    $data['value'] = 4;
                    //Yii::$app->session->setFlash('success', 'Food item has been added to cart. '.Html::a('<u>Go to my Cart</u>', ['/cart/view-cart']).'.');
                   
            }
            else
            {
                $data['message'] = Yii::t('cart',"Something Went Wrong!");
                $data['value'] = -1;
              
            }
        }
        return $data;
    }

    protected static function detectAvailable($cart,$post)
    {
        $avaiableSelection =[];
        if(!empty($post['CartSelection']))
        {
            $avaiableSelection = CommonController::removeNestedArray($post['CartSelection']);
        }

        if(empty($cart['selection']) && empty($avaiableSelection))
        {
            //return -1;
            return true;
        }
        elseif(empty($cart['selection']) && !empty($avaiableSelection))
        {
            //return -2;
            return false;
        }
        elseif(!empty($cart['selection']) && empty($avaiableSelection))
        {
            return false;
        }

        $selection = [];
        foreach($cart['selection'] as $data)
        {
            $selection[] = $data->selectionid;
        }
        
        $isAvailable = array_diff($avaiableSelection, $selection);

        return empty($isAvailable);
    }

    /*
    * detect either the food selection is empty or not
    * if not process to another step
    */
    protected static function detectEmptySelection($post,$id)
    {
        $data = [];
        $data['value'] = 1;
        $data['message'] ="";
        $foodselection = Foodselectiontype::find()->where("Food_ID = :id ",[':id' => $id])->all();

        if(empty($post['CartSelection']) && empty($foodselection))
        {
            return $data;
        }
        else
        {
            foreach ($foodselection as $key => $value) {
                $avaialbe = foodSelection::find()->where('Type_ID = :tid and Status != -1',[':tid'=>$value->ID])->one();
                if(empty($avaialbe))
                {
                    unset($foodselection[$key]);
                }
            }
            $isValid = SelectionTypeController::detectMinMaxSelecttion($post['CartSelection']['selectionid'],$foodselection);
            if($isValid['value'] == 1)
            {
                $data['value']= 2;
                return $data;
            }
            else
            {
                $data['value'] = 3;
                $data['message'] = $isValid['message'];
                return $data;
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

        $price[0] =  $food->Price + $price[1];
        
        return $price;
    }

    /*
    * detect food quantity base on promotion and food limit
    * cq => cart quantity
    * fq => food quantity
    * id => food id
    * group => areagroup
    */
    protected static function detectQuantity($cq,$fq,$id,$group)
    {
        $data =array();
        $data['value'] = -1;
        $data['message'] ="";
        $promotion = PromotionController::getPromotion();
        
        if(empty($promotion))
        {
            if($cq > $fq)
            {
                $data['message'] = Yii::t('cart',"The Quantity More Than Food Limit");
                return $data;
            }
        }
        else
        {   
            $quantity = $cq += self::detectTypePromotion($id,$promotion);
            
            $promotionData = DetectPromotionController::getDailyList($id,$promotion->id,$promotion->type_promotion);
            if(empty($promotionData))
            {
                if($cq > $fq)
                {
                    $data['message'] = Yii::t('cart',"The Quantity More Than Food Limit");
                    return $data;
                }
            }
            else
            {
                 $pquantity = $promotionData['limit']->food_limit - $promotionData['daily']->food_limit;

                if($quantity > $pquantity )
                {
                    $data['message'] = Yii::t('cart',"The Quantity More Than Promotion Quantity");
                    return $data;
                }
            }
           
        }
        $data['value'] = 1;
        return $data;
    }

    /*
    * detect type promotion 
    * base on cart quantity and promotion
    * calulate the promotion food limit
    */
    protected static function detectTypePromotion($fid,$promotion)
    {
        $query = Cart::find()->where('uid = :uid',[':uid' => Yii::$app->user->identity->id]);
        switch ($promotion->type_promotion) {
            case 2:
                $food = Food::findOne($fid);
                $restaurant = Restaurant::find()->where('restaurant.Restaurant_ID = :id',[':id'=>$food->Restaurant_ID])->joinWith(['food'])->one();
                $allfood = ArrayHelper::map($restaurant->food,'Food_ID','Food_ID');
                $query->andWhere(['in','fid',$allfood]);
                break;
            case 3:
                $query->andWhere('fid = :fid',[':fid'=>$fid]);
                break;
            default:
               
                break;
        }
        $count = $query->sum('quantity');
        return  is_null($count) ? 0 : $count;
        
    }

    protected static function generateNickName($quantity,$id)
    {
         $cookie =  new Cookie([
            'name' => 'cartNickName',
            'value' => ['quantity'=>$quantity,'id'=>$id],
            'expire' => time() + 3600,
        ]);
        \Yii::$app->getResponse()->getCookies()->add($cookie);
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