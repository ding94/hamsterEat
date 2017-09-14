<?php
namespace frontend\controllers;
use Yii;
use yii\web\Controller;
use common\models\Food;
use common\models\Upload;
use yii\web\UploadedFile;
use common\models\Foodselection;
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;

class FoodController extends Controller
{
    public function actionFoodDetails($foodid)
    {
        $fooddata = food::find()->where('Food_ID = :id' ,[':id' => $foodid])->one();

         return $this->render('fooddetails',['fooddata' => $fooddata,]);
    }

    public function actionInsertFood()
    {
         
        $food = new Food();

      $foodselection = [new Foodselection()];
    
       if($food->load(Yii::$app->request->post()))
       {
           
           $foodselection = foodselection::createMultiple(Foodselection::classname());
           foodselection::loadMultiple($foodselection, Yii::$app->request->post());
           if (Yii::$app->request->isAjax) {
                Yii::$app->response->format = Response::FORMAT_JSON;
                return ArrayHelper::merge(
                    ActiveForm::validateMultiple($foodselection),
                    ActiveForm::validate($food)
                );

                $valid = foodselection::validateMultiple($foodselection) && $valid;
                 if ($valid) {
                $transaction = \Yii::$app->db->beginTransaction();
                try {
                    if ($flag = $food ->save(false)) {
                        foreach ($foodselection as $foodselection) {
                            $foodselection->Selection_ID = $food->Food_ID;
                            if (! ($flag = $foodselection->save(false))) {
                                $transaction->rollBack();
                                break;
                            }
                        }
                    }
                    if ($flag) {
                        $transaction->commit();
                       //return $this->redirect(['view', 'id' => $modelCustomer->id]);
                    }
                } catch (Exception $e) {
                    $transaction->rollBack();
                }
            }
       }
      
       
        
    }
    
    return $this->render('insertfood',['food' => $food,'foodselection' => (empty($foodselection)) ? [new Foodselection] : $foodselection]);
    
    
}
}