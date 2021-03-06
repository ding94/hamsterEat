<?php
namespace frontend\controllers;
use Yii;
use yii\data\Pagination;
use yii\filters\AccessControl;
use yii\helpers\Url;
use yii\helpers\Json;
use yii\helpers\ArrayHelper;
use yii\web\Controller;
use yii\web\UploadedFile;
use yii\web\Session;
use frontend\models\SignupForm;
use frontend\models\Accounttopup;
use frontend\controllers\CommonController;
use common\models\{User,Upload,Withdraw};
use common\models\Account\{Accountbalance,AccountbalanceHistory,Memberpoint};
use common\models\Company\CompanyEmployees;
use common\models\user\{Useraddress,Userdetails,Changepassword};

class UserController extends CommonController
{
    public function behaviors()
    {
         return [
             'access' => [
                 'class' => AccessControl::className(),
                 'rules' => [
                    [
                        'actions' => ['user-profile','userdetails','useraddress','userbalance','changepassword','primary-address','newaddress','delete-address','edit-address','change-name-contact','phone-detail','email-detail','email-confirm'],
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
        },'userdetails','balance'])->one();
        $employee = CompanyEmployees::find()->where('uid=:u',[':u'=>Yii::$app->user->id])->joinWith('company')->one();
        $this->layout = 'user';
      
        return $this->render('userprofile',['user' => $user,'employee'=>$employee]);
       
    }

    public function actionChangeNameContact()
    {
        $post = Yii::$app->request->post();
        $data =array();
        $data['value'] = 0;
        if(!empty($post))
        {
            $detail = Userdetails::findOne(Yii::$app->user->id);
            $name = explode(' ', $post['name'],2);
            $detail->User_ContactNo = $post['contactno'];
            $detail->User_FirstName = $name[0];
            if(!empty($name[1]))
            {
                $detail->User_LastName = $name[1];
            }
            if($detail->save())
            {
                $data['value'] = 1;
                $data['message'] = "Your Delivery Name And Contanct Number Sucess Save";
            }
        }

        return Json::encode($data);
    }

    public function actionUserdetails()
    {
        $upload = new Upload();
        $upload->scenario = 'profile';
        $path = Yii::$app->params['userprofilepic'];
        
        // return $this->render('upload', ['detail' => $detail]);
        $link = CommonController:: createUrlLink(1);
        $detail = Userdetails::find()->where('User_id = :id'  , [':id' => Yii::$app->user->identity->id])->one();
        
        //$picpath = $detail['User_PicPath']; 
        if($detail->load(Yii::$app->request->post()))
        {
            $post = Yii::$app->request->post();
            $model = UserDetails::find()->where('User_Username = :uname',[':uname' => Yii::$app->user->identity->username])->one(); 

    		$upload->imageFile =  UploadedFile::getInstance($upload, 'imageFile');
           
            if (!empty($upload->imageFile))
            {
                $upload->imageFile->name = Yii::$app->user->identity->username.'.'.$upload->    imageFile->extension;
                if(!empty($detail->User_PicPath))
                {
                   $upload->upload($path,$path.$detail->User_PicPath);
                }
                else
                {
                  $upload->upload($path);  
                }
                
                $detail->User_PicPath =$upload->imageFile->name;
            }
           
			$isValid = $detail->validate();
            if($isValid){
                $detail->save();
                Yii::$app->session->setFlash('success', Yii::t('common',"Update completed"));
                return $this->redirect(['user/user-profile']);
                
            }
            else{
                Yii::$app->session->setFlash('warning', Yii::t('common',"Update Failed"));
            }
		}
	
		//$this->view->title = 'Update Profile';
		$this->layout = 'user';
		return $this->render("userdetails",['detail' => $detail,'link' => $link,'upload'=>$upload]);
    }

    public function actionPhoneDetail()
    {
        $detail = Userdetails::findOne(Yii::$app->user->identity->id);
        
        $signup = new SignupForm();
        $link = CommonController::createUrlLink(1);
        if (Yii::$app->request->post()) {
            $signup->load(Yii::$app->request->post());
            $detail->load(Yii::$app->request->post());
            $phone = $detail['User_ContactNo'];
            $valid = PhoneController::ValidatePhone($signup->validate_code,$phone);

            if ($valid == true) {
                if ($detail->validate()) {
                    $detail->save();
                    Yii::$app->session->setFlash('success', Yii::t('common',"Success!"));
                    return $this->redirect(['/user/phone-detail']);
                }
                else{
                    Yii::$app->session->setFlash('warning', Yii::t('common',"Update Failed"));
                }
            }
        }
        $this->layout = 'user';
        return $this->render('phone-detail',['detail'=>$detail,'signup'=>$signup,'link'=>$link]);
    }

    public function actionEmailDetail()
    {
        $user = User::findOne(Yii::$app->user->identity->id);
        $link = CommonController::createUrlLink(1);
        $model = new User;
        $model->scenario = 'changeAdmin';

        if ($model->load(Yii::$app->request->post())) {
            $back = 1;
            $email = $model['email'];
            if ($email == $user['email']) {
                Yii::$app->getSession()->setFlash('warning',Yii::t('user','Email was same as current.'));
                return $this->redirect(['/user/email-detail']);
            }
            if (User::find()->where('email=:e',[':e'=>$email])->one()) {
                Yii::$app->getSession()->setFlash('warning',Yii::t('user','This email address has already been taken.'));
                return $this->redirect(['/user/email-detail']);
            }

            $mail = \Yii::$app->mailer->compose(['html' => 'confirmLink-html','text' => 'confirmLink-text'],//html file, word file in email
                    ['id' => $user->id, 'auth_key' => $user->auth_key,'back'=>$back])//pass value)
                    ->setTo($email)
                    ->setFrom([\Yii::$app->params['supportEmail'] => \Yii::$app->name])
                    ->setSubject('Email Confirmation')
                    ->send();

                $session = Yii::$app->session;
                $session['email'] = [
                    'time' => time(),
                    'lifetime' => 3600,
                    'key' => $user->auth_key,
                    'email' => $email,
                ];
            if($mail){
                    Yii::$app->getSession()->setFlash('success',Yii::t('user','Verification email sent! Kindly check email and validate your account.'));
                    return $this->redirect(['/user/email-detail']);
                }

            else{
                Yii::$app->getSession()->setFlash('warning','Failed, kindly contact our customer srevice if you need help.');
            }
        }
        $this->layout = 'user';
        return $this->render('email-detail',['user'=>$user,'model'=>$model,'link'=>$link]);
    }

    public function actionEmailConfirm($id,$auth_key)
    {
        if (!empty(Yii::$app->session->get('email'))) {
            $session = Yii::$app->session->get('email');
            $user = User::findOne(Yii::$app->user->identity->id);
            if ($id == $user['id'] && $auth_key == $user['auth_key']) {
                $user['email'] = $session['email'];
                $user->save();
                Yii::$app->getSession()->setFlash('success',Yii::t('cart','Success!'));
            }
            else{
                Yii::$app->getSession()->setFlash('warning',Yii::t('user','Verification failed! Kindly check resend email again.'));
            }
        }
        else{
            Yii::$app->getSession()->setFlash('warning',Yii::t('user','Verification failed! Kindly check resend email again.'));
        }
        return $this->redirect(['/user/email-detail']);
    }

	public function actionUserbalance()
 	{
        $searchModel = new AccountbalanceHistory();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams,5);
		$account = Accountbalance::find()->where('User_Username = :name',[':name' => Yii::$app->user->identity->username])->one();
//var_dump( $model);exit;       
	   $query = AccountbalanceHistory::find()->where('abid = :aid',[':aid' => $account->AB_ID]);
  
	   $count = $query->count();
        $historypagination = new Pagination(['totalCount' => $count,'pageSize'=>10]);
        $historypage = $query->offset($historypagination->offset)->limit($historypagination->limit)->orderBy(['created_at'=> SORT_DESC])->all();
        $link = CommonController::createUrlLink(2);
 		$this->layout = 'user';
        $this->view->title = Yii::t('common','User Balance History');
		return $this->render('userbalance', ['model'=>$dataProvider,'historypage' => $historypage ,'historypagination' => $historypagination, 'searchModel' => $searchModel,'link'=>$link]);
 	}
    
