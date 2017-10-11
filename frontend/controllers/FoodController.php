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
use common\models\Model;
use common\models\Orderitem;
use common\models\Orderitemselection;
use common\models\Restaurant;
use common\models\food\Foodtype;
use common\models\food\Foodtypejunction;
use common\models\food\Foodstatus;
use frontend\modules\Restaurant\controllers\FoodselectionController;
use frontend\modules\Restaurant\controllers\FoodtypeAndStatusController;
use frontend\modules\Restaurant\controllers\DefaultController;
use frontend\controllers\CartController;

class FoodController extends Controller
{
    public function actionFoodDetails($id,$rid)
    {
        $fooddata = Food::find()->where(Food::tableName().'.Food_ID = :id' ,[':id' => $id])->innerJoinWith('foodType',true)->one();
        
        $foodtype = Foodselectiontype::find()->where('Food_ID = :id',[':id' => $id])->orderBy(['ID' => SORT_ASC])->all();
        
        $orderItemSelection =new Orderitemselection;
        $orderitem = new Orderitem;

        if ($orderItemSelection->load(Yii::$app->request->post()) || $orderitem->load(Yii::$app->request->post()))
        {
            $orderitem->load(Yii::$app->request->post());
            if ($orderitem->OrderItem_Quantity < 1)
            {
                Yii::$app->session->setFlash('error', 'You cannot order less than 1.');

                return $this->redirect(['food-details', 'id'=>$id]);
            }

            foreach ($foodtype as $k => $foodtype) {
                if ($foodtype->Min > 0 && $foodtype->Max == $foodtype->Max){
                    if (count($orderItemSelection->FoodType_ID[$k]) < $foodtype->Min || count($orderItemSelection->FoodType_ID[$k]) > $foodtype->Max){
                        Yii::$app->session->setFlash('danger', 'Please select at least '.$foodtype->Min.' items and most '.$foodtype->Max.' items.');
                        return $this->redirect(Yii::$app->request->referrer);
                    }
                }
                else if ($foodtype->Min == $foodtype->Min && $foodtype->Max == $foodtype->Max) { 
                    if(count($orderItemSelection->FoodType_ID[$k]) > $foodtype->Max || count($orderItemSelection->FoodType_ID[$k]) < $foodtype->Min ){
                        Yii::$app->session->setFlash('danger', 'Please select at least '.$foodtype->Min.' items and most '.$foodtype->Max.' items.');
                        return $this->redirect(Yii::$app->request->referrer);
                    } 
                }
            }
            $quantity = $orderitem->OrderItem_Quantity;
            $remarks = $orderitem->OrderItem_Remark;
            $selected = $orderItemSelection->FoodType_ID;

            $glue = "','";
            if ($selected == !null){
            function implode_all($glue, $selected){            
                for ($i=0; $i<count($selected); $i++) {
                    if (@is_array($selected[$i])) 
                        $selected[$i] = implode_all ($glue, $selected[$i]);
                }         
                return implode($glue, $selected);
            }
            $finalselected = implode_all(',', $selected);
        } else {
            $finalselected = '';
            }

            return $this->redirect(['cart/addto-cart', 'quantity' => $quantity, 'Food_ID' => $id, 'finalselected' => $finalselected, 'remarks'=>$remarks, 'rid'=>$rid]);
        }

        return $this->renderAjax('fooddetails',['fooddata' => $fooddata,'foodtype' => $foodtype, 'orderitem'=>$orderitem ,'orderItemSelection' => $orderItemSelection]);
         
    }

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
            $foodprice = CartController::actionRoundoff1decimal($food->BeforeMarkedUp);
            $markedupprice = $foodprice * 1.3;
            $markedupprice = CartController::actionRoundoff1decimal($markedupprice);
            //var_dump($food->BeforeMarkedUp);exit;
            $food->Price = $markedupprice;
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

                            return $this->redirect(['food/menu', 'id' => $food->Food_ID, 'rid'=>$rid]);
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
        //var_dump($foodselection[0][0]);exit;
        $this->layout = 'user';
      
