<?php
namespace frontend\controllers;

use Yii;
use yii\web\Controller;
use common\models\food\Food;
use common\models\Upload;
use yii\web\UploadedFile;
use common\models\food\Foodselectiontype;
use common\models\food\Foodselection;
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use yii\helpers\Json;
use common\models\Model;
use common\models\Orderitem;
use common\models\Orderitemselection;
use common\models\User;
use common\models\Rmanager;
use common\models\Restaurant;
use common\models\food\Foodtype;
use common\models\food\Foodtypejunction;
use common\models\food\Foodstatus;
use common\models\Rating\Foodrating;
use frontend\modules\Restaurant\controllers\FoodselectionController;
use frontend\modules\Restaurant\controllers\FoodtypeAndStatusController;
use frontend\modules\Restaurant\controllers\DefaultController;
use frontend\controllers\CartController;
use frontend\controllers\CommonController;
use common\models\Rmanagerlevel;
use yii\data\Pagination;

class FoodController extends CommonController
{
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                //'only' => ['foodDetails', 'insertFood','menu','delete','editFood','postedit','recycleBin','deletePermanent','viewComments'],
                'rules' => [
                    [
                        'actions' => [ 'insert-food','menu','delete','edit-food','postedit','recycle-bin','delete-permanent'],

                        'allow' => true,
                        'roles' => ['restaurant manager'],
                    ],
                    [
                        'actions' => ['food-details','view-comments'],
                        'allow' => true,
                        'roles' => ['?','@'],
                    ]
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'logout' => ['post'],
                ],
            ],
        ];
    }
//--This function loads the food item's details
    public function actionFoodDetails($id,$rid)
    {
        if (!(Yii::$app->user->isGuest)) {
            $rmanager = Rmanager::find()->where('uid=:id',[':id'=>Yii::$app->user->identity->id])->one();
        }

        $valid = ValidController::RestaurantValid($rid);
        if ($valid == true) {
            if (empty($rmanager)) {
                Yii::$app->session->setFlash('error', 'This restaurant was not valid now.');
                return $this->redirect(['/Restaurant/default/restaurant-details', 'id'=>$id,'rid'=>$rid]);
            }
        }
        
        $valid = ValidController::FoodValid($id);
        if ($valid == false) {
            Yii::$app->session->setFlash('error', 'This food was not valid now.');
            return $this->redirect(['/Restaurant/default/restaurant-details', 'id'=>$id,'rid'=>$rid]);
        }
        
        $fooddata = Food::find()->where(Food::tableName().'.Food_ID = :id' ,[':id' => $id])->innerJoinWith('foodType',true)->one();
        
        $foodPack = FoodtypeAndStatusController::getFoodPack($fooddata['foodType']);
        if($foodPack)
        {
            $fooddata->foodPackage = 1;
        }

        $foodtype = Foodselectiontype::find()->where('Food_ID = :id',[':id' => $id])->orderBy(['ID' => SORT_ASC])->all();
        
        $orderItemSelection =new Orderitemselection;
        $orderitem = new Orderitem;

        $comments = Foodrating::find()->where('Food_ID = :fid', [':fid'=>$id])->orderBy(['created_at' => SORT_DESC])->all();

        if ($orderItemSelection->load(Yii::$app->request->post()) || $orderitem->load(Yii::$app->request->post()))
        {
            $orderitem->load(Yii::$app->request->post());
            if ($orderitem->OrderItem_Quantity < 1)
            {
                Yii::$app->session->setFlash('error', 'You cannot place order less than 1 food.');

                return $this->redirect(['/Restaurant/default/restaurant-details', 'rid'=>$rid]);
            }
            
            foreach ($foodtype as $k => $foodtype) {
                if ($foodtype->Min > 0){
                    if ($orderItemSelection['FoodType_ID'][$foodtype->ID] == ''){
                        Yii::$app->session->setFlash('danger', 'Please select at least '.$foodtype->Min.' items and most '.$foodtype->Max.' items.');
                        return $this->redirect(Yii::$app->request->referrer);
                    } else if (count($orderItemSelection->FoodType_ID[$foodtype->ID]) < $foodtype->Min || count($orderItemSelection->FoodType_ID[$foodtype->ID]) > $foodtype->Max){
                        Yii::$app->session->setFlash('danger', 'Please select at least '.$foodtype->Min.' items and most '.$foodtype->Max.' items.');
                        return $this->redirect(Yii::$app->request->referrer);
                    }
                } else {
                    if (count($orderItemSelection->FoodType_ID[$foodtype->ID]) < $foodtype->Min || count($orderItemSelection->FoodType_ID[$foodtype->ID]) > $foodtype->Max){
                        Yii::$app->session->setFlash('danger', 'Please select at least '.$foodtype->Min.' items and most '.$foodtype->Max.' items.');
                        return $this->redirect(Yii::$app->request->referrer);
                    }
                }
            }
            $quantity = $orderitem->OrderItem_Quantity;
            $remarks = $orderitem->OrderItem_Remark;
            $selected = $orderItemSelection->FoodType_ID;
            $restaurant = Restaurant::find()->where('Restaurant_ID = :rid', [':rid'=>$rid])->one();
            $session = Yii::$app->session;
            if (!is_null($session['group']) && $session['group'] == $restaurant['Restaurant_AreaGroup'])
            {
                $sessiongroup = $restaurant['Restaurant_AreaGroup'];
            }
            elseif (!is_null($session['group']) && $session['group'] != $restaurant['Restaurant_AreaGroup'])
            {
                Yii::$app->session->setFlash('error', "This item is in a different area from your area. Please re-enter your area.");
                return $this->redirect(['site/index']);
            }
            else
            {
                Yii::$app->session->setFlash('error', "Please enter your postcode and area first before ordering.");
                return $this->redirect(['site/index']);
            }
            $glue = "','";
            if ($selected == !null)
            {
                $finalselected = JSON_encode($selected);
            }
            else 
            {
                $finalselected = '';
            }

            //var_dump($finalselected);exit;
            return $this->redirect(['cart/addto-cart', 'quantity' => $quantity, 'Food_ID' => $id, 'finalselected' => $finalselected, 'remarks'=>$remarks, 'rid'=>$rid, 'sessiongroup'=>$sessiongroup]);
        }
        return $this->renderAjax('fooddetails',['fooddata' => $fooddata,'foodtype' => $foodtype, 'orderitem'=>$orderitem ,'orderItemSelection' => $orderItemSelection, 'comments'=>$comments]);
         
    }

