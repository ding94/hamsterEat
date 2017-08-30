<?php

namespace frontend\modules\Restaurant\controllers;

use yii;
use yii\web\Controller;
use common\models\Restaurant;

/**
 * Default controller for the `Restaurant` module
 */
class DefaultController extends Controller
{
    /**
     * Renders the index view for the module
     * @return string
     */
    public function actionIndex($groupArea)
    {
        //$aa = Yii::$app->request->get();
        $restaurant = restaurant::find()->where('Restaurant_AreaGroup = :group' ,[':group' => $groupArea])->all();

        return $this->render('index',['restaurant'=>$restaurant, 'groupArea'=>$groupArea]);
    }

    public function actionRestaurantDetails($rid)
    {
        $id = restaurant::find()->where('Restaurant_ID = :id' ,[':id' => $rid])->one();

        return $this->render('RestaurantDetails',['id'=>$id]);
    }

}
