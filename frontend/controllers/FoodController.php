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
    public function actionFoodDetails($id)
    {
        $fooddata = Food::find()->where(Food::tableName().'.Food_ID = :id' ,[':id' => $id])->innerJoinWith('foodType',true)->one();
        
        $foodtype = Foodselectiontype::find()->where('Food_ID = :id',[':id' => $id])->all();
        $orderItemSelection =new Orderitemselection;
        $orderitem = new Orderitem;
        
        if ($orderItemSelection->load(Yii::$app->request->post()) && $orderitem->load(Yii::$app->request->post()))
        {
            $orderitem->load(Yii::$app->request->post());
            if ($orderitem->OrderItem_Quantity < 1)
            {
                Yii::$app->session->setFlash('error', 'You cannot order less than 1.');

                return $this->redirect(['food-details', 'id'=>$id]);
            }

            foreach ($foodtype as $k => $foodtype) {
                if ($foodtype->Min > 0 && $foodtype->Max == $foodtype->Max){
                    if ($orderItemSelection->FoodType_ID[$k] == '' || count($orderItemSelection->FoodType_ID[$k]) > $foodtype->Max){
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
            function implode_all($glue, $selected){            
                for ($i=0; $i<count($selected); $i++) {
                    if (@is_array($selected[$i])) 
                        $selected[$i] = implode_all ($glue, $selected[$i]);
                }         
                return implode($glue, $selected);
            }
            //var_dump(implode_all($glue, $selected));exit;
            $finalselected = implode_all(',', $selected);

            return $this->redirect(['cart/addto-cart', 'quantity' => $quantity, 'Food_ID' => $id, 'finalselected' => $finalselected, 'remarks'=>$remarks]);
        }

        return $this->render('fooddetails',['fooddata' => $fooddata,'foodtype' => $foodtype, 'orderitem'=>$orderitem ,'orderItemSelection' => $orderItemSelection]);
         
    }

    public function actionInsertFood($rid)
    {
        $food = new Food();
        $food->scenario = "new"; 
        $foodtype = [new Foodselectiontype()];
        $foodselection = [[new Foodselection()]];
        $upload = new Upload();
        
        $foodjunction = new Foodtypejunction();
        $type = ArrayHelper::map(FoodType::find()->all(),'ID','Type_Desc');
    
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
                $valid =  $valid && $foodselection[1] ;
            }
         
             if ($valid) {
                $transaction = Yii::$app->db->beginTransaction();
                try {
                        
                    if ($flag = $food->save()) {

                        FoodtypeAndStatusController::newFoodJuntion($post['Type_ID'],$food->Food_ID);

                        $isValid = FoodtypeAndStatusController::newStatus($food->Food_ID);

                        $flag = FoodselectionController::createfoodselection($foodtype,$foodselection[0],$food->Food_ID) && $isValid;

                        if ($flag) {
                            $transaction->commit();
                            $status = DefaultController::updateRestaurant($rid);

                            return $this->redirect(['food/food-details', 'id' => $food->Food_ID]);
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
      
        return $this->render('insertfood',['food' => $food,'foodjunction'=>$foodjunction,'foodtype' => (empty($foodtype)) ? [new Foodselectiontype] : $foodtype,'foodselection' => (empty($foodselection)) ? [[new Foodselection]] : $foodselection,'type' => $type]);
    }
    
     public function actionMenu($rid)
     {
        $menu = food::find()->where('Restaurant_ID=:id', [':id' => $rid])->innerJoinWith('foodType',true)->innerJoinWith('foodStatus',true)->all();
         $this->layout = 'user';
         
         return $this->render('Menu',['menu'=>$menu, 'rid'=>$rid]);

     }

    public function actionDelete($rid,$id)
    {
        $status = Foodstatus::find()->where('Food_ID = :fid',[':fid'=>$id])->one();
        if ($status['Status'] == true)
        {
            $sql = "UPDATE foodstatus SET status = false WHERE Food_ID ='$id'";
            Yii::$app->db->createCommand($sql)->execute();
        }
        else
        {
            $sql = "UPDATE foodstatus SET status = true WHERE Food_ID ='$id'";
            Yii::$app->db->createCommand($sql)->execute();
        }
         $rid = $rid;
         $menu = food::find()->where('Restaurant_ID = :id' ,[':id' => $rid])->all();
         $this->layout = 'user';

         return $this->redirect(['menu','menu'=>$menu,'id'=>$id,'rid'=>$rid]);

    }

     public function actionEditFood($id)
     {
        $food = Food::find()->where(Food::tableName().'.Food_ID = :id' ,[':id' => $id])->innerJoinWith('foodType',true)->one();
        $chosen = ArrayHelper::map($food['foodType'],'ID','ID');
        $type = ArrayHelper::map(FoodType::find()->all(),'ID','Type_Desc');
       
        $foodtype =$food->foodselectiontypes;
        $foodselection = [];
        $oldRooms = [];
        $upload = new Upload();
        $picpath = $food['PicPath'];
        $food->scenario = "edit";
        
        if (Yii::$app->request->isPost) {
           
            $post = Yii::$app->request->post();  
                            
            $upload->imageFile =  UploadedFile::getInstance($food, 'PicPath');

            $food->load($post);
            $food->Price = CartController::actionDisplay2decimal($post['Food']['roundprice']);

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
            
            $foodtype = Model::createMultiple(Foodselectiontype::classname(), $foodtype);

            Model::loadMultiple($foodtype, Yii::$app->request->post());
        
            $valid = $food->validate();

            $foodsIDs = [];
             if (isset($_POST['Foodselection'][0][0])) {
                $foodselection = FoodselectionController::validatefoodselection($post['Foodselection']);
                $valid =  $valid && $foodselection[1] ;
            }
            
            if ($valid) {
                
                $transaction = Yii::$app->db->beginTransaction();
                try {
                    if ($flag = $food->save()) {

                        Foodtypejunction::deleteAll(['Food_ID'=>$id]);

                        Foodselection::deleteAll(['Food_ID' => $id]);
                       
                        Foodselectiontype::deleteAll(['Food_ID' => $id]);

                        FoodtypeAndStatusController::newFoodJuntion($post['Type_ID'],$food->Food_ID);

                        $isValid = FoodtypeAndStatusController::newStatus($food->Food_ID);

                        $flag = FoodselectionController::createfoodselection($foodtype,$foodselection[0],$food->Food_ID);

                        if ($flag) {

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
         return $this->render('editfood',['food' => $food,'chosen'=> $chosen,'type' => $type,'foodtype' => (empty($foodtype)) ? [new Foodselectiontype] : $foodtype,'foodselection' => (empty($foodselection)) ? [[new Foodselection]] : $foodselection]);

        
       
    }

    protected static function newFood($post,$rid,$upload)
    {
        $food = new Food();
        $food->load($post);
        $food->Price = CartController::actionDisplay2decimal($food->Price);
        $food->Restaurant_ID = $rid;
        $food->PicPath = $upload;
        $food->Ingredient = 'xD';
        return $food;
    }
    
}

