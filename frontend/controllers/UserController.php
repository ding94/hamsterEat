<?php
namespace frontend\controllers;
use Yii;
use yii\web\Controller;
use common\models\User\Userdetails;
use common\models\User;

class UserController extends Controller
{
    public function actionUserProfile()
    {
        $user = User::find()->where('username = :id' ,[':id' => Yii::$app->user->identity->username])->one();
        $userdetails = Userdetails::find()->where('User_Username = :id' ,[':id' => Yii::$app->user->identity->username])->one();
       
        return $this->render('userprofile',['user' => $user,'userdetails' => $userdetails]);
       
    }
}
