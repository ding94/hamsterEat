<?php
namespace frontend\controllers;
use Yii;
use yii\web\Controller;
use common\models\Food;
use common\models\Upload;
use yii\web\UploadedFile;

class FoodController extends Controller
{
    public function actionFoodDetails()
    {
        $food = food::find()->where('Food_ID = :id' ,[':id' => 1])->one();

         return $this->render('fooddetails',['food' => $food,]);
    }
    public function actionInsertFood()
    {
        $upload = new Upload();
        $food = new Food();
        $path = Yii::$app->request->baseUrl.'/imageLocation';

        return $this->render('insertfood',['food'=>$food]);
    }

}