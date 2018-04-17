<?php
namespace frontend\controllers;

use Yii;
use yii\web\Cookie;
use yii\base\InvalidParamException;
use yii\web\BadRequestHttpException;
use yii\web\Controller;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use common\models\LoginForm;
use frontend\models\{PasswordResetRequestForm,ResetPasswordForm,SignupForm,ContactForm,CompanysignupForm};
use common\models\Rmanager;
use common\models\Deliveryman;
use common\models\User;
use common\models\user\Userdetails;
use common\models\user\Useraddress;
use common\models\Account\Accountbalance;
use common\models\Area;
use common\models\Account\Memberpoint;
use common\models\SelfObject;
use common\models\Referral;
use yii\helpers\ArrayHelper;
use yii\web\Session;
use frontend\controllers\{CommonController,PhoneController};
use common\models\Banner;
use common\models\Expansion;
use common\models\Feedback;
use common\models\Feedbackcategory;
use common\models\Upload;
use common\models\AuthFb;
use common\models\Company\{Company,CompanyEmployees};
use yii\web\UploadedFile;
use yii\helpers\Json;
/**
 * Site controller
 */
class SiteController extends CommonController
{
    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'only' => ['logout', 'signup','index','resendconfirmlink','referral','resendconfirmlink-referral','request-password-reset','reset-password','validation'],
                'rules' => [
                    [
                        'actions' => ['signup','index','resendconfirmlink','confirm','logout','request-password-reset','reset-password','closebanner'],

                        'allow' => true,

                        'roles' => ['@','?'],

                    ],
                    [
                        'actions' => ['index'],
                        'allow' => true,
                        'roles' => ['?','@'],
                    ],
                    [
                        'actions' => ['signup','referral','resendconfirmlink-referral',],
                        'allow' => true,
                        'roles' => ['?'],
                    ],
                    [
                        'actions' => ['validation'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'logout' => ['post'],
                ],
            ],
        ];
    }

    /**
     * @inheritdoc
     */
    public function actions()
    {
        return [
            'auth' => [
              'class' => 'yii\authclient\AuthAction',
              'successCallback' => [$this, 'oAuthSuccess'],
            ],
            'link' => [
                'class' => 'yii\authclient\AuthAction',
                'successCallback' => [$this, 'linkSuccess'],
            ],
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
            'captcha' => [
                'class' => 'yii\captcha\CaptchaAction',
                'fixedVerifyCode' => YII_ENV_TEST ? 'testme' : null,
            ],
        ];
    }

    /**
     * Displays homepage.
     *
     * @return mixed
     */

