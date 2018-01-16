<?php
namespace frontend\controllers;

use Yii;
use yii\web\Controller;
use common\models\food\Food;
use common\models\food\Foodselectiontype;
use common\models\food\Foodselection;
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use yii\helpers\Json;
use common\models\Model;
use common\models\User;
use common\models\Rmanager;
use common\models\Restaurant;
use common\models\Restauranttype;
use common\models\Restauranttypejunction;
use common\models\food\Foodtype;
use common\models\food\Foodtypejunction;
use common\models\food\Foodstatus;
use common\models\Rating\Foodrating;
use common\models\Cart\Cart;
use common\models\Cart\CartSelection;
use frontend\modules\Restaurant\controllers\FoodselectionController;
use frontend\modules\Restaurant\controllers\FoodtypeAndStatusController;
use frontend\modules\Restaurant\controllers\DefaultController;
use frontend\controllers\CartController;
use frontend\controllers\CommonController;
use frontend\controllers\FoodImgController;
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
        if(!Yii::$app->request->isAjax){
            return $this->redirect(Yii::$app->request->referrer);
        }

        if (!(Yii::$app->user->isGuest)) {
            $rmanager = Rmanager::find()->where('uid=:id',[':id'=>Yii::$app->user->identity->id])->one();
        }

        $valid = ValidController::RestaurantValid($rid);
        if ($valid == true) {
            if (empty($rmanager)) {
                Yii::$app->session->setFlash('error', 'This restaurant was not valid now.');
                return $this->redirect(Yii::$app->request->referrer);
            }
        }
        
        $valid = ValidController::FoodValid($id);
        if ($valid == false) {
            Yii::$app->session->setFlash('error', 'This food was not valid now.');
            return $this->redirect(Yii::$app->request->referrer);
        }
        
        $fooddata = Food::find()->where(Food::tableName().'.Food_ID = :id' ,[':id' => $id])->innerJoinWith('foodType',true)->one();

        $foodtype = Foodselectiontype::find()->where('Food_ID = :id',[':id' => $id])->orderBy(['ID' => SORT_ASC])->all();
        
        $cartSelection = new CartSelection;
        $cart = new Cart;
        
        $comments = Foodrating::find()->where('Food_ID = :fid', [':fid'=>$id])->orderBy(['created_at' => SORT_DESC])->all();
        
        return $this->renderAjax('fooddetails',['fooddata' => $fooddata,'foodtype' => $foodtype, 'cart'=>$cart ,'cartSelection' => $cartSelection, 'comments'=>$comments]);
         
    }

