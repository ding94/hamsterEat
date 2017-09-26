<?php

namespace frontend\controllers;
use common\models\Orders;
use Yii;
use yii\web\Controller;

class OrderController extends \yii\web\Controller
{
    public function actionMyOrders()
    {
        $orders = Orders::find()->where('User_Username = :uname and Orders_Status != :status', [':uname'=>Yii::$app->user->identity->username, ':status'=>'Not Placed'])->all();
        
        return $this->render('myorders', ['orders'=>$orders]);
    }

}