    public function actionChangepassword()
 	{      
	    $model = new Changepassword;
	    $link = CommonController::createUrlLink(1);

	    if($model->load(Yii::$app->request->post()) ){
	 		if ($model->check()) {
	 			 Yii::$app->session->setFlash('success', Yii::t('user','Successfully changed password'));
	 			    return $this->redirect(['user/changepassword']);
	 		}
	        
	        else {
	         	Yii::$app->session->setFlash('warning', Yii::t('user','changed password failed'));
	        }
	      
	    }
	    $this->view->title = 'Change Password';
	 	$this->layout = 'user';
	    return $this->render('changepassword',['model'=>$model,'link' => $link]); 
 	}

    /*
    * update alll adress level to 0
    * and set the id address to 1
    */
    public function actionPrimaryAddress($id)
    {
        Useraddress::updateAll(['level' => 0],'uid = :uid',[':uid' => Yii::$app->user->identity->id]);
        $model = Useraddress::findOne($id);
        $model->level = 1;
        if($model->save())
        {
            Yii::$app->session->setFlash('success', Yii::t('user','Address changed to Primary'));
        }
        else
        {
            Yii::$app->session->setFlash('error', Yii::t('user','Fail to change Primary Address'));
        }
        return $this->redirect(Yii::$app->request->referrer);
    }

