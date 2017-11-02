<?php
namespace frontend\controllers;
use Yii;
use yii\web\Controller;
use yii\helpers\ArrayHelper;
use common\models\user\Userdetails;
use common\models\user\Useraddress;;
use common\models\User;
use common\models\user\Changepassword;
use common\models\Upload;
use yii\web\UploadedFile;
use common\models\Account\Accountbalance;
use frontend\models\Accounttopup;
use common\models\Account\Memberpoint;
use frontend\controllers\CommonController;
use yii\filters\AccessControl;

class UserController extends CommonController
{
    public function behaviors()
    {
         return [
             'access' => [
                 'class' => AccessControl::className(),
                 'rules' => [
                    [
                        'actions' => ['user-profile','userdetails','useraddress','userbalance','changepassword','primary-address','newaddress','delete-address'],
                        'allow' => true,
                        'roles' => ['@'],

                    ],
                    //['actions' => ['rating-data'],'allow' => true,'roles' => ['?'],],
                 ]
             ]
        ];
    }

    public function actionUserProfile()
    {
        $user = User::find()->where('user.id = :id' ,[':id' => Yii::$app->user->id])->joinWith(['address' => function($query){
            $query->orderBy(['level' =>SORT_DESC ]);
        },'userdetails'])->one();
        $this->layout = 'user';
      
        return $this->render('userprofile',['user' => $user]);
       
    }

    public function actionUserdetails()
    {
     
        $upload = new Upload();
        $upload->scenario = 'ticket';
        $path = Yii::$app->request->baseUrl.'/imageLocation';
        
       // return $this->render('upload', ['detail' => $detail]);

        $detail = Userdetails::find()->where('User_id = :id'  , [':id' => Yii::$app->user->identity->id])->one();
        
             $picpath = $detail['User_PicPath']; 
            if($detail->load(Yii::$app->request->post()))
            {
                    $post = Yii::$app->request->post();
                    $model = UserDetails::find()->where('User_Username = :uname',[':uname' => Yii::$app->user->identity->username])->one(); 
                    
			        //$model->action = 1;
			        //$model->action_before=1;
    		        $upload->imageFile =  UploadedFile::getInstance($detail, 'User_PicPath');
                          if (!is_null($upload->imageFile))
                {
    		        $upload->imageFile->name = time().'.'.$upload->imageFile->extension;
    		       // $post['User_PicPath'] = 
                    $location = 'imageLocation/';
    		        $upload->upload($location);
			        
                    $model->User_PicPath =$path.'/'.$upload->imageFile->name;
                   
    		        //$model->save();
			      
                }
                else{
                    $detail->User_PicPath = $picpath;
                   
                     
                }
               
				     $isValid = $detail->validate()  && $model->validate();
                    if($isValid){
                        $detail->save();
                      
                        $model->save();
        
                    Yii::$app->session->setFlash('success', "Update completed");
                    return $this->redirect(['user/user-profile']);
                
                    }
                    else{
                        Yii::$app->session->setFlash('warning', "Fail Update");
                    }

			}
	
		//$this->view->title = 'Update Profile';
		$this->layout = 'user';
		return $this->render("userdetails",['detail' => $detail]);
    }

	public function actionUserbalance()
 	{
 		
		$model = Accountbalance::find()->where('User_Username = :User_Username' ,[':User_Username' => Yii::$app->user->identity->username])->one();
		//var_dump($balance);exit;
 		$accounttopup = Accounttopup::find()->where('User_Username= :User_Username' ,[':User_Username' => Yii::$app->user->identity->username])->one();
 		$memberpoint = Memberpoint::find()->where('uid = :uid',[':uid' => Yii::$app->user->identity->id])->one();
		//var_dump($memberpoint);exit;
		if (empty($model)) 
 		{
 			$model = new Accountbalance();
 		}

 		$this->layout = 'user';
		return $this->render('userbalance', ['model' => $model,'accounttopup' => $accounttopup,'memberpoint' =>$memberpoint]);
 	}
    
    public function actionChangepassword()
 	{      
	    $model = new Changepassword;
	 
	     if($model->load(Yii::$app->request->post()) ){
	 		if ($model->check()) {
	 			 Yii::$app->session->setFlash('success', 'Successfully changed password');
	 			    return $this->redirect(['user/changepassword']);
	 		}
	     
	         
	         else {
	         	Yii::$app->session->setFlash('warning', 'changed password failed');
	        }
	      
	    }
	    $this->view->title = 'Change Password';
	 	$this->layout = 'user';
	    return $this->render('changepassword',['model'=>$model]); 
 	}

    public function actionPrimaryAddress($id)
    {
        Useraddress::updateAll(['level' => 0],'uid = :uid',[':uid' => Yii::$app->user->identity->id]);
        $model = Useraddress::findOne($id);
        $model->level = 1;
        if($model->save())
        {
            Yii::$app->session->setFlash('success', 'Address changed to Primary');
        }
        else
        {
            Yii::$app->session->setFlash('success', 'Address changed Fail');
        }
        return $this->redirect(Yii::$app->request->referrer);
    }

    public function actionNewaddress()
    {
        $model = new Useraddress;
        if($model->load(Yii::$app->request->post()))
        {
            $count = Useraddress::find()->where('uid = :uid',[':uid' => Yii::$app->user->identity->id])->count();
            if($count < 3)
            {
                $model->uid = Yii::$app->user->identity->id;
                
                if($model->save())
                {
                    if($model->level == 1)
                    {
                         Useraddress::updateAll(['level' => 0],'uid = :uid AND id != :id',[':uid' => Yii::$app->user->identity->id,':id'=> $model->id]);
                    }
                     Yii::$app->session->setFlash('success', 'Address changed to Primary');
                }
                else
                {
                    Yii::$app->session->setFlash('danger', ' Address Add Fail');
                }
            }
            else
            {
                Yii::$app->session->setFlash('danger', ' Reach Max Limit 3');
            }
            return $this->redirect(Yii::$app->request->referrer);
        }
        $this->layout = 'user';
        $this->view->title = 'New Address';
        return $this->renderAjax('address',['model'=>$model]);
    }

    public function actionDeleteAddress($id)
    {
        $model= Useraddress::findOne($id);
        if(!empty($model))
        {
            $model->delete();
            Yii::$app->session->setFlash('success', 'Delete Successfully');
        }
        else
        {
            Yii::$app->session->setFlash('warning', 'Fail Delete ');
        }
        return $this->redirect(Yii::$app->request->referrer);
    }

}




