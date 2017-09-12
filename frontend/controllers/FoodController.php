<?php
namespace frontend\controllers;
use Yii;
use yii\web\Controller;
use common\models\Food;
use common\models\Upload;
use yii\web\UploadedFile;
use common\models\Foodselection;

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

      $foodselection = new Foodselection();
       if($food->load(Yii::$app->request->post()))
       {
           $food->save();
           $foodselection->save();
       }
      
        // $foodselection->save();
        return $this->render('insertfood',['food'=>$food,'foodselection'=>$foodselection]);
    }
}