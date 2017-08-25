<?php
namespace frontend\controllers;
use Yii;
use yii\web\Controller;
use common\models\User\Userdetails;
use common\models\User\Useraddress;
use common\models\User\Rmanager;
use common\models\User;

class UserController extends Controller
{
    public function actionUserProfile()
    {
        $user = User::find()->where('username = :id' ,[':id' => Yii::$app->user->identity->username])->one();
        $userdetails = Userdetails::find()->where('User_Username = :id' ,[':id' => Yii::$app->user->identity->username])->one();
       $useraddress = Useraddress::find()->where('User_Username = :id' ,[':id' => Yii::$app->user->identity->username])->one();
        return $this->render('userprofile',['user' => $user,'userdetails' => $userdetails,'useraddress'=>$useraddress]);
       
    }
    public function actionUserdetails()
    {
        $detail = UserDetails::find()->where('User_Username = :uname'  , [':uname' => Yii::$app->user->identity->username])->one();
		
           
            if($detail->load(Yii::$app->request->post()) && $detail->save())
            {
				   Yii::$app->session->setFlash('success', 'Update Successful');
				   return $this->redirect(['index']);
			}

            $address = Useraddress::find()->where('User_Username = :uname'  , [':uname' => Yii::$app->user->identity->username])->one();
            
           
            if($address->load(Yii::$app->request->post()) && $address->save())
            {
				   Yii::$app->session->setFlash('success', 'Update Successful');
				   return $this->redirect(['index']);
			}
		//$this->view->title = 'Update Profile';
		//$this->layout = 'user';
		return $this->render("userdetails",['detail' => $detail,'address'=>$address]);
    }
    public function actionUseraddress()
    {
        
		//$this->view->title = 'Update Profile';
		//$this->layout = 'user';
		return $this->render("useraddress",['address' => $address]);
    }
    
}