//--This function runs when user creates a new food
    public function actionInsertFood($rid)
    {
        $food = new Food();
        $food->scenario = "new"; 
        $foodtype = [new Foodselectiontype()];
        $foodselection = [[new Foodselection()]];
        $upload = new Upload();
        
        $foodjunction = new Foodtypejunction();
        $type = ArrayHelper::map(FoodType::find()->orderBy(['(Type_Desc)' => SORT_ASC])->all(),'ID','Type_Desc');
    
       if(Yii::$app->request->isPost)
       {
            $post = Yii::$app->request->post();
            $upload->imageFile =  UploadedFile::getInstance($food, 'PicPath');
            $upload->imageFile->name = time().'.'.$upload->imageFile->extension;
            $location = 'imageLocation/foodImg/';
            $upload->upload($location);
    
            $food = self::newFood($post,$rid,$upload->imageFile->name);
    
            $foodtype = Model::createMultiple(Foodselectiontype::classname());

            Model::loadMultiple($foodtype, Yii::$app->request->post());
         
            $valid =  Model::validateMultiple($foodtype) && $food->validate() && $upload;
            
            if (isset($_POST['Foodselection'][0][0])) {

                $foodselection = FoodselectionController::validatefoodselection($post['Foodselection']);
            }

             if ($valid) {
                $transaction = Yii::$app->db->beginTransaction();
                try {
                        
                    if ($flag = $food->save()) {

                        FoodtypeAndStatusController::newFoodJuntion($post['Type_ID'],$food->Food_ID);

                        $isValid = FoodtypeAndStatusController::newStatus($food->Food_ID);

                        $flag = FoodselectionController::createfoodselection($foodtype,$foodselection,$food->Food_ID) && $isValid;
                       
                        if ($flag) {
                            $transaction->commit();
                            $status = DefaultController::updateRestaurant($rid);

                            return $this->redirect(['food/menu', 'rid' => $rid , 'page' => 'menu']);
                        } 
                        else {
                            $transaction->rollBack();
                        }
                    }
                } 
                catch (Exception $e) {
                    $transaction->rollBack();
                }
            }
        }
        $this->layout = 'user';
      
        return $this->render('insertfood',['food' => $food,'foodjunction'=>$foodjunction,'foodtype' => (empty($foodtype)) ? [new Foodselectiontype] : $foodtype,'foodselection' => (empty($foodselection)) ? [[new Foodselection]] : $foodselection,'type' => $type ,'rid'=>$rid]);
    }
    
