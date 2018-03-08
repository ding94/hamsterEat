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
use common\models\LanguageLine;
use common\models\Model;
use common\models\User;
use common\models\Rmanager;
use common\models\Restaurant;
use common\models\Restauranttype;
use common\models\Restauranttypejunction;
use common\models\food\Foodtype;
use common\models\food\Foodtypejunction;
use common\models\food\Foodstatus;
use common\models\food\FoodName;
use common\models\food\FoodSelectiontypeName;
use common\models\food\FoodSelectionName;
use common\models\Rating\Foodrating;
use common\models\Cart\Cart;
use common\models\Cart\CartSelection;
use frontend\modules\Restaurant\controllers\FoodselectionController;
use frontend\modules\Restaurant\controllers\FoodtypeAndStatusController;
use frontend\modules\Restaurant\controllers\DefaultController;
use frontend\controllers\CartController;
use frontend\controllers\CommonController;
use frontend\controllers\ExcelController;
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
                        'actions' => [ 'insert-food','menu','delete','edit-food','postedit','recycle-bin','delete-permanent','selection-delete'],

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
                Yii::$app->session->setFlash('error', Yii::t('food','This restaurant was not valid now.'));
                return $this->redirect(Yii::$app->request->referrer);
            }
        }
        
        $valid = ValidController::FoodValid($id);
        if ($valid == false) {
            Yii::$app->session->setFlash('error', Yii::t('food','This food was not valid now.'));
            return $this->redirect(Yii::$app->request->referrer);
        }
        
        $fooddata = Food::find()->where('Food_ID = :id' ,[':id' => $id])->one();
        if(empty($fooddata))
        {
            Yii::$app->session->setFlash('error', Yii::t('food','Something Went Wrong. Please Try Again Later!'));
            return $this->redirect(Yii::$app->request->referrer);
        }

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
        $food = new Food;
        $name = new FoodName;
        $selectionname = new FoodSelectionName;
        $typename = new FoodSelectiontypeName;
        $foodtype = [new Foodselectiontype()];
        $foodselection = [[new Foodselection()]];
        $foodjunction = new Foodtypejunction();
       
        $type = ArrayHelper::map(FoodType::find()->andWhere(['and',['!=','Type_Desc','Halal'],['!=','Type_Desc','Non-Halal']])->orderBy(['(Type_Desc)' => SORT_ASC])->all(),'ID','Type_Desc');
        $halal = Foodtype::find()->where('Type_Desc=:t',[':t'=>'Halal'])->one();
        $nonhalal = Foodtype::find()->where('Type_Desc=:t',[':t'=>'Non-Halal'])->one();
        $restauranttype = Restauranttypejunction::find()->where('Restaurant_ID=:rid',[':rid'=>$rid])->joinWith('restauranttype')->all();
                
       if(Yii::$app->request->isPost)
       {
            $post = Yii::$app->request->post();
              
            $foodtypemodel = Foodtype::find()->where('Type_Desc=:t',[':t'=>$post['Type_ID'][0]])->one();
            $foodtypeid = Foodtype::find()->where('ID=:id',[':id'=>$post['Type_ID']])->one();

            if(isset($post['Foodselectiontype']) && isset($post['Foodselection']))
            {
                $true = FoodtypeAndStatusController::detectMinMax($post['Foodselectiontype'],$post['Foodselection']);
                if($true)
                {
                    return $this->redirect(Yii::$app->request->referrer); 
                }
            }

            if($foodtypemodel==null && $foodtypeid==null){
                $foodtypemodel = new Foodtype();
                $foodtypemodel->Type_Desc = $post['Type_ID'][0];
                $foodtypemodel->save();
                $post['Type_ID'] = (string)$foodtypemodel->ID;
            }
           
            $food = self::newFood($post,$rid);
            $name->load($post);
            $name->language = 'en';
           
            $foodtype = Model::createMultiple(Foodselectiontype::classname());
            $foodtypename = Model::createMultiple(FoodSelectiontypeName::classname());

            Model::loadMultiple($foodtype, Yii::$app->request->post());
            Model::loadMultiple($foodtypename,Yii::$app->request->post());

            
            $valid =  Model::validateMultiple($foodtype) && Model::validateMultiple($foodtypename) && $food->validate() && $name->validate();
         
            if (isset($post['Foodselection'][0][0])) {

                $selectiondata = FoodselectionController::validatefoodselection() ;
                $selection = $selectiondata['data'];
                $valid = $selectiondata['valid'] && $valid;
            }
          
             if ($valid) {
                $transaction = Yii::$app->db->beginTransaction();
                try {
                     
                    if ($flag = $food->save()) {

                        $status = FoodtypeAndStatusController::newFoodJuntion($post['Type_ID'],$food->Food_ID);
                        
                        $isValid = FoodtypeAndStatusController::newStatus($food->Food_ID);
                        $name->id = $food->Food_ID;
                        $isValid = $name->save()  && $isValid;

                        $flag = FoodselectionController::createfoodselection($foodtype,$foodtypename,$selection,$food->Food_ID) && $isValid && $status->validate();
                       
                        if ($flag) {
                            $status->save();
                            $transaction->commit();
                            $status = DefaultController::updateRestaurant($rid);

                            Yii::$app->session->setFlash('success',Yii::t('cart','Success!'));
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
                'foodtype' =>  $foodtype,
                'foodselection' =>  $foodselection,
                'type' => $type,
                'rid'=>$rid,
                'halal'=>$halal,
                'nonhalal'=>$nonhalal,
                'name' => $name,
                'typename' => $typename,
                'selectionname' => $selectionname,
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
        $rname = CommonController::getRestaurantName($rid);
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
                    Yii::$app->session->setFlash('success',Yii::t('food','Item Deleted.'));
                    return $this->redirect(Yii::$app->request->referrer);
                }
            }
            else
            {
                Yii::$app->session->setFlash('error',Yii::t('food','You need to pause the item first.'));
            }
        }
        else
        {
            Yii::$app->session->setFlash('error',Yii::t('common','You are not allow to perfrom this action!'));
        }
        return $this->redirect(Yii::$app->request->referrer);
    }

    public function actionSelectionDelete($id)
    {
        $selection = Foodselection::findOne($id);
        if(empty($selection))
        {
            Yii::$app->session->setFlash('error',Yii::t('cart','Something Went Wrong!'));
            return $this->redirect(Yii::$app->request->referrer);
        }
        $food = Food::find()->where('Food_ID = :fid',[':fid'=>$selection->Food_ID])->joinWith('restaurant')->one();
        if ($food['restaurant']['Restaurant_Manager'] == Yii::$app->user->identity->username){
            if($selection->Status == 0)
            {
                $selection->Status = -1;
               
                if($selection->save())
                {
                    Yii::$app->session->setFlash('success',Yii::t('food','Item Deleted.'));
                }
                else
                {
                    Yii::$app->session->setFlash('error',Yii::t('food','You need to pause the item first.'));
                }
            }
            else
            {
                Yii::$app->session->setFlash('error',Yii::t('food','You need to pause the item first.'));
            }
        }
        else
        {
            Yii::$app->session->setFlash('error',Yii::t('common','You are not allow to perfrom this action!')); 
        }
        return $this->redirect(Yii::$app->request->referrer);
       
    }

//--This function runs when a food's details are edited
    public function actionEditFood($id,$rid)
    {
        CommonController::restaurantPermission($rid);
        $food = Food::find()->where('Food_ID = :id' ,[':id' => $id])->joinWith('transName')->one();
       
        $restaurant = Restaurant::find()->where('Restaurant_ID=:rid',[':rid'=>$food['Restaurant_ID']])->one();
        $typeName = new FoodSelectiontypeName;
        $selectionName = new FoodSelectionName;
        CommonController::rmanagerApproval();
        CommonController::restaurantPermission($restaurant->Restaurant_ID);
        $foodjunction = new Foodtypejunction();
      
        $restauranttype = Restauranttypejunction::find()->where('Restaurant_ID=:rid',[':rid'=>$food['Restaurant_ID']])->joinWith('restauranttype')->all();
       

        $chosen = ArrayHelper::map($food['foodType'],'ID','ID');
        $type = ArrayHelper::map(FoodType::find()->andWhere(['and',['!=','Type_Desc','Halal'],['!=','Type_Desc','Non-Halal']])->orderBy(['(Type_Desc)' => SORT_ASC])->all(),'ID','Type_Desc');
      
        $foodtype = Foodselectiontype::find()->where('Food_ID = :id',[':id'=>$food->Food_ID])->joinWith(['transName'])->all();

        $foodselection = [];
        
        if (!empty($foodtype)) 
        {
            $foodselection = FoodselectionController::oldData($foodtype,1);
            foreach ($foodselection as $key => $value) {
                if(empty($value))
                {
                    unset($foodtype[$key]);
                    unset($foodselection[$key]);
                }
            }

        }
       
        $food->scenario = "edit";
    
        return $this->render('editfood',['food' => $food,'foodjunction'=>$foodjunction, 'chosen'=> $chosen,'type' => $type,'foodtype' => (empty($foodtype)) ? [new Foodselectiontype] : $foodtype,'foodselection' => (empty($foodselection)) ? [[new Foodselection]] : $foodselection ,'typeName'=>$typeName,'selectionName'=>$selectionName]);
    }

    public function actionPostedit($id)
    { 
        $food = Food::findOne($id);
        $name = FoodName::findOne($id);
        $restaurant = Restaurant::find()->where('Restaurant_ID=:rid',[':rid'=>$food['Restaurant_ID']])->one();
        CommonController::restaurantPermission($restaurant->Restaurant_ID);
        $modelSelectionType = Foodselectiontype::find()->where('Food_ID = :id',[':id'=>$food->Food_ID])->joinWith(['transName'])->all();

        $modelJunction = $food->junction;

        $modelSelect = [];
        $oldSelect = [];
        $selectionId = [];

        $post = Yii::$app->request->post();
        if(empty($post['Type_ID']))
        {
            Yii::$app->session->setFlash('warning', "Please Select A Type");
            return $this->redirect(Yii::$app->request->referrer);
        }
        if(isset($post['Foodselectiontype']) && isset($post['Foodselection']))
        {
            $true = FoodtypeAndStatusController::detectMinMax($post['Foodselectiontype'],$post['Foodselection']);
            if($true)
            {
                return $this->redirect(Yii::$app->request->referrer); 
            }
        }
      
        $foodtypemodel = Foodtype::find()->where('Type_Desc=:t',[':t'=>$post['Type_ID']])->one();
        $foodtypeid = Foodtype::find()->where('ID=:id',[':id'=>$post['Type_ID']])->one();
       
        if($foodtypemodel==null && $foodtypeid==null){
           
            $foodtypemodel = new Foodtype();
            $foodtypemodel->Type_Desc = $post['Type_ID'];
            
            if(!is_numeric($foodtypemodel->Type_Desc)){
                $foodtypemodel->save();    
            } else {
                Yii::$app->session->setFlash('danger', Yii::t('food',"Invalid Food Type"));
                return $this->redirect(Yii::$app->request->referrer);
            }

            $post['Type_ID'] = (string)$foodtypemodel->ID;
        }
        
        if (!empty($modelSelectionType)) 
        {
            $oldSelect = FoodselectionController::oldData($modelSelectionType,2);
        }

        $food->load($post);
        $food->Price = CartController::actionDisplay2decimal($food->Price);
        $food->BeforeMarkedUp =  CartController::actionRoundoff1decimal($food->Price / 1.3);
        
        $name->load($post);

        $oldSelectionTypeId = ArrayHelper::map($modelSelectionType, 'ID', 'ID');

        $junctionData = FoodtypeAndStatusController::diffStatus($modelJunction,$post['Type_ID']);

        $modelSelectionType = Model::createMultiple(Foodselectiontype::classname(), $modelSelectionType);

        $valid = 
        \yii\base\Model::loadMultiple($modelSelectionType,Yii::$app->request->post());

        if($valid)
        {
            foreach ($modelSelectionType as $key => $type) {
                $arraytype['FoodSelectiontypeName'] = $post['FoodSelectiontypeName'][$key];
                if($type->isNewRecord)
                {
                    $singleTname = new FoodSelectiontypeName;
                    $singleTname->language = 'en';
                }
                else
                {
                    $singleTname = $type->transName;
                }
                $singleTname->load($arraytype);
                $typeName[$key] = $singleTname;
                $valid = $singleTname->validate() && $valid;
            }
        }
        else
        {
            $valid = true;
        }
       
        $deletedSelectionTypeId = array_diff($oldSelectionTypeId, array_filter(ArrayHelper::map($modelSelectionType, 'ID', 'ID')));

        $valid = Model::validateMultiple($modelSelectionType) && $food->validate() && $name->validate();
        
        if (isset($post['Foodselection'][0][0]))
        {
            foreach ($post['Foodselection'] as $i => $select) 
            {

                $selectionId = ArrayHelper::merge($selectionId, array_filter(ArrayHelper::getColumn($select, 'ID')));

                foreach ($select as $k => $selections) 
                {

                    $data['Foodselection'] = $selections;
                    $data['FoodSelectionName'] = $post['FoodSelectionName'][$i][$k];
                   
                    $modelSelects = (isset($selections['ID']) && isset($oldSelect[$selections['ID']])) ? $oldSelect[$selections['ID']] : new Foodselection;
                    if($modelSelects->isNewRecord)
                    {
                        $singleSname = new FoodSelectionName;
                        $singleSname->language = 'en';
                    }
                    else
                    {
                        $singleSname = $modelSelects->transName;
                    }

                    $singleSname->load($data);
                    $modelSelects->load($data);
                  
                    $modelSelect[$i][$k] = $modelSelects;
                    $selectionname[$i][$k] = $singleSname;
                   
                    $valid = $modelSelects->validate()&& $valid;

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
                    if(!empty($deletedSelect))
                    {
                        foreach($deletedSelect as $value)
                        {
                            Foodselection::updateAll(['Status'=>'-1'],'ID = :id',[':id' => $value]);
                        }
                        
                    }

                    if(!empty($junctionData[0]))
                    {
                        Foodtypejunction::deleteAll('Food_ID = :fid and Type_ID = :tid',[':fid' => $food->Food_ID, ':tid' => $junctionData[0]]);
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

                        if(($flag = $selectionType->save()))
                        {
                            $typeName[$i]->id = $selectionType->ID;
                            if(!$typeName[$i]->save())
                            {
                                break;
                            }
                        }
                        else
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
                                if(($flag = $model->save()))
                                {
                                   $selectionname[$i][$k]->id = $model->ID;
                                   if(!$selectionname[$i][$k]->save())
                                    {
                                        break;
                                    }
                                }
                                else
                                {
                                    break;
                                }
                            }
                        }
                    }
                    if($flag)
                    {
                        $transaction->commit();
                        Yii::$app->session->setFlash('success',Yii::t('food',"Success edited"));
                        return $this->redirect(Yii::$app->request->referrer);

                    }
                    else
                    {
                        $transaction->rollBack();
                        Yii::$app->session->setFlash('warning', Yii::t('cart',"Failed to edit!"));
                        return $this->redirect(Yii::$app->request->referrer);
                    }
                }
            }
            catch(Exception $e)
            {
                $transaction->rollBack();
            }
            Yii::$app->session->setFlash('warning', Yii::t('cart',"Failed to edit!"));
            return $this->redirect(Yii::$app->request->referrer);
        }
        else
        {
            Yii::$app->session->setFlash('warning', Yii::t('cart',"Failed to edit!"));
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

