<?php
namespace frontend\controllers;
use Yii;
use yii\web\Controller;
use common\models\Orderitem;
use common\models\User;
use common\models\food;

class CartController extends Controller
{
    public function actionAddtoCart()
    {
        
        if(Yii::$app->user->isGuest){
            return $this->redirect(['site/login']);
        }
        
        $user = User::find()->where('username = :id' ,[':id' => Yii::$app->user->identity->username])->one();
       
         if(Yii::$app->request->isPost){
             $post=Yii::$app->request->post();
             $num =Yii::$app->request->post()['Quantity'];
             $data['Cart'] = $post;
             $data['Cart']['userid'] = $user;
           
         }
         if (Yii::$app->request->isGet){
             $foodid = Yii::$app->request->get("Food_ID");
             $model = food::find()->where('Food_ID = :foodid',[':foodid'=>$foodid])->one();
             //$price = $model->issale ? $model->saleprice :$model->price;
             $num =1;
             $data['Cart'] =['Food_ID' =>$foodid,'Quantity'=>$num,'id'=>$user];
         }
         if(!$model = orderitem::find()->where('Food_ID = :foodid and username = :id',[':foodid' => $foodid, ':id' => $user])){
             $model = new Orderitem;
             var_dump($model);exit;
    
         }
         else{
             //$data['Cart']['Quantity']= $model->$num;
             Yii::$app->getSession()->setFlash('warning','Sorry');
         }
         $model->load($data);
         $model->save();
         return $this->redirect(['restaurant/index']);

    }
}