//--This function runs when user creates a new food
    public function actionInsertFood($rid)
    {
        CommonController::restaurantPermission($rid);
        $food = new Food();
        $food->scenario = "new"; 
        $foodtype = [new Foodselectiontype()];
        $foodselection = [[new Foodselection()]];
        $foodjunction = new Foodtypejunction();

        $type = ArrayHelper::map(FoodType::find()->andWhere(['and',['!=','Type_Desc','Halal'],['!=','Type_Desc','Non-Halal']])->orderBy(['(Type_Desc)' => SORT_ASC])->all(),'ID','Type_Desc');
        $halal = Foodtype::find()->where('Type_Desc=:t',[':t'=>'Halal'])->one();
        $nonhalal = Foodtype::find()->where('Type_Desc=:t',[':t'=>'Non-Halal'])->one();
        $restauranttype = Restauranttypejunction::find()->where('Restaurant_ID=:rid',[':rid'=>$rid])->joinWith('restauranttype')->all();
        $rtype= "";

        foreach ($restauranttype as $k => $value) {
            if ($value['restauranttype']['Type_Name'] == $halal['Type_Desc'] || $value['restauranttype']['Type_Name'] == $nonhalal['Type_Desc']) {
                $rtype = $value['restauranttype']['Type_Name'];
            }
        }
        
       if(Yii::$app->request->isPost)
       {
            $post = Yii::$app->request->post();

            $post['Type_ID'][] = $post['Foodtypejunction']['Type_ID'];
    
            $food = self::newFood($post,$rid);
            
            $foodtype = Model::createMultiple(Foodselectiontype::classname());

            Model::loadMultiple($foodtype, Yii::$app->request->post());
         
            $valid =  Model::validateMultiple($foodtype) && $food->validate();
            
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
      
        return $this->render('insertfood',
            [
                'food' => $food,
                'foodjunction'=>$foodjunction,
                'foodtype' => (empty($foodtype)) ? [new Foodselectiontype] : $foodtype,
                'foodselection' => (empty($foodselection)) ? [[new Foodselection]] : $foodselection,
                'type' => $type,
                'rid'=>$rid,
                'halal'=>$halal,
                'nonhalal'=>$nonhalal,
                'rtype'=>$rtype,
            ]);
    }
    
//---This function is for loading the restaurant's menu
     public function actionMenu($rid,$page)
     {
        $linkData = CommonController::restaurantPermission($rid);
        $link = CommonController::getRestaurantUrl($linkData,$rid);
        $query = Food::find()->where('Restaurant_ID=:rid',[':rid'=>$rid]);
        $countQuery = clone $query;
        $pagination = new Pagination(['totalCount' => $countQuery->count()]);
        //$count = count(Food::find()->where('Restaurant_ID=:rid',[':rid'=>$rid])->all());
        // var_dump($count);exit;
        //$pagination = new Pagination(['totalCount'=>$count,'pageSize'=>10]);
        $menu = $query->offset($pagination->offset)
        ->limit($pagination->limit)
        ->all();

        // var_dump($menu);exit;
      
        $restaurant = Restaurant::find()->where('Restaurant_ID = :id', [':id'=>$rid])->one();
        $rname = $restaurant['Restaurant_Name'];
        $staff = Rmanagerlevel::find()->where('User_Username = :uname and Restaurant_ID = :id', [':uname'=>Yii::$app->user->identity->username, ':id'=>$rid])->one();
        
        return $this->render('Menu',['menu'=>$menu, 'rid'=>$rid, 'rname'=>$rname, 'restaurant'=>$restaurant,'staff'=>$staff, 'pagination'=>$pagination,'link'=>$link]);
     }

//--This function runs when a food is deleted
    public function actionDelete($fid)
    {
        $food = Food::find()->where('Food_ID = :fid',[':fid'=>$fid])->joinWith('restaurant')->one();
        if ($food['restaurant']['Restaurant_Manager'] == Yii::$app->user->identity->username) {
            $status = Foodstatus::find()->where('Food_ID =:fid',[':fid'=>$fid])->one();
            if ($status['Status']==0) {
                $status['Status'] = -1;
                if ($status->validate()) {
                    $status->save();
                    Yii::$app->session->setFlash('success','Item Deleted.');
                    return $this->redirect(Yii::$app->request->referrer);
                }
            }
            else
            {
                Yii::$app->session->setFlash('error','You need to pause the item first.');
            }
        }
        else
        {
            Yii::$app->session->setFlash('error','You are not allowed to perform this action.');
        }
        return $this->redirect(Yii::$app->request->referrer);
    }

//--This function runs when a food's details are edited
    public function actionEditFood($id)
    {
        $food = Food::find()->where(Food::tableName().'.Food_ID = :id' ,[':id' => $id])->innerJoinWith('foodType',true)->one();
        $restaurant = Restaurant::find()->where('Restaurant_ID=:rid',[':rid'=>$food['Restaurant_ID']])->one();
        CommonController::rmanagerApproval();
        CommonController::restaurantPermission($restaurant->Restaurant_ID);
        $foodjunction = new Foodtypejunction();
        $halal = Foodtype::find()->where('Type_Desc=:t',[':t'=>'Halal'])->one();
        $nonhalal = Foodtype::find()->where('Type_Desc=:t',[':t'=>'Non-Halal'])->one();
        $restauranttype = Restauranttypejunction::find()->where('Restaurant_ID=:rid',[':rid'=>$food['Restaurant_ID']])->joinWith('restauranttype')->all();
        $rtype= "";

        foreach ($food['foodType'] as $key => $value) :
            if ($value['Type_Desc'] == 'Halal' || $value['Type_Desc'] == 'Non-Halal') {
                $foodjunction['Type_ID'] = $value['ID'];
            }
        endforeach;


        foreach ($restauranttype as $k => $value) {
            if ($value['restauranttype']['Type_Name'] == $halal['Type_Desc'] || $value['restauranttype']['Type_Name'] == $nonhalal['Type_Desc']) {
                $rtype = $value['restauranttype']['Type_Name'];
            }
        }

        $chosen = ArrayHelper::map($food['foodType'],'ID','ID');
        $type = ArrayHelper::map(FoodType::find()->andWhere(['and',['!=','Type_Desc','Halal'],['!=','Type_Desc','Non-Halal']])->orderBy(['(Type_Desc)' => SORT_ASC])->all(),'ID','Type_Desc');
      
        $foodtype =$food->foodselectiontypes;
        $foodselection = [];
      
        if (!empty($foodtype)) 
        {
            $foodselection = FoodselectionController::oldData($foodtype,1);
        }

        $food->scenario = "edit";
    
        return $this->render('editfood',['food' => $food,'halal'=>$halal,'nonhalal'=>$nonhalal,'rtype'=>$rtype,'foodjunction'=>$foodjunction, 'chosen'=> $chosen,'type' => $type,'foodtype' => (empty($foodtype)) ? [new Foodselectiontype] : $foodtype,'foodselection' => (empty($foodselection)) ? [[new Foodselection]] : $foodselection ]);
    }

    public function actionPostedit($id)
    { 
        $food = Food::findOne($id);
        $restaurant = Restaurant::find()->where('Restaurant_ID=:rid',[':rid'=>$food['Restaurant_ID']])->one();
        CommonController::restaurantPermission($restaurant->Restaurant_ID);
        $modelSelectionType = $food->foodselectiontypes;
        $modelJunction = $food->junction;

        $modelSelect = [];
        $oldSelect = [];
        $selectionId = [];

        $post = Yii::$app->request->post();
        $foodtypemodel = Foodtype::find()->where('Type_Desc=:t',[':t'=>$post['Type_ID'][0]])->one();
        $foodtypeid = Foodtype::find()->where('ID=:id',[':id'=>$post['Type_ID'][0]])->one();
        if($foodtypemodel==null && $foodtypeid==null){
            $foodtypemodel = new Foodtype();
            $foodtypemodel->Type_Desc = $post['Type_ID'][0];
            $foodtypemodel->save();
            $post['Type_ID'][0] = (string)$foodtypemodel->ID;
        }
        $post['Type_ID'][] = $post['Foodtypejunction']['Type_ID'];
        
        if (!empty($modelSelectionType)) 
        {
            $oldSelect = FoodselectionController::oldData($modelSelectionType,2);
        }

        $food->load($post);
        $food->Price = CartController::actionDisplay2decimal($food->Price);
        $food->BeforeMarkedUp =  CartController::actionRoundoff1decimal($food->Price / 1.3);
    

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
                        return $this->redirect(Yii::$app->request->referrer);

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

    protected static function newFood($post,$rid)
    {
        $food = new Food();
        $food->load($post);
        $food->Price = CartController::actionDisplay2decimal($food->Price);
        $food->BeforeMarkedUp =  CartController::actionRoundoff1decimal($food->Price / 1.3);
        $food->Restaurant_ID = $rid;
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

