<?php
namespace frontend\controllers;
use Yii;
use yii\web\Controller;
use common\models\Food;
use common\models\Upload;
use yii\web\UploadedFile;
use common\models\Foodselection;
use common\models\Foodtype;
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;
use common\models\Model;
use common\models\Orderitem;
use common\models\Orderitemselection;


class FoodController extends Controller
{
    public function actionFoodDetails($id)
    {
        $fooddata = food::find()->where('Food_ID = :id' ,[':id' => $id])->one();
        $foodtype = foodtype::find()->where('Food_ID = :id',[':id' => $id])->all();
         
        $orderItemSelection =new Orderitemselection;
        
        $orderitem = new Orderitem;
       
       
          
        if ($orderitem->load(Yii::$app->request->post()))
        {
            $quantity = $orderitem->OrderItem_Quantity;
            $selection = $orderItemSelection->FoodType_ID;

            var_dump($selection);exit;

            return $this->redirect(array('cart/addto-cart', 'quantity' => $quantity, 'Food_ID' => $id, 'selection' => $selection, 'foodtypeid'=>$foodtypeid));
        }

        return $this->render('fooddetails',['fooddata' => $fooddata,'foodtype' => $foodtype, 'orderitem'=>$orderitem ,'orderItemSelection' => $orderItemSelection]);
         
    }

    public function actionInsertFood($rid)
    {
         
        $food = new Food();
        $foodtype = [new Foodtype()];
        $foodselection = [[new Foodselection()]];
        $upload = new Upload();
        $path = Yii::$app->request->baseUrl.'/imageLocation/';
    
       if($food->load(Yii::$app->request->post()))
       {
           $post = Yii::$app->request->post();
           $upload->imageFile =  UploadedFile::getInstance($food, 'Food_FoodPicPath');
    		$upload->imageFile->name = time().'.'.$upload->imageFile->extension;
            $location = 'imageLocation/';
    		$upload->upload($location);
         
			        
    		//$model->load($post);
            $food->Restaurant_ID = $rid;
            $food->Food_FoodPicPath = $upload->imageFile->name;
            $food->Food_Type = implode(',',$food->Food_Type);
           
           $foodtype = Model::createMultiple(Foodtype::classname());
           Model::loadMultiple($foodtype, Yii::$app->request->post());

           // validate person and houses models
            $valid = $food->validate();
            //$valid = Model::validateMultiple($foodtype) && $valid;

             if (isset($_POST['Foodselection'][0][0])) {
                foreach ($_POST['Foodselection'] as $i => $foodtypes) {
                    foreach ($foodtypes as $ix => $foodselections) {
                        $data['Foodselection'] = $foodselections;
                        $modelfoodselection = new Foodselection;
                        $modelfoodselection->load($data);
                        $foodselection[$i][$ix] = $modelfoodselection;
                        $valid = $modelfoodselection->validate();
                    }
                }
            }

         if ($valid) {
                $transaction = Yii::$app->db->beginTransaction();
                try {
                    
                    if ($flag = $food->save(false)) {
                        foreach ($foodtype as $i => $modelfoodtype) {
                           
                           // if ($flag === false) {
                            //    break;
                          //  }

                            $modelfoodtype->Food_ID = $food->Food_ID;

                            if (!($flag = $modelfoodtype->save(false))) {
                                
                                break;
                            }

                            if (isset($foodselection[$i]) && is_array($foodselection[$i])) {
                                foreach ($foodselection[$i] as $ix => $modelfoodselection) {
                                    $modelfoodselection->FoodType_ID = $modelfoodtype->FoodType_ID;
                                    $modelfoodselection->Food_ID = $food->Food_ID;
                                    if (!($flag = $modelfoodselection->save(false))) {
                                        break;
                                    }
                                }
                            }
                        }
                    }

                    if ($flag) {
                        $transaction->commit();
                        return $this->redirect(['food/food-details', 'id' => $food->Food_ID]);
                    } else {
                        $transaction->rollBack();
                    }
                } catch (Exception $e) {
                    $transaction->rollBack();
                }
            }
        }
        $this->layout = 'user';
      
        return $this->render('insertfood',['food' => $food,'foodtype' => (empty($foodtype)) ? [new Foodtype] : $foodtype,'foodselection' => (empty($foodselection)) ? [[new Foodselection]] : $foodselection]);
    }
    