//---This function is for loading the restaurant's menu
     public function actionMenu($rid,$page)
     {
        $menu = Food::find()->where('Restaurant_ID=:rid',[':rid'=>$rid]);
        $count = count(Food::find()->where('Restaurant_ID=:rid',[':rid'=>$rid])->all());
        // var_dump($count);exit;
        $pagination = new Pagination(['totalCount'=>$count,'pageSize'=>10]);
        $menu = $menu
        ->all();

        // var_dump($menu);exit;
      
        $restaurant = Restaurant::find()->where('Restaurant_ID = :id', [':id'=>$rid])->one();
        $rname = $restaurant['Restaurant_Name'];
        $staff = Rmanagerlevel::find()->where('User_Username = :uname and Restaurant_ID = :id', [':uname'=>Yii::$app->user->identity->username, ':id'=>$rid])->one();
        $link = CommonController::getRestaurantUrl($rid,$restaurant['Restaurant_AreaGroup'],$restaurant['Restaurant_Area'],$restaurant['Restaurant_Postcode'],$staff['RmanagerLevel_Level']);
        return $this->render('Menu',['menu'=>$menu, 'rid'=>$rid, 'rname'=>$rname, 'restaurant'=>$restaurant,'staff'=>$staff, 'pagination'=>$pagination,'link'=>$link]);
     }

//--This function runs when a food is deleted
    public function actionDelete($rid,$id,$page)
    {
        $restaurant = Restaurant::find()->where('Restaurant_ID=:rid',[':rid'=>$rid])->one();
        if (!empty($restaurant)) {
            if ($user = User::find()->where('username=:u',[':u'=>$restaurant['Restaurant_Manager']])->one()) {
                $user = $user['id'];
                $check = ValidController::checkUserValid($user);
                if ($check == false) {
                    return $this->redirect(['site/index']);
                }
            }
        }

        $status = Foodstatus::find()->where('Food_ID = :fid',[':fid'=>$id])->one();
        if ($status['Status'] == true)
        {
            $sql = "UPDATE foodstatus SET status = false WHERE Food_ID ='$id'";
            Yii::$app->db->createCommand($sql)->execute();
 
            $menu = food::find()->where('Restaurant_ID=:id and Status = :status', [':id' => $rid, ':status'=>1])->innerJoinWith('foodType',true)->innerJoinWith('foodStatus',true)->all();
        }
        else
        {
             $sql = "UPDATE foodstatus SET status = true WHERE Food_ID ='$id'";
             Yii::$app->db->createCommand($sql)->execute();
 
             $menu = food::find()->where('Restaurant_ID=:id and Status = :status', [':id' => $rid, ':status'=>0])->innerJoinWith('foodType',true)->innerJoinWith('foodStatus',true)->all();
        }
        $rid = $rid;
 
        $this->layout = 'user';

        return $this->redirect(Yii::$app->request->referrer);
    }

