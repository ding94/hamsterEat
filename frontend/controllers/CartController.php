<?php
namespace frontend\controllers;
use Yii;
use yii\web\Controller;
use common\models\User;
use common\models\food\{Food,Foodselectiontype,Foodselection,Foodstatus};
use common\models\Order\{Orders,Orderitem};
use common\models\Area;
use common\models\vouchers\{Vouchers,VouchersType,UserVoucher};
use common\models\user\{Userdetails,Useraddress};
use common\models\Restaurant;
use common\models\Cart\Cart;
use common\models\Cart\CartSelection;
use yii\helpers\Json;
use frontend\modules\UserPackage\controllers\SelectionTypeController;
use frontend\controllers\CommonController;
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
        $cart = new Cart;
        $cartSelection = new CartSelection;
        $post = Yii::$app->request->post();
        $cart->load(Yii::$app->request->post());
        $data['value'] = 0;
        //$cartSelection->load(Yii::$app->request->post());
        if(empty($post))
        {
            $data['message'] = Yii::t('cart','Something Went Wrong!');
            ;
            return Json::encode($data);
        }

        $session = Yii::$app->session;
        $food = food::find()->where('food.Food_ID = :id and foodstatus.Status = 1',[':id'=> $id])->joinWith(['restaurant','foodSelection','foodStatus'])->andWhere(['>','food_limit','0'])->one();

        if(empty($food))
        {
            $data['message'] = Yii::t('cart','The Food Is Not Available Or Missing');
            return Json::encode($data);
        }
        
        if($cart->quantity >= $food->foodStatus->food_limit+1)
        {
            $data['message'] = Yii::t('cart','Maximun Amount Of Food Order');
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
        
        $cart = Cart::find()->where('uid = :uid',[':uid' => Yii::$app->user->identity->id])->joinWith(['food','selection'])->all();
        
        foreach($cart as $i=> $single)
        {
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
          //var_dump($groupCart[1][0]);exit;

        return $this->render('cart',['groupCart' => $groupCart]);
    }

    public function actionTotalcart($area)
    {
        $time['now'] = Yii::$app->formatter->asTime(time());
        $query =  Cart::find()->where('uid = :uid and area = :area',[':uid' => Yii::$app->user->identity->id ,':area'=>$area])->joinWith(['food']);

        $price['total'] = 0;

        foreach($query->each() as $value)
        {
            if($value->status == 1)
            {
                $price['total'] += $value->price * $value->quantity;
                $countDelivery[$value->food->Restaurant_ID] = 0;
            }
        }
      
        $deliveryCharge = empty($countDelivery)? 0 : count($countDelivery) * Yii::$app->params['deliveryCharge'];
        $price['delivery'] = $deliveryCharge;

        $time['early'] = date('08:00:00');
        $time['late'] = date('23:00:59');

        $this->layout ="content";

        $voucher = ArrayHelper::map(UserVoucher::find()->where('uid=:uid',[':uid'=>Yii::$app->user->identity->id])->andWhere(['>=','user_voucher.endDate',time(date("Y-m-d"))])->joinWith(['voucher'=>function($query){
                $query->andWhere(['=','discount_type',5])->orWhere(['=','discount_type',2]);
            }])->all(),'code','code');
        $ren = new VouchersType;
        return $this->render('totalcart',['price'=>$price ,'time' => $time,'voucher'=>$voucher,'ren'=>$ren,'area'=>$area]);
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

    public function actionNewaddress()
    {
        $count = Useraddress::find()->where('uid = :uid',[':uid' => Yii::$app->user->identity->id])->count();
        if($count >= 3)
        {
             Yii::$app->session->setFlash('danger', Yii::t('cart','Reach Max Limit 3'));
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
                     Yii::$app->session->setFlash('success', Yii::t('cart','Successfully create new address'));
            }
            else
            {
                Yii::$app->session->setFlash('danger', Yii::t('cart','Address Add Fail'));
            }
            return $this->redirect(Yii::$app->request->referrer);
        }
        $this->layout = 'user';
        $this->view->title = Yii::t('cart','Add New Address');
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
            $add = $addr['address'];
            $addr->load(Yii::$app->request->post());
            $addr['address'] = trim(preg_replace('/\s+/', ' ', $add));
            
            if ($addr->validate()) {
                $addr->save();
                Yii::$app->session->setFlash('success', Yii::t('cart','Success!'));
            }
            else{
                Yii::$app->session->setFlash('error', Yii::t('cart','Failed to edit!'));
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
            $orderitem = Orderitem::find()->joinWith('food')->where('Delivery_ID=:id',[':id'=>$did])->orderBy('Order_ID ASC')->all();
            return $this->render('aftercheckout', ['did'=>$did, 'order'=>$order,'orderitem'=>$orderitem ]);
        }
       
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
                $cart->quantity = $cart->quantity - 1;
                break;

            case 'plus':
                $cart->quantity += 1;
                break;
            
            default:
                break;
        }
        if ($cart->quantity < 1) {
            $data['message'] = Yii::t('cart',"Food can't order less than 1.");
            return Json::encode($data);
        }

        if($status->food_limit-$cart->quantity < 0)
        {
            $data['message'] =  $data['message'] = Yii::t('cart','Maximun Amount Of Food Order');
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

    public function actionGetdiscount($dis,$codes,$sub,$deli,$total)
    { // ajax's function must do in one controller, can't pass to second
        if (empty($dis)) {
            if (empty($codes)) {
                $value=  Json::encode(19);
                return $value;
            }
        }
        if (!empty($codes)) {
            $dis= $codes;
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
                    $value['code'] = $dis;
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
        $data['value'] =1 ;
        $data['message'] = "";

        if(!empty($post['Cart']['remark']))
        {
            return $data;
        }
        $addedCart ="";
        $isAvailable = false;
        $allcart = Cart::find()->where("uid = :uid and fid = :fid and remark = ''",[':uid'=>Yii::$app->user->identity->id,':fid'=>$id])->joinWith(['selection'])->all();

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
            $addedCart->quantity += $post['Cart']['quantity'];
            if($cart->save())
            {
                $data['message'] = Yii::t('cart','Food item has been added to cart.').' '.Html::a('<u>'.Yii::t('cart','Go to my Cart').'</u>', ['/cart/view-cart']).'.';
                    $data['value'] = 4;
                    //Yii::$app->session->setFlash('success', 'Food item has been added to cart. '.Html::a('<u>Go to my Cart</u>', ['/cart/view-cart']).'.');
                   
            }
            else
            {
                $data['message'] = Yii::t('cart',"Something Went Wrong!");
                $data['value'] = 0;
              
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