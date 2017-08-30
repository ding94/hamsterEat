<?php
namespace frontend\controllers;
use Yii;
use yii\web\Controller;
use yii\helpers\ArrayHelper;
use common\models\User\Userdetails;
use common\models\User\Useraddress;;
use common\models\User;
use common\models\Upload;
use common\models\Ticket;
use common\models\Ticketcategorytypes;
use yii\web\UploadedFile;

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
      
        $upload = new Upload();
        $path = Yii::$app->request->baseUrl.'/imageLocation/';
       //var_dump($path);exit;
     

       // return $this->render('upload', ['detail' => $detail]);

        $detail = UserDetails::find()->where('User_Username = :uname'  , [':uname' => Yii::$app->user->identity->username])->one();
        
        $address = Useraddress::find()->where('User_Username = :uname'  , [':uname' => Yii::$app->user->identity->username])->one();  
            if($detail->load(Yii::$app->request->post()) && $address->load(Yii::$app->request->post()))
            {
                    $post = Yii::$app->request->post();
    		        $model = Userdetails::find()->where('User_Username = :uname',[':uname' => Yii::$app->user->identity->username])->one();
                    
			
			        //$model->action = 1;
			        //$model->action_before=1;
    		        $upload->imageFile =  UploadedFile::getInstance($detail, 'User_PicPath');
    		        $upload->imageFile->name = time().'.'.$upload->imageFile->extension;
    		       // $post['User_PicPath'] = 
    		        $upload->upload();
			        
    		        $model->load($post);
                
                   $model->User_PicPath =$path.'/'.$upload->imageFile->name;
                     
    		        $model->save();
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
		//$this->layout = 'user';
		return $this->render("userdetails",['detail' => $detail,'address'=>$address]);
    }
    public function actionUseraddress()
    {
        
        //$this->view->title = 'Update Profile';
        //$this->layout = 'user';
        return $this->render("useraddress",['address' => $address]);
    }

    public function actionSubmitTicket()
    {
        $model = new Ticket;
        $type = Ticketcategorytypes::find()->all();
        $data = ArrayHelper::map($type,'Category_Name','Category_Name');
        $path = Yii::$app->params['submitticket'];
        $upload = new Upload;
        if (Yii::$app->request->post()) {
            
            $post = Yii::$app->request->post();

            $upload->imageFile =  UploadedFile::getInstance($upload, 'imageFile');
            $upload->imageFile->name = time().'.'.$upload->imageFile->extension;
            $post['Ticket']['Ticket_PicPath'] = $path.'/'.$upload->imageFile->name;
            $location = 'imageLocation/submitticket/';
            $upload->upload($location);

            $model->User_Username = Yii::$app->user->identity->username;
            $model->Ticket_DateTime = time();
            $model->Ticket_Status = 'Submitted';
            $model->load($post);
            $model->save(false);
            Yii::$app->session->setFlash('success', 'Upload Successful');
            return $this->redirect(['/user/submit-ticket']);
        }




        return $this->render("ticket",['model' => $model, 'data' => $data,'upload'=>$upload]);
    }
    
}