    /*
    * create new address
    * total data from database more than 3 fail
    * if click mark as default update all other to level 0 
    * and update itselft to level 1
    */
    public function actionNewaddress()
    {
      
        $count = Useraddress::find()->where('uid = :uid',[':uid' => Yii::$app->user->identity->id])->count();
        if($count >= 3)
        {
             Yii::$app->session->setFlash('danger', Yii::t('cart','Reach Max Limit 3'));
              return $this->redirect(Yii::$app->request->referrer);
        }

        $model = new Useraddress;
        $user = Userdetails::find()->where('User_id=:id',[':id'=>Yii::$app->user->identity->id])->one();
        $model['recipient'] = $user['User_FirstName'].' '.$user['User_LastName'];
        $model['contactno'] = $user['User_ContactNo'];
        if($model->load(Yii::$app->request->post()))
        {
            
            $model->uid = Yii::$app->user->identity->id;
                
            if($model->save())
            {
                if($model->level == 1)
                {
                    Useraddress::updateAll(['level' => 0],'uid = :uid AND id != :id',[':uid' => Yii::$app->user->identity->id,':id'=> $model->id]);
                }
                     Yii::$app->session->setFlash('success', Yii::t('cart','Successfully create new address'));
            }
            else
            {
                ii::$app->session->setFlash('danger', Yii::t('user','Address Add Fail'));
            }
            return $this->redirect(Yii::$app->request->referrer);
        }
        $this->layout = 'user';
        $this->view->title = 'Add New Address';
        return $this->renderAjax('address',['model'=>$model]);
    }

    public function actionEditAddress($id)
    {
        $model = Useraddress::findOne($id);
        if($model->load(Yii::$app->request->post()))
        {
            if($model->uid == Yii::$app->user->identity->id)
            {
                if($model->save())
                {
                    if($model->level == 1)
                    {
                        Useraddress::updateAll(['level' => 0],'uid = :uid AND id != :id',[':uid' => Yii::$app->user->identity->id,':id'=> $model->id]);
                    }
                    Yii::$app->session->setFlash('success', Yii::t('user','Successfully update address'));
                }
                else
                {
                    ii::$app->session->setFlash('danger', Yii::t('user','Address Update Fail'));
                }
            }
            else
            {
                Yii::$app->session->setFlash('danger', Yii::t('common','You are not the right person'));
            }
            return $this->redirect(Yii::$app->request->referrer);
        }
        $this->view->title = 'Edit Address';
        return $this->renderAjax('address',['model'=> $model]);
    }

    public function actionDeleteAddress($id)
    {
        $model= Useraddress::findOne($id);
        if(!empty($model))
        {
            $model->delete();
            Yii::$app->session->setFlash('success', Yii::t('common','Delete Successfully'));
        }
        else
        {
            Yii::$app->session->setFlash('warning', Yii::t('common','Delete Fail'));
        }
        return $this->redirect(Yii::$app->request->referrer);
    }
}