//--This function captures the user's area group from the entered postcodes and area
    public function actionIndex()
    {
        $postcodeArray = ArrayHelper::map(Area::find()->all(),'Area_ID','Area_Area');

        $list =array();
        $banner = Banner::find()->where(['<=','startTime',date("Y-m-d H:i:s")])->andWhere(['>=','endTime',date("Y-m-d H:i:s")])->all();
      
        if(Yii::$app->request->isPost)
        {
            $post = Yii::$app->request->post();

            // if (is_null($post['area']) || empty($post['area'])) {
            //     Yii::$app->session->setFlash('error', 'Please select area to continue.');
            //     return $this->refresh();
            // }
            $group = 1;
          
            $session = new Session;

            $session->open();
            $session['area'] = 'Medini';
            $session['group'] = $group;
            $session->close();
            return $this->redirect(['Restaurant/default/index']);          
        }   

        return $this->render('index',['list'=>$list,'postcodeArray'=>$postcodeArray,'banner'=>$banner]);

    }

    public function actionChangelanguage($lang)
    {
        $lang = CommonController::getLanguage($lang);
        return $this->redirect(Yii::$app->request->referrer);
    }

    /**
     * Logs in a user.
     *
     * @return mixed
     */
    public function actionLogin()
    {
        if (!Yii::$app->user->isGuest) {
            return $this->goHome();
        }

        $model = new LoginForm();
        if ($model->load(Yii::$app->request->post()) && $model->login()) {
            return $this->goBack();
        } 
        else
        {
            return $this->render('login', [
                'model' => $model,
            ]);
        }
    }

    public function actionLoginPopup()
    {
        if (!Yii::$app->user->isGuest) {
            return $this->goHome();
        }

        $model = new LoginForm();
        if ($model->load(Yii::$app->request->post())) {
            if( $model->login())
            {
                Yii::$app->session->setFlash('success', Yii::t('site','Login Success.'));
            }
            else
            {
                Yii::$app->session->setFlash('danger', Yii::t('site','Either username or password is incorrect.'));
            }
          
            return $this->goBack();
        }  

        return $this->renderAjax('loginpopup', [
                'model' => $model,
            ]);
    }

    /**
     * Logs out the current user.
     *
     * @return mixed
     */
    public function actionLogout()
    {
        Yii::$app->user->logout();
        $cookies = Yii::$app->response->cookies;
        $cookies->remove('halal');
        $cookies->remove('banner');
        Yii::$app->session->setFlash('success', Yii::t('site','Logout Success.'));
        return $this->goHome();
    }

    public function actionClosebanner()
    {
        $cookies = Yii::$app->request->cookies;
        if (empty($cookies->getValue('banner'))) {
            $cookie =  new Cookie([
                'name' => 'banner',
                'value' => 0,
                'expire' => time() + 86400,
            ]);
            \Yii::$app->getResponse()->getCookies()->add($cookie);
        }

        //$cookies = Yii::$app->response->cookies;
        //$cookies->remove('banner');
    }

    /**
     * Displays contact page.
     *
     * @return mixed
     */
    public function actionContact()
    {
        $model = new ContactForm();
        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            if ($model->sendEmail(Yii::$app->params['adminEmail'])) {
                Yii::$app->session->setFlash('success', Yii::t('site','Thank you for contacting us. We will respond to you as soon as possible.'));
            } else {
                Yii::$app->session->setFlash('error', Yii::t('site','There was an error sending your message.'));
            }

            return $this->refresh();
        } else {
            return $this->render('contact', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Displays about page.
     *
     * @return mixed
     */
    public function actionAbout()
    {
        return $this->render('about');
    }

    /**
     * Signs user up.
     *
     * @return mixed
     */
    public function actionSignup()
    {
        $model = new SignupForm();
        $employee = new CompanyEmployees();
        $company = Arrayhelper::map(Company::find()->andWhere(['!=','status',0])->all(),'id','name');
        if ($model->load(Yii::$app->request->post())) {
            $employee->load(Yii::$app->request->post());
            if ($user = $model->signup()) {
                $employee['uid'] = $user['id'];
                $employee['status'] = 0;
                $employee['created_at'] = time();
                $employee['updated_at'] = time();
                $employee->save(false);
                $email = \Yii::$app->mailer->compose(['html' => 'confirmLink-html','text' => 'confirmLink-text'],//html file, word file in email
                    ['id' => $user->id, 'auth_key' => $user->auth_key])//pass value)
                ->setTo($user->email)
                ->setFrom([\Yii::$app->params['supportEmail'] => \Yii::$app->name])
                ->setSubject('Signup Confirmation')
                ->send();
                if($email){
                    if (Yii::$app->getUser()->login($user)) {

                        Yii::$app->getSession()->setFlash('success','Verification email sent! Kindly check email and validate your account.');
                        return $this->redirect(['/site/validation']);
                    }
                }
                else{
                Yii::$app->getSession()->setFlash('warning','Failed, contact Admin!');
                }
                return $this->goHome();
                }
            }
        

        return $this->render('signup', ['model' => $model,'employee'=>$employee,'company'=>$company]);
    }

    public function actionCompanysignup()
    {   
        $model = new CompanysignupForm();   
      
        $area = Arrayhelper::map(area::find()->all(),'Area_ID','Area_Area');

        if ($model->load(Yii::$app->request->post())) {
            $valid = PhoneController::ValidatePhone($model->validate_code,$model->contact_number);
            if($valid)
            {   
                if ($user = $model->companysignup()) {

                    $email = \Yii::$app->mailer->compose(['html' => 'confirmLink-html','text' => 'confirmLink-text'],//html file, word file in email
                        ['id' => $user->id, 'auth_key' => $user->auth_key])//pass value)
                    ->setTo($user->email)
                    ->setFrom([\Yii::$app->params['supportEmail'] => \Yii::$app->name])
                    ->setSubject('Signup Confirmation')
                    ->send();

                    if($email){
                        if (Yii::$app->getUser()->login($user)) {

                            Yii::$app->getSession()->setFlash('success','Verification email sent! Kindly check email and validate your account.');
                            return $this->redirect(['/site/validation']);
                        }
                    }
                }
                Yii::$app->getSession()->setFlash('warning','Something Went Wrong');
            }
        }
        
        return $this->render('companysignup', ['model' => $model,'area'=> $area]);
    }
    
    public function actionResendconfirmlink()
    {
        $email = \Yii::$app->mailer->compose(['html' => 'confirmLink-html'],//html file, word file in email
                    ['id' => Yii::$app->user->identity->id, 'auth_key' => Yii::$app->user->identity->auth_key])//pass value)
                ->setTo(Yii::$app->user->identity->email)
                ->setFrom([\Yii::$app->params['supportEmail'] => \Yii::$app->name])
                ->setSubject('Signup Confirmation')
                ->send();
                if($email){
                    Yii::$app->getSession()->setFlash('success','Verification email sent! Kindly check email and validate your account.');
                   
                } else{
                    Yii::$app->getSession()->setFlash('warning','Failed, contact Admin!');
                }
                return $this->redirect(['/site/validation']);
    }

    public function actionConfirm()
    {   
        $id = Yii::$app->request->get('id');
        
        $key = Yii::$app->request->get('auth_key');
        $user = User::find()->where([
        'id'=>$id,
        'auth_key'=>$key,
        'status'=>1,
        ])->one();
        
        if(!empty($user)){
            $user->status=10;
            
            $userdetails = new Userdetails();
            $userdetails->User_id= $id;
            $userdetails->User_Username= $user['username'];
            $userbalance = new Accountbalance;
            $userbalance->User_Username = $user['username'];
            $userbalance->User_Balance = 0; 

            $point = self::generateMemberPoint($id);

            $isValid = $user->validate() && $userdetails->validate() && $point->validate();
            if($isValid)
            {
                $user->save();
                $userdetails->save();
                $userbalance->save();
                $point->save();
                
                Yii::$app->getSession()->setFlash('success','Success!');
                Yii::$app->getUser()->login($user);
                return $this->redirect(['/user/user-profile']);
            }
            else
            {
                Yii::$app->getSession()->setFlash('warning','Failed!');
                return $this->goHome();
            }
            
        } else{
            Yii::$app->getSession()->setFlash('warning','Failed!');
            return $this->goHome();
        }
    }

    /**
     * Requests password reset.
     *
     * @return mixed
     */
    public function actionRequestPasswordReset()
    {
        $model = new PasswordResetRequestForm();
        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            if ($model->sendEmail()) {
                Yii::$app->session->setFlash('success', Yii::t('site','Check your email for further instructions.'));

                return $this->goHome();
            } else {
                Yii::$app->session->setFlash('error', Yii::t('site','Sorry, we are unable to reset password for the provided email address.'));
            }
        }

        return $this->render('requestPasswordResetToken', [
            'model' => $model,
        ]);
    }

    /**
     * Resets password.
     *
     * @param string $token
     * @return mixed
     * @throws BadRequestHttpException
     */
    public function actionResetPassword($token)
    {
        try {
            $model = new ResetPasswordForm($token);
        } catch (InvalidParamException $e) {
            throw new BadRequestHttpException($e->getMessage());
        }

        if ($model->load(Yii::$app->request->post()) && $model->validate() && $model->resetPassword()) {
            Yii::$app->session->setFlash('success', Yii::t('site','New password saved.'));

            return $this->goHome();
        }

        return $this->render('resetPassword', [
            'model' => $model,
        ]);
    }
    
    // public function actionRmanager()
    // {
    //     $model = new SignupForm();
    //     $model1 = new Rmanager();
        
        
    //      if ($model->load(Yii::$app->request->post()) &&  $model1->load(Yii::$app->request->post())) {
         
    //         $model->type = 1;
            
    //         if ($user = $model->signup()) {
    //             $email = \Yii::$app->mailer->compose(['html' => 'confirmLink-html','text' => 'confirmLink-text'],//html file, word file in email
    //                 ['id' => $user->id, 'auth_key' => $user->auth_key])//pass value)
    //             ->setTo($user->email)
    //             ->setFrom([\Yii::$app->params['supportEmail'] => \Yii::$app->name])
    //             ->setSubject('Signup Confirmation')
    //             ->send();
    //             if($email){
    //                 if (Yii::$app->getUser()->login($user)) {
    //                     $model1->uid=$user->id;
    //                     $model1->User_Username=$user->username;
    //                     $model1->Rmanager_Approval = 1;
    //                     $model1->Rmanager_DateTimeApplied = time();
    //                     $model1->Rmanager_DateTimeApproved = time();
    //                     $model1->save();
    //                     Yii::$app->getSession()->setFlash('success','Verification email sent! Kindly check email and validate your account.');
    //                     return $this->redirect('validation');
    //                 }
    //             }
    //             else{
    //             Yii::$app->getSession()->setFlash('warning','Failed, contact Admin!');
    //             }
    //             return $this->goHome();
    //             }
    //         }
        
    //       return $this->render('rmanager',['model1'=>$model1,'model'=>$model]);
    // }
    
    // public function actionDeliveryman(){
    //     $model = new SignupForm();
    //     $model1 = new Deliveryman();
    //     $model1->scenario = "new";
    //     $data = Area::find()->all();
    //     $areaGroup = ArrayHelper::map($data,'Area_ID','Area_Group');
    //     $area = ArrayHelper::map($data,'Area_ID','Area_Area');
     
    //     if ($model->load(Yii::$app->request->post()) &&  $model1->load(Yii::$app->request->post())) 
    //     {
    //         $model1->DeliveryMan_DateTimeApplied = time();
    //         $model->type = 2;

    //         if(array_key_exists($model1->aid, $areaGroup))
    //         {
    //             $model1->DeliveryMan_AreaGroup = $areaGroup[$model1->aid];
    //         }
           
    //         if ($model1->validate() && $user = $model->signup()) 
    //         {
    //             $email = \Yii::$app->mailer->compose(['html' => 'confirmLink-html','text' => 'confirmLink-text'],//html file, word file in email
    //                 ['id' => $user->id, 'auth_key' => $user->auth_key])//pass value)
    //             ->setTo($user->email)
    //             ->setFrom([\Yii::$app->params['supportEmail'] => \Yii::$app->name])
    //             ->setSubject('Signup Confirmation')
    //             ->send();
    //             if($email)
    //             {
    //                 if (Yii::$app->getUser()->login($user)) 
    //                 {
    //                     $model1->User_id=$user->id;
    //                     $model1->save();
    //                     Yii::$app->getSession()->setFlash('success','Verification email sent! Kindly check email and validate your account.');
    //                     return $this->redirect(['validation']);
    //                 }
    //             }
    //         }
    //         Yii::$app->getSession()->setFlash('warning','Failed, contact Admin!');
    //         return $this->goHome();  
    //     }

    //     return $this->render('deliveryman',['model1'=>$model1,'model'=>$model,'area'=>$area]);
    // }
    
	public function actionRuser()
    {
        return $this->render('ruser');
    }

    public static function generateMemberPoint($id)
    {
        $data = new Memberpoint;
        $data->uid = $id;
        $data->point = 0;
        $data->positive = 0;
        $data->negative = 0;
        return $data;
    }

    /* Function for dependent dropdown in frontend index page. */
    public function actionGetArea()
    {
    if (isset($_POST['depdrop_parents'])) {
        $parents = $_POST['depdrop_parents'];
        if ($parents != null) {
            $cat_id = $parents[0];
            $out = self::getAreaList($cat_id); 
            echo json_encode(['output'=>$out, 'selected'=>'']);
            return;
        }
    }
    echo json_encode(['output'=>'', 'selected'=>'']);
    }

    public static function getAreaList($postcode)
    {
        $area = Area::find()->where(['like','Area_Postcode' , $postcode])->select(['Area_ID', 'Area_Area'])->all();
        $areaArray = [];
        foreach ($area as $area) {
            $object = new SelfObject();
            $object->id = $area['Area_Area'];
            $object->name = $area['Area_Area'];

            $areaArray[] = $object;
        }
        return $areaArray;
    }

    public function actionReferral($name)
    {
        $model = new SignupForm();
        $referral = new Referral();

        if ($model->load(Yii::$app->request->post())) {
            $model->status = 2;
            $referral->new_user = $model->username;
            $referral->referral = $name;
            if ($user = $model->signup()) {
                $referral->save();
                $email = \Yii::$app->mailer->compose(['html' => 'confirmLinkReferral-html','text' => 'confirmLinkReferral-text'],//html file, word file in email
                    ['id' => $user->id, 'auth_key' => $user->auth_key, 'referral_name' => $name])//pass value)
                ->setTo($user->email)
                ->setFrom([\Yii::$app->params['supportEmail'] => \Yii::$app->name])
                ->setSubject('Signup Confirmation')
                ->send();
                if($email){
                    if (Yii::$app->getUser()->login($user)) {

                        Yii::$app->getSession()->setFlash('success','Verification email sent! Kindly check email and validate your account.');
                        return $this->redirect('validation');
                    }
                }
                else{
                Yii::$app->getSession()->setFlash('warning','Failed, contact Admin!');
                }
                return $this->goHome();
                }
            }

        return $this->render('signup', ['model' => $model]);
    }

    public function actionResendconfirmlinkReferral()
    {
        $referral = Referral::find()->where('new_user = :user',[':user'=>Yii::$app->user->identity->username])->one();

        $email = \Yii::$app->mailer->compose(['html' => 'confirmLinkReferral-html'],//html file, word file in email
                    ['id' => Yii::$app->user->identity->id, 'auth_key' => Yii::$app->user->identity->auth_key, 'referral_name' => $referral->referral])//pass value)
                ->setTo(Yii::$app->user->identity->email)
                ->setFrom([\Yii::$app->params['supportEmail'] => \Yii::$app->name])
                ->setSubject('Signup Confirmation')
                ->send();
                if($email){
                    Yii::$app->getSession()->setFlash('success','Verification email sent! Kindly check email and validate your account.');
                   
                } else{
                    Yii::$app->getSession()->setFlash('warning','Failed, contact Admin!');
                }
                return $this->redirect('validation');
    }

    public function actionConfirmReferral()
    {
        $id = Yii::$app->request->get('id');
        
        $key = Yii::$app->request->get('auth_key');
        $referralName = Yii::$app->request->get('referral_name');
        $user = User::find()->where([
        'id'=>$id,
        'auth_key'=>$key,
        'status'=>2,
        ])->one();
        
        if(!empty($user)){
            $user->status=10;
    
            $userdetails = new Userdetails();
            $userdetails->User_id= Yii::$app->user->identity->id;
            $userdetails->User_Username= Yii::$app->user->identity->username;
            $userbalance = new Accountbalance;
            $userbalance->User_Username = Yii::$app->user->identity->username;
            $userbalance->User_Balance = 0; 

            $point = self::generateMemberPoint($id);

            $isValid = $user->validate() && $userdetails->validate()  && $point->validate();
            if($isValid)
            {
                $user->save();
                $userdetails->save();
                $userbalance->save();
                $point->save();

                //voucher creation part for 2 users

                $discount = 5; // discount how much
                $discountitem = 3; // discount item, in db: discount_item 1 - 4
                $discounttype = 2; // disocunt type, 1 for %, 2 for amount
                $voucher = VouchersController::VoucherCreate($discount,$discountitem,$discounttype);// get voucher for new user
                if ($voucher->validate()) {
                    $voucher->save();

                    $uservoucher = VouchersController::UserInviteReward(Yii::$app->user->identity->id,$voucher->id,$voucher->code,$voucher->endDate);// set uservoucher

                    if ($uservoucher->validate()) {
                        $uservoucher->save();
                    }

                    $voucher= VouchersController::VoucherCreate($discount,$discountitem,$discounttype); // recreate a new voucher;
                    if ($voucher->validate()) {
                        $voucher->save();

                        $refid = User::find()->where('username =:u',[':u'=>$referralName])->one(); // find ref user id, by using username
                        if (!empty($refid)) {
                            $uservoucher = VouchersController::UserInviteReward($refid->id,$voucher->id,$voucher->code,$voucher->endDate); // set uservoucher
                            if ($uservoucher->validate()) {
                                $uservoucher->save();
                            }
                        }
                        
                    }

                }
                //voucher creation end
                
                Yii::$app->getSession()->setFlash('success','Success!');
                Yii::$app->getUser()->login($user);
                return $this->redirect(['user/user-profile']);
            }
            else
            {
                Yii::$app->getSession()->setFlash('warning','Failed!');
                return $this->goHome();
            }
            
        } else{
            Yii::$app->getSession()->setFlash('warning','Failed!');
            return $this->goHome();
        }
    }

    public function actionFaq()
    {
        return $this->render('faq');
    }

//--This function gets the user to submit his requested area for expansion
    public function actionRequestArea()
    {
        $expansion = new Expansion();
        $postcode = new Area();
        $postcodeArray = ArrayHelper::map(Area::find()->all(),'Area_Postcode','Area_Postcode');
        $list =array();
        $banner = Banner::find()->where(['<=','startTime',date("Y-m-d H:i:s")])->andWhere(['>=','endTime',date("Y-m-d H:i:s")])->all();
        if ($expansion->load(Yii::$app->request->post()))
        {
            $expansion->Expansion_DateTime = time();
            if (!Yii::$app->user->isGuest)
            {
                $expansion->User_Username = Yii::$app->user->identity->username;
            }

            $expansion->save(false);

            Yii::$app->getSession()->setFlash('success','Thank you for submitting your area expansion request. We hope to receive your order soon.');
            return $this->redirect(['index', 'postcode'=>$postcode ,'list'=>$list,'postcodeArray'=>$postcodeArray,'banner'=>$banner]);
        }

        return $this->render('expansion', ['expansion'=>$expansion]);
    }

//--This function gets the user's feedback / bug report for a specific page
    public function actionFeedBack($link)
    {
        $feedback = new Feedback();
        $categoryarray = ArrayHelper::map(Feedbackcategory::find()->all(),'ID','Category');
        foreach ($categoryarray as $c => $value) {
            $categoryarray[$c] = Yii::t('report',$value);
        }
        
        $upload = new Upload();
        if ($feedback->load(Yii::$app->request->post()))
        {
            $upload->imageFile = UploadedFile::getInstance($feedback, 'Feedback_PicPath');
            $upload->imageFile->name = time().'.'.$upload->imageFile->extension;
            $upload->upload(Yii::$app->params['feedback']);
        
            $feedback->Feedback_PicPath = $upload->imageFile->name;
            $postcode = new Area();
            $postcodeArray = ArrayHelper::map(Area::find()->all(),'Area_Postcode','Area_Postcode');
            $list =array();
            $banner = Banner::find()->where(['<=','startTime',date("Y-m-d H:i:s")])->andWhere(['>=','endTime',date("Y-m-d H:i:s")])->all();
            
            if (!Yii::$app->user->isGuest)
            {
                $feedback->User_Username = Yii::$app->user->identity->username;
            }

            $feedback->Feedback_DateTime = time();
            $feedback->Feedback_Link = $link;

            $feedback->save(false);
            
            Yii::$app->getSession()->setFlash('success','Thank you for submitting your feedback. We will improve to serve you better.');
            return $this->redirect(Yii::$app->request->referrer);
        }

        return $this->renderAjax('feedback', ['feedback'=>$feedback, 'categoryarray'=>$categoryarray]);
    }

    public function actionValidation()
    {
        return $this->render('validation');
    }

    public function actionSelectiontype()
    {
        $post= Yii::$app->request->post();
        
        $cookie =  new Cookie([
            'name' => 'halal',
            'value' => $post['type'],
            'expire' => time() + 86400 * 365,
        ]);
        \Yii::$app->getResponse()->getCookies()->add($cookie);
        return true;
    }

    public function oAuthSuccess($client)
    {
        switch ($client->getId()) {
            case 'facebook':
                $auth = self::fbAuth($client);
                if($auth == true){
                    return $this->goHome();
                } else {
                    Yii::$app->getSession()->setFlash('error', 'Login Failed');
                    return $this->goHome();
                }
                break;

            case 'google':
                $auth = self::googleAuth($client);
                if($auth == true){
                    return $this->goHome();
                } else {
                    Yii::$app->getSession()->setFlash('error', 'Login Failed');
                    return $this->goHome();
                }
                break;
            
            default:
                # code...
                break;
        }
    }

    private static function fbAuth($client)
    {
        $attributes = $client->getUserAttributes();

        /** @var Auth $auth */
        $auth = AuthFb::find()->where([
            'source' => $client->getId(),
            'source_id' => $attributes['id'],
        ])->one();
        if (Yii::$app->user->isGuest) {
            if ($auth) { // login
                $user = User::find()->where(['id' => $auth->user_id])->one();
                Yii::$app->user->login($user);
                Yii::$app->getSession()->setFlash('success', 'Login Successful');
                return true;
            } else { // signup
                if (User::find()->where(['email' => $attributes['email']])->exists()) {
                    Yii::$app->getSession()->setFlash('error', [
                        Yii::t('app', "User with the same email as in {client} account already exists but isn't linked to it. Login using email first to link it.", ['client' => $client->getTitle()]),
                    ]);
                } else {
                    $user = new User([
                        'username' => $attributes['name'],
                        'email' => $attributes['email'],
                    ]);
                    $user->generateAuthKey();
                    $transaction = $user->getDb()->beginTransaction();
                    if ($user->save()) {
                        $auth = new AuthFb([
                            'user_id' => $user->id,
                            'source' => $client->getId(),
                            'source_id' => (string)$attributes['id'],
                        ]);
                        $balance = new Accountbalance;
                        $balance->User_Username = $user['username'];
                        $balance->User_Balance = 0; 
                        if ($auth->save() && $balance->save()) {
                            $transaction->commit();
                            Yii::$app->user->login($user);
                            return true;
                        } else {
                            print_r($auth->getErrors());
                        }
                    } else {
                        print_r($user->getErrors());
                    }
                }
            }
        } else { // user already logged in
            if (!$auth) { // add auth provider
                $auth = new AuthFb([
                    'user_id' => Yii::$app->user->id,
                    'source' => $client->getId(),
                    'source_id' => $attributes['id'],
                ]);
                $auth->save();
            }
        }
    }

    private static function googleAuth($client)
    {
        $attributes = $client->getUserAttributes();
        /** @var Auth $auth */
        $auth = AuthFb::find()->where([
            'source' => $client->getId(),
            'source_id' => $attributes['id'],
        ])->one();
        if (Yii::$app->user->isGuest) {
            if ($auth) { // login
                $user = User::find()->where(['id' => $auth->user_id])->one();
                Yii::$app->user->login($user);
                Yii::$app->getSession()->setFlash('success', 'Login Successful');
                return true;
            } else { // signup
                if (User::find()->where(['email' => $attributes['emails'][0]['value']])->exists()) {
                    Yii::$app->getSession()->setFlash('error', [
                        Yii::t('app', "User with the same email as in {client} account already exists but isn't linked to it. Login using email first to link it.", ['client' => $client->getTitle()]),
                    ]);
                } else {
                    $user = new User([
                        'username' => $attributes['displayName'],
                        'email' => $attributes['emails'][0]['value'],
                    ]);
                    $user->generateAuthKey();
                    $transaction = $user->getDb()->beginTransaction();
                    if ($user->save()) {
                        $auth = new AuthFb([
                            'user_id' => $user->id,
                            'source' => $client->getId(),
                            'source_id' => (string)$attributes['id'],
                        ]);
                        $balance = new Accountbalance;
                        $balance->User_Username = $user['username'];
                        $balance->User_Balance = 0; 
                        if ($auth->save() && $balance->save()) {
                            $transaction->commit();
                            Yii::$app->user->login($user);
                            return true;
                        } else {
                            print_r($auth->getErrors());
                        }
                    } else {
                        print_r($user->getErrors());
                    }
                }
            }
        } else { // user already logged in
            if (!$auth) { // add auth provider
                $auth = new AuthFb([
                    'user_id' => Yii::$app->user->id,
                    'source' => $client->getId(),
                    'source_id' => $attributes['id'],
                ]);
                $auth->save();
            }
        }
    }
    
    public function linkSuccess($client)
    {
        $attributes = $client->getUserAttributes();
        
        $auth = AuthFb::find()->where([
            'source' => $client->getId(),
            'source_id' => $attributes['id'],
        ])->one();
        
        if($auth == NULL){
            $auth = new AuthFb([
                'user_id' => Yii::$app->user->identity->id,
                'source' => $client->getId(),
                'source_id' => (string)$attributes['id'],
            ]);
            $auth->save();
            if($auth->save()){
                Yii::$app->getSession()->setFlash('success', 'Link Successful');
            } else {
                Yii::$app->getSession()->setFlash('danger', 'Link Unsuccessful');
            }
        } else {
            Yii::$app->getSession()->setFlash('danger', 'Your account has already been link to your facebook.');
        }
    }
}