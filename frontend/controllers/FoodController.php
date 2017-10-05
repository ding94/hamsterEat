<?php
namespace frontend\controllers;

use Yii;
use yii\web\Controller;
use common\models\food\Food;
use common\models\Upload;
use yii\web\UploadedFile;
use common\models\food\Foodselectiontype;
use common\models\food\FoodSelection;
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;
use common\models\Model;
use common\models\Orderitem;
use common\models\Orderitemselection;
use common\models\Restaurant;
use common\models\food\Foodtype;
use common\models\food\Foodtypejunction;
use common\models\food\Foodstatus;

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
        $path = Yii::$app->request->baseUrl.'/imageLocation/';
        
        $foodjunction = new Foodtypejunction();
        $type = ArrayHelper::map(FoodType::find()->all(),'ID','Type_Desc');
    
       if($food->load(Yii::$app->request->post()))
       {
            $post = Yii::$app->request->post();
           
            $upload->imageFile =  UploadedFile::getInstance($food, 'PicPath');
    		$upload->imageFile->name = time().'.'.$upload->imageFile->extension;
            $location = 'imageLocation/';
    		$upload->upload($location);
        
    		//$model->load($post);
            $food->Restaurant_ID = $rid;
            $food->PicPath = $upload->imageFile->name;
            //$food->Food_Type = implode(',',$food->Food_Type);
            $food->Ingredient = 'xD';
            $food->Nickname = 'xD';
            //$food->Food_TotalBought = "0";
            //$food->Food_TotalRated = "0";
           
           $foodtype = Model::createMultiple(Foodselectiontype::classname());
           Model::loadMultiple($foodtype, Yii::$app->request->post());
       
           // validate person and houses models
            $valid = $food->validate();
            //$valid = Model::validateMultiple($foodtype) && $valid;
            //var_dump($valid);exit;
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
          // var_dump($foodtype);exit;
         if ($valid) {
                $transaction = Yii::$app->db->beginTransaction();
                try {
                    
                    if ($flag = $food->save()) {
                        foreach ($post['Type_ID'] as $typeid) {
                            $newtype = new Foodtypejunction;
                           
                            $newtype->Food_ID =$food->Food_ID;
                            $newtype->Type_ID = $typeid;
                            $newtype->save();
                        }

                        $newstatus = new Foodstatus;

                        $newstatus->Food_ID = $food->Food_ID;
                        $newstatus->Status = 1;

                        $newstatus->save();

                        foreach ($foodtype as $i => $modelfoodtype) {
                          // var_dump($food);exit;
                           // if ($flag === false) {
                            //    break;
                          //  }

                            $modelfoodtype->Food_ID = $food->Food_ID;
                            
                            if (!($flag = $modelfoodtype->save(false))) {
                                
                                break;
                            }

                            if (isset($foodselection[$i]) && is_array($foodselection[$i])) {
                                foreach ($foodselection[$i] as $ix => $modelfoodselection) {
                                    $modelfoodselection->Type_ID = $modelfoodtype->ID;
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
                        $status = restaurant::find()->where('Restaurant_ID = :rid', [':rid'=>$rid])->one();
                        if ($status['Restaurant_Status'] == 'Under Renovation')
                        {
                            $sql = "UPDATE restaurant SET Restaurant_Status = 'Operating' WHERE Restaurant_ID = ".$rid."";
                            Yii::$app->db->createCommand($sql)->execute();
                        }
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
         if (!empty($foodtype)) {
             foreach ($foodtype as $i => $ftype) {
                 
                 $foodselectiontypes = $ftype->foodSelection;
                 $foodselection[$i] = $foodselectiontypes;
               $oldRooms = ArrayHelper::merge(ArrayHelper::index($foodselectiontypes, 'Food_ID'), $oldRooms);
         }
        }

        if ($food->load(Yii::$app->request->post())) {
           
                $post = Yii::$app->request->post();               
                $upload->imageFile =  UploadedFile::getInstance($food, 'PicPath');
                 if (!is_null($upload->imageFile))
                {
                    $upload->imageFile->name = time().'.'.$upload->imageFile->extension;
                    // $post['User_PicPath'] = 
                     $upload->upload('imageLocation/');
                     
                     //$restaurantdetails->load($post);
                 
                     $food->PicPath = $upload->imageFile->name;
     
                     Yii::$app->session->setFlash('success', 'Upload Successful');
                }
                else
                {
                    $food->PicPath = $picpath;
                }

            //$food->Food_Type = implode(',',$food->Food_Type);
            // reset
            $foodselection = [];
            
            $oldHouseIDs = ArrayHelper::map($foodtype, 'Food_ID', 'Food_ID');
            $foodtype = Model::createMultiple(Foodselectiontype::classname(), $foodtype);
            Model::loadMultiple($foodtype, Yii::$app->request->post());
            $deletedHouseIDs = array_diff($oldHouseIDs, array_filter(ArrayHelper::map($foodtype, 'Food_ID', 'Food_ID')));

            // validate person and houses models
            $valid = $food->validate();
           // $valid = Model::validateMultiple($foodtype) && $valid;

            $foodsIDs = [];
             if (isset($_POST['Foodselection'][0][0])) {
               foreach ($_POST['Foodselection'] as $i => $foodtypes) {
                    $foodsIDs = ArrayHelper::merge($foodsIDs, array_filter(ArrayHelper::getColumn($foodtypes, 'Food_ID')));
                     foreach ($foodtypes as $ix => $foodselections) {
                         $data['Foodselection'] = $foodselections;
                        $modelfoodselection = (isset($foodselections['Food_ID']) && isset($oldRooms[$foodselections['Food_ID']])) ? $oldRooms[$foodselections['Food_ID']] : new Foodselection;
                        $modelfoodselection->load($data);
                        $foodselection[$i][$ix] = $modelfoodselection;
                        $valid = $modelfoodselection->validate();                    
                    }
                }
            }

            $oldRoomsIDs = ArrayHelper::getColumn($oldRooms, 'Food_ID');
            $deletedRoomsIDs = array_diff($oldRoomsIDs, $foodsIDs);

            Foodtypejunction::deleteAll('Food_ID = :fid', [':fid'=>$id]);

            foreach ($post['Type_ID'] as $typeid) {
                $newtype = new Foodtypejunction;
               
                $newtype->Food_ID =$food->Food_ID;
                $newtype->Type_ID = $typeid;
                $newtype->save();
            }
            
            if ($valid) {
                
                $transaction = Yii::$app->db->beginTransaction();
                try {
                    if ($flag = $food->save(false)) {

                        if (! empty($deletedRoomsIDs)) {
                            Foodselection::deleteAll(['Food_ID' => $deletedRoomsIDs]);
                        }

                        if (! empty($deletedHouseIDs)) {
                            Foodselectiontype::deleteAll(['Food_ID' => $deletedHouseIDs]);
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
                                   $modelfoodselection->Type_ID = $modelfoodtype->ID;
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
         return $this->render('editfood',['food' => $food,'chosen'=> $chosen,'type' => $type,'foodtype' => (empty($foodtype)) ? [new Foodtype] : $foodtype,'foodselection' => (empty($foodselection)) ? [[new Foodselection]] : $foodselection]);

        
       
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
