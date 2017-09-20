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

class FoodController extends Controller
{
    public function actionFoodDetails($id)
    {
        $fooddata = food::find()->where('Food_ID = :id' ,[':id' => $id])->one();

        $orderitem = new Orderitem;

        if ($orderitem->load(Yii::$app->request->post()))
        {
            $quantity = $orderitem->OrderItem_Quantity;

            return $this->redirect(array('cart/addto-cart', 'quantity' => $quantity, 'Food_ID' => $id));
        }

        return $this->render('fooddetails',['fooddata' => $fooddata, 'orderitem'=>$orderitem]);
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
      
        return $this->render('insertfood',['food' => $food,'foodtype' => (empty($foodtype)) ? [new Foodtype] : $foodtype,'foodselection' => (empty($foodselection)) ? [[new Foodselection]] : $foodselection]);
    }
    
 
   
    
}
