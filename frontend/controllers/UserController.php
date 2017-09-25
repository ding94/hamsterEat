<?php
namespace frontend\controllers;
use Yii;
use yii\web\Controller;
use yii\helpers\ArrayHelper;
use common\models\User\Userdetails;
use common\models\User\Useraddress;;
use common\models\User;
use common\models\Upload;
use yii\web\UploadedFile;

class UserController extends Controller
{
    public function actionUserProfile()
    {
        $user = User::find()->where('id = :id' ,[':id' => Yii::$app->user->id])->joinWith(['useraddress','userdetails'])->one();
        $this->layout = 'user';
        
        return $this->render('userprofile',['user' => $user]);
       
    }

    public function actionUserdetails()
    {
     
        $upload = new Upload();
        $upload->scenario = 'ticket';
        $path = Yii::$app->request->baseUrl.'/imageLocation/';
       //var_dump($path);exit;
     

       // return $this->render('upload', ['detail' => $detail]);

        $detail = UserDetails::find()->where('User_id = :id'  , [':id' => Yii::$app->user->identity->id])->one();
        
        $address = Useraddress::find()->where('User_id = :id'  , [':id' => Yii::$app->user->identity->id])->one();  
            if($detail->load(Yii::$app->request->post()) && $address->load(Yii::$app->request->post()))
            {
                    $post = Yii::$app->request->post();
    		        $model = Userdetails::find()->where('User_Username = :uname',[':uname' => Yii::$app->user->identity->username])->one(); 
                    
			
			        //$model->action = 1;
			        //$model->action_before=1;
    		        $upload->imageFile =  UploadedFile::getInstance($detail, 'User_PicPath');
    		        $upload->imageFile->name = time().'.'.$upload->imageFile->extension;
    		       // $post['User_PicPath'] = 
                    $location = 'imageLocation/';
    		        $upload->upload($location);
			        
    		        $model->load($post);
                
                   $model->User_PicPath =$path.'/'.$upload->imageFile->name;
                     
    		        //$model->save();
			        Yii::$app->session->setFlash('success', 'Upload Successful');

				     $isValid = $detail->validate() && $address->validate();
                    if($isValid){
                        $detail->save();
                        $address->save();

        
                    Yii::$app->session->setFlash('success', "Update completed");
                    return $this->redirect(['user/user-profile']);
                
                    }
                    else{
                        Yii::$app->session->setFlash('warning', "Fail Update");
                    }

			}
	
		//$this->view->title = 'Update Profile';
		$this->layout = 'user';
		return $this->render("userdetails",['detail' => $detail,'address'=>$address]);
    }
    public function actionUseraddress()
    {
        
        //$this->view->title = 'Update Profile';
        //$this->layout = 'user';
        return $this->render("useraddress",['address' => $address]);
    }

    
    
}