     public function actionMenu($rid){
         $menu = food::find()->where('Restaurant_ID = :id' ,[':id' => $rid])->andWhere('Food_Deleted = :dlt',[':dlt' => 0])->all();
       

         $this->layout = 'user';
         
         return $this->render('menu',['menu'=>$menu]);

     }
     public function actionDelete($rid,$id){
         $sql = "UPDATE food SET Food_Deleted = true WHERE Food_ID ='$id' AND Restaurant_ID = $rid";
         Yii::$app->db->createCommand($sql)->execute();
         $rid = $rid;
         $menu = food::find()->where('Restaurant_ID = :id' ,[':id' => $rid])->all();
         $this->layout = 'user';

         return $this->redirect(['menu','menu'=>$menu,'id'=>$id,'rid'=>$rid]);

}
     public function actionEditFood($id){
         
        $food = $this->findModel($id);
        $foodtype =$food->foodTypes;
        $foodselection = [];
        $oldRooms = [];
        $upload = new Upload();
        $picpath = $food['Food_FoodPicPath'];

         if (!empty($foodtype)) {
             foreach ($foodtype as $i => $ftype) {
                 
                 $foodtypes = $ftype->foodSelection;
                 $foodselection[$i] = $foodtypes;
               $oldRooms = ArrayHelper::merge(ArrayHelper::index($foodtypes, 'Selection_ID'), $oldRooms);
         }
        }

        if ($food->load(Yii::$app->request->post())) {
           
                $post = Yii::$app->request->post();               
                $upload->imageFile =  UploadedFile::getInstance($food, 'Food_FoodPicPath');
                 if (!is_null($upload->imageFile))
                {
                    $upload->imageFile->name = time().'.'.$upload->imageFile->extension;
                    // $post['User_PicPath'] = 
                     $upload->upload('imageLocation/');
                     
                     //$restaurantdetails->load($post);
                 
                     $food->Food_FoodPicPath = $upload->imageFile->name;
     
                     Yii::$app->session->setFlash('success', 'Upload Successful');
                }
                else
                {
                    $food->Food_FoodPicPath = $picpath;
                }

            $food->Food_Type = implode(',',$food->Food_Type);
            // reset
            $foodselection = [];
            
            $oldHouseIDs = ArrayHelper::map($foodtype, 'FoodType_ID', 'FoodType_ID');
            $foodtype = Model::createMultiple(Foodtype::classname(), $foodtype);
            Model::loadMultiple($foodtype, Yii::$app->request->post());
            $deletedHouseIDs = array_diff($oldHouseIDs, array_filter(ArrayHelper::map($foodtype, 'FoodType_ID', 'FoodType_ID')));

            // validate person and houses models
            $valid = $food->validate();
           // $valid = Model::validateMultiple($foodtype) && $valid;

            $foodsIDs = [];
             if (isset($_POST['Foodselection'][0][0])) {
               foreach ($_POST['Foodselection'] as $i => $foodtypes) {
                    $foodsIDs = ArrayHelper::merge($foodsIDs, array_filter(ArrayHelper::getColumn($foodtypes, 'FoodType_ID')));
                     foreach ($foodtypes as $ix => $foodselections) {
                         $data['Foodselection'] = $foodselections;
                        $modelfoodselection = (isset($foodselections['Selection_ID']) && isset($oldRooms[$foodselections['Selection_ID']])) ? $oldRooms[$foodselections['Selection_ID']] : new Foodselection;
                        $modelfoodselection->load($data);
                        $foodselection[$i][$ix] = $modelfoodselection;
                        $valid = $modelfoodselection->validate();                    
                    }
                }
            }

            $oldRoomsIDs = ArrayHelper::getColumn($oldRooms, 'Selection_ID');
            $deletedRoomsIDs = array_diff($oldRoomsIDs, $foodsIDs);

            if ($valid) {
                
                $transaction = Yii::$app->db->beginTransaction();
                try {
                    if ($flag = $food->save(false)) {

                        if (! empty($deletedRoomsIDs)) {
                            Foodselection::deleteAll(['Selection_ID' => $deletedRoomsIDs]);
                        }

                        if (! empty($deletedHouseIDs)) {
                            Foodtype::deleteAll(['FoodType_ID' => $deletedHouseIDs]);
                        }

                           foreach ($foodtype as $i => $modelfoodtype) {

                            if ($flag === false) {
                                break;
                            }

                            $modelfoodtype->Food_ID = $food->Food_ID;

                            if (!($flag = $modelfoodtype->save(false))) {
                                break;
                            }

                            if (isset($foodselection[$i]) && is_array($foodselection[$i])) {
                                foreach ($foodselection[$i] as $ix => $modelfoodselection) {
                                   $modelfoodselection->FoodType_ID = $modelfoodtype->FoodType_ID;
                                    $modelfoodselection->Food_ID = $food->Food_ID;
                                    if (!($flag = $modelfoodselection->save(false))) {
                                        break;
                                    }
                                }
                            }
                        }
                    }

                    if ($flag) {
                        $transaction->commit();
                        return $this->redirect(['food/food-details', 'id' => $food->Food_ID]);
                    } else {
                        $transaction->rollBack();
                    }
                } catch (Exception $e) {
                    $transaction->rollBack();
                }
            }
        }
         $this->layout = 'user';
         return $this->render('editfood',['food' => $food,'foodtype' => (empty($foodtype)) ? [new Foodtype] : $foodtype,'foodselection' => (empty($foodselection)) ? [[new Foodselection]] : $foodselection]);

        
       
}
 protected function findModel($id)
    {
        if (($model = food::findOne($id)) !== null) {         
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }



}