        return $this->render('insertfood',['food' => $food,'foodjunction'=>$foodjunction,'foodtype' => (empty($foodtype)) ? [new Foodselectiontype] : $foodtype,'foodselection' => (empty($foodselection)) ? [[new Foodselection]] : $foodselection,'type' => $type]);
        //return $this->render('insertfood',['food' => $food,'foodjunction'=>$foodjunction,'foodtype' => [new Foodselectiontype],'foodselection' => [ [new Foodselection]],'type' => $type]);
    }
    
     public function actionMenu($rid)
     {
        $menu = food::find()->where('Restaurant_ID=:id and Status = :status', [':id' => $rid, ':status'=>1])->innerJoinWith('foodType',true)->innerJoinWith('foodStatus',true)->all();
        $this->layout = 'user';
         
         return $this->render('Menu',['menu'=>$menu, 'rid'=>$rid, 'page'=>'menu']);

     }

    public function actionDelete($rid,$id,$page)
    {
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

         return $this->redirect(['menu','menu'=>$menu,'id'=>$id,'rid'=>$rid,'page'=>$page]);

    }

     public function actionEditFood($id)
     {
        $food = Food::find()->where(Food::tableName().'.Food_ID = :id' ,[':id' => $id])->innerJoinWith('foodType',true)->one();
        $chosen = ArrayHelper::map($food['foodType'],'ID','ID');
        $type = ArrayHelper::map(FoodType::find()->orderBy(['(Type_Desc)' => SORT_ASC])->all(),'ID','Type_Desc');
      
        $foodtype =$food->foodselectiontypes;
        $foodselection = [];
      
        if (!empty($foodtype)) {
            foreach ($foodtype as $i => $ftype) {                 
                $foodtypes = $ftype->foodSelection;
               // var_dump($foodtypes);exit;
                $foodselection[$i] = $foodtypes;
            }
        }
        $oldRooms = [];
        $upload = new Upload();
        $picpath = $food['PicPath'];
        $food->scenario = "edit";
    
        if (Yii::$app->request->isPost) {
           
            $post = Yii::$app->request->post();  
                  
            $upload->imageFile =  UploadedFile::getInstance($food, 'PicPath');

            $food->load($post);
            $food->BeforeMarkedUp = CartController::actionRoundoff1decimal($post['Food']['roundprice']);
            $markedupprice = CartController::actionRoundoff1decimal($post['Food']['roundprice']) * 1.3;
            $markedupprice = CartController::actionRoundoff1decimal($markedupprice);
            $food->Price = $markedupprice;
    
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
         
            $foodselection = [];
            //var_dump($isValid);exit;
            $foodtype = Model::createMultiple(Foodselectiontype::classname(), $foodtype);

            Model::loadMultiple($foodtype, Yii::$app->request->post());
        
            $valid = $food->validate();
    
            $foodsIDs = [];
             if (isset($_POST['Foodselection'][0][0])) {
                $foodselection = FoodselectionController::validatefoodselection($post['Foodselection']);
            }
           
            if ($valid) {
                
                $transaction = Yii::$app->db->beginTransaction();
                try {
                    if ($flag = $food->save()) {

                        Foodtypejunction::deleteAll(['Food_ID'=>$food->Food_ID]);

                        Foodselection::deleteAll(['Food_ID' => $food->Food_ID]);

                        Foodselectiontype::deleteAll(['Food_ID' => $food->Food_ID]);

                        FoodtypeAndStatusController::newFoodJuntion($post['Type_ID'],$food->Food_ID);

                        $isValid = FoodtypeAndStatusController::newStatus($food->Food_ID);

                        $flag = FoodselectionController::createfoodselection($foodtype,$foodselection,$food->Food_ID);

                        if ($flag) {
                            $transaction->commit();
                            return $this->redirect(['food/food-details', 'id' => $food->Food_ID]);
                        } 
                         else {
                        $transaction->rollBack();
                        }
                    }
                   
                } catch (Exception $e) {
                    $transaction->rollBack();
                }
            }

        }
         $this->layout = 'user';
         return $this->renderPartial('editfood',['food' => $food,'chosen'=> $chosen,'type' => $type,'foodtype' => (empty($foodtype)) ? [new Foodselectiontype] : $foodtype,'foodselection' => (empty($foodselection)) ? [[new Foodselection]] : $foodselection]);

        
       
    }

    protected static function newFood($post,$rid,$upload)
    {
        $food = new Food();
        $food->load($post);
        $food->BeforeMarkedUp = CartController::actionDisplay2decimal($food->BeforeMarkedUp);
        $food->Restaurant_ID = $rid;
        $food->PicPath = $upload;
        $food->Ingredient = 'xD';
        return $food;
    }

    public function actionRecycleBin($rid)
    {
        $menu = food::find()->where('Restaurant_ID=:id and Status = :status', [':id' => $rid, ':status'=>0])->innerJoinWith('foodType',true)->innerJoinWith('foodStatus',true)->all();
        $this->layout = 'user';
        
        return $this->render('Menu',['menu'=>$menu, 'rid'=>$rid, 'page'=>'recyclebin']);
    }

    public function actionDeletePermanent($rid,$id,$page)
    {
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
    
}