//--This function runs when a food's details are edited
    public function actionEditFood($id)
    {

        $food = Food::find()->where(Food::tableName().'.Food_ID = :id' ,[':id' => $id])->innerJoinWith('foodType',true)->one();
        $restaurant = Restaurant::find()->where('Restaurant_ID=:rid',[':rid'=>$food['Restaurant_ID']])->one();
        if (!empty($restaurant)) {
            if ($user = User::find()->where('username=:u',[':u'=>$restaurant['Restaurant_Manager']])->one()) {
                $user = $user['id'];
                $check = ValidController::checkUserValid($user);
                if ($check == false) {
                    return $this->redirect(['site/index']);
                }
            }
        }

        $chosen = ArrayHelper::map($food['foodType'],'ID','ID');
        $type = ArrayHelper::map(FoodType::find()->orderBy(['(Type_Desc)' => SORT_ASC])->all(),'ID','Type_Desc');
      
        $foodtype =$food->foodselectiontypes;
        $foodselection = [];
      
        if (!empty($foodtype)) 
        {
            $foodselection = FoodselectionController::oldData($foodtype,1);
        }

        $upload = new Upload();
        $picpath = $food['PicPath'];
        $food->scenario = "edit";
    
        $this->layout = 'user';
        return $this->render('editfood',['food' => $food,'chosen'=> $chosen,'type' => $type,'foodtype' => (empty($foodtype)) ? [new Foodselectiontype] : $foodtype,'foodselection' => (empty($foodselection)) ? [[new Foodselection]] : $foodselection ]);
    }

    public function actionPostedit($id)
    {
        $food = Food::findOne($id);
        $modelSelectionType = $food->foodselectiontypes;
        $modelJunction = $food->junction;

        $modelSelect = [];
        $oldSelect = [];
        $selectionId = [];

        $upload = new Upload();
        $upload->imageFile =  UploadedFile::getInstance($food, 'PicPath');

        $post = Yii::$app->request->post();

        $picpath = $food['PicPath'];

        if (!empty($modelSelectionType)) 
        {
            $oldSelect = FoodselectionController::oldData($modelSelectionType,2);
        }

        $food->load($post);
        $food->Price = CartController::actionDisplay2decimal($food->Price);
        $food->BeforeMarkedUp =  CartController::actionRoundoff1decimal($food->Price / 1.3);
    
        if (!is_null($upload->imageFile))
        {
            $upload->imageFile->name = time().'.'.$upload->imageFile->extension;
                  
            $location = 'imageLocation/foodImg/';
                
            $upload->upload($location);

            $food->PicPath = $upload->imageFile->name;
        }
        else
        {
            $food->PicPath = $picpath;
        }

        $oldSelectionTypeId = ArrayHelper::map($modelSelectionType, 'ID', 'ID');

        $junctionData = FoodtypeAndStatusController::diffStatus($modelJunction,$post['Type_ID']);

        $modelSelectionType = Model::createMultiple(Foodselectiontype::classname(), $modelSelectionType);

        \yii\base\Model::loadMultiple($modelSelectionType,Yii::$app->request->post());

        $deletedSelectionTypeId = array_diff($oldSelectionTypeId, array_filter(ArrayHelper::map($modelSelectionType, 'ID', 'ID')));

        $valid = $food->validate();

        $valid = Model::validateMultiple($modelSelectionType) && $valid;
        
        if (isset($post['Foodselection'][0][0]))
        {
            foreach ($post['Foodselection'] as $i => $select) 
            {

                $selectionId = ArrayHelper::merge($selectionId, array_filter(ArrayHelper::getColumn($select, 'ID')));

                foreach ($select as $k => $selections) 
                {

                    $data['Foodselection'] = $selections;

                    $modelSelects = (isset($selections['ID']) && isset($oldSelect[$selections['ID']])) ? $oldSelect[$selections['ID']] : new Foodselection;

                    $modelSelects->load($data);

                    $modelSelect[$i][$k] = $modelSelects;

                    $valid = $modelSelects->validate();

                } 
            }
        }
        
        $oldSelectIds = ArrayHelper::getColumn($oldSelect,'ID');
        $deletedSelect = array_diff($oldSelectIds,$selectionId);
       
        if($valid)
        {
            $transaction = Yii::$app->db->beginTransaction();
            try
            {
                if($flag = $food->save())
                {
                    if(!empty($deletedSelectionTypeId))
                    {
                        Foodselectiontype::deleteAll(['ID' => $deletedSelectionTypeId]);
                    }

                    if(!empty($deletedSelect))
                    {
                        Foodselection::deleteAll(['ID' => $deletedSelect]);
                    }

                    if(!empty($junctionData[0]))
                    {
                        foreach($junctionData[0] as $deleteId)
                        {
                             Foodtypejunction::deleteAll('Food_ID = :fid and Type_ID = :tid',[':fid' => $food->Food_ID, ':tid' => $deleteId]);
                        }
                    }
                   
                    if(!empty($junctionData[1]))
                    {
                        FoodtypeAndStatusController::newFoodJuntion($junctionData[1],$food->Food_ID);
                    }

                    foreach($modelSelectionType as $i => $selectionType)
                    {
                        if($flag == false)
                        {
                            break;
                        }

                        $selectionType->Food_ID = $food->Food_ID;

                        if(!($flag = $selectionType->save()))
                        {
                            break;
                        }

                        if(isset($modelSelect[$i]) && is_array($modelSelect[$i]))
                        {
                            foreach($modelSelect[$i] as $k => $model)
                            {
                                $model->Type_ID = $selectionType->ID;
                                $model->Food_ID = $food->Food_ID;
                                $model->Price = CartController::actionDisplay2decimal($model->Price);
                                $model->BeforeMarkedUp =  CartController::actionRoundoff1decimal($model->Price / 1.3);
                                //$beforemarkedup = CartController::actionRoundoff1decimal($model->BeforeMarkedUp);
                                //$markedup = $beforemarkedup * 1.3;
                                //$markedup = CartController::actionRoundoff1decimal($markedup);
                                //$model->BeforeMarkedUp = $beforemarkedup;
                                //$model->Price = $markedup;
                                if(!($flag = $model->save()))
                                {
                                    break;
                                }
                            }
                        }
                    }
                    if($flag)
                    {
                        $transaction->commit();
                        Yii::$app->session->setFlash('success', "Success edit");
                        return $this->redirect(['food/menu', 'rid' => $food->Restaurant_ID , 'page' => 'menu']);

                    }
                    else
                    {
                        $transaction->rollBack();
                        Yii::$app->session->setFlash('warning', "Fail edit");
                        return $this->redirect(Yii::$app->request->referrer);
                    }
                }
            }
            catch(Exception $e)
            {
                $transaction->rollBack();
            }
            Yii::$app->session->setFlash('warning', "Fail edit");
            return $this->redirect(Yii::$app->request->referrer);
        }
        else
        {
            Yii::$app->session->setFlash('warning', "Fail edit");
            return $this->redirect(Yii::$app->request->referrer);
        }
    }

    protected static function newFood($post,$rid,$upload)
    {
        $food = new Food();
        $food->load($post);
        
        //$food->BeforeMarkedUp = CartController::actionDisplay2decimal($food->BeforeMarkedUp);

        //$foodprice = CartController::actionRoundoff1decimal($food->BeforeMarkedUp);
        //$markedupprice = $foodprice * 1.3;
        //$markedupprice = CartController::actionRoundoff1decimal($markedupprice);

        //$food->Price = $markedupprice;
        $food->Price = CartController::actionDisplay2decimal($food->Price);
        $food->BeforeMarkedUp =  CartController::actionRoundoff1decimal($food->Price / 1.3);
        $food->Restaurant_ID = $rid;
        $food->PicPath = $upload;
        $food->Ingredient = 'xD';
        return $food;
    }

    public function actionRecycleBin($rid)
    {
        $restaurant = Restaurant::find()->where('Restaurant_ID=:rid',[':rid'=>$rid])->one();
        if (!empty($restaurant)) {
            if ($user = User::find()->where('username=:u',[':u'=>$restaurant['Restaurant_Manager']])->one()) {
                $user = $user['id'];
                $check = ValidController::checkUserValid($user);
                if ($check == false) {
                    return $this->redirect(['site/index']);
                }
            }
        }

        $menu = food::find()->where('Restaurant_ID=:id and Status = :status', [':id' => $rid, ':status'=>0])->innerJoinWith('foodType',true)->innerJoinWith('foodStatus',true)->all();
        $rname = restaurant::find()->where('Restaurant_ID=:id',[':id' => $rid])->one()->Restaurant_Name;

        return $this->render('Menu',['menu'=>$menu, 'rid'=>$rid, 'page'=>'recyclebin', 'rname'=>$rname, 'restaurant'=>$restaurant]);
    }

    public function actionDeletePermanent($rid,$id,$page)
    {

        $restaurant = Restaurant::find()->where('Restaurant_ID=:rid',[':rid'=>$rid])->one();
        if (!empty($restaurant)) {
            if ($user = User::find()->where('username=:u',[':u'=>$restaurant['Restaurant_Manager']])->one()) {
                $user = $user['id'];
                $check = ValidController::checkUserValid($user);
                if ($check == false) {
                    return $this->redirect(['site/index']);
                }
            }
        }

        $status = Foodstatus::find()->where('Food_ID = :fid',[':fid'=>$id])->one();
        if ($status['Status'] == true)
        {
            $sql = "UPDATE foodstatus SET status = false WHERE Food_ID ='$id'";
            Yii::$app->db->createCommand($sql)->execute();
        }
        else
        {
            $sql = "UPDATE foodstatus SET status = -1 WHERE Food_ID ='$id'";
            Yii::$app->db->createCommand($sql)->execute();
        }
         $rid = $rid;

         $menu = food::find()->where('Restaurant_ID=:id and Status = :status', [':id' => $rid, ':status'=>0])->innerJoinWith('foodType',true)->innerJoinWith('foodStatus',true)->all();

         $this->layout = 'user';

         return $this->redirect(['menu','menu'=>$menu,'id'=>$id,'rid'=>$rid,'page'=>$page]);
    }

//--This function loads the food's comments
    public function actionViewComments($id)
    {
        $comments = Foodrating::find()->where('Food_ID = :id', [':id'=>$id])->all();

        $foodname= Food::find()->where('Food_ID=:id',[':id'=>$id])->one();
        $foodname=$foodname['Name'];
        
        return $this->render('comments', ['fid'=>$id, 'comments'=>$comments,'foodname'=>$foodname]);
    }
}

