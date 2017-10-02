<?php
namespace frontend\controllers;

use Yii;
use yii\base\InvalidParamException;
use yii\web\BadRequestHttpException;
use yii\web\Controller;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use common\models\LoginForm;
use frontend\models\PasswordResetRequestForm;
use frontend\models\ResetPasswordForm;
use frontend\models\SignupForm;
use frontend\models\ContactForm;
use frontend\models\Rmanager;
use frontend\models\Deliveryman;
use common\models\User;
use common\models\user\Userdetails;
use common\models\user\Useraddress;
use common\models\Accountbalance;
use common\models\Area;
use yii\helpers\ArrayHelper;
use yii\web\Session;

/**
 * Site controller
 */
class SiteController extends Controller
{
    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'only' => ['logout', 'signup','index'],
                'rules' => [
                    [
                        'actions' => ['signup','index'],
                        'allow' => true,

                        'roles' => ['@','?'],

                    ],
                    [
                        'actions' => ['index'],
                        'allow' => true,
                        'roles' => ['?','@'],
                    ],
                    [
                        'actions' => ['signup'],
                        'allow' => true,
                        'roles' => ['?'],
                    ],
                    [
                        'actions' => ['logout'],
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
    public function actionIndex()
    {
        $postcode = new Area();
        $list =array();
        $postcode->detectArea = 0;
        if(Yii::$app->request->isPost)
        {
            $postcode->detectArea = 1;
            $area = Yii::$app->request->post('Area');
            $postcode->Area_Postcode = $area['Area_Postcode'];
            $dataArea = Area::find()->where(['like','Area_Postcode' , $area['Area_Postcode']])->all();
            $list = ArrayHelper::map($dataArea,'Area_Area' ,'Area_Area');
            
            if(empty($list)) {
                $postcode->detectArea = 0;
                Yii::$app->session->setFlash('error', 'There is no available area under that postcode.');
            }
           
        }   
        
        return $this->render('index',['postcode'=>$postcode ,'list'=>$list]);

      
    }

    public function actionSearchRestaurantByArea()
    {
        $area = Yii::$app->request->post('Area');
        $groupArea = Area::find()->where('Area_Postcode = :area_postcode and Area_Area = :area_area',[':area_postcode'=> $area['Area_Postcode'] , ':area_area'=>$area['Area_Area']])->one();
        $session = new Session;
        $session->open();
        $pcode = Area::find()->where('Area_Area = :area', [':area'=>$area['Area_Area']])->one();
        $pcode = $pcode['Area_Postcode'];
        $session['postcode'] = $pcode;
        $session['area'] = $area['Area_Area'];
        //var_dump($session['postcode']);exit;
        $groupArea = $groupArea['Area_Group'];
        //var_dump($groupArea);exit;
        return $this->redirect(['Restaurant/default/index','groupArea'=>$groupArea]);

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
        } else {
            return $this->render('login', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Logs out the current user.
     *
     * @return mixed
     */
    public function actionLogout()
    {
        Yii::$app->user->logout();

        return $this->goHome();
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
                Yii::$app->session->setFlash('success', 'Thank you for contacting us. We will respond to you as soon as possible.');
            } else {
                Yii::$app->session->setFlash('error', 'There was an error sending your message.');
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
        
    
        if ($model->load(Yii::$app->request->post())) {
            if ($user = $model->signup()) {
                $email = \Yii::$app->mailer->compose(['html' => 'confirmLink-html','text' => 'confirmLink-text'],//html file, word file in email
                    ['id' => $user->id, 'auth_key' => $user->auth_key])//pass value)
                ->setTo($user->email)
                ->setFrom([\Yii::$app->params['supportEmail'] => \Yii::$app->name])
                ->setSubject('Signup Confirmation')
                ->send();
                if($email){
                    if (Yii::$app->getUser()->login($user)) {

                        Yii::$app->getSession()->setFlash('success','Verification email sent! Kindly check email and validate your account.');
                        return $this->render('validation');
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
                    return $this->render('validation');
                } else{
                    Yii::$app->getSession()->setFlash('warning','Failed, contact Admin!');
                }
                return $this->render('validation');
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
            $userdetails->User_id= Yii::$app->user->identity->id;
            $userdetails->User_Username= Yii::$app->user->identity->username;
            $useraddress = new Useraddress();
            $useraddress->User_id= Yii::$app->user->identity->id;
            $userbalance = new Accountbalance;
            $userbalance->User_Username = Yii::$app->user->identity->username;
            $userbalance->User_Balance = 0;     
            
            $isValid = $user->validate() && $userdetails->validate() && $useraddress->validate();
            if($isValid)
            {
                $user->save();
                $userdetails->save();
                $useraddress->save();
                $userbalance->save();
                
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
                Yii::$app->session->setFlash('success', 'Check your email for further instructions.');

                return $this->goHome();
            } else {
                Yii::$app->session->setFlash('error', 'Sorry, we are unable to reset password for the provided email address.');
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
            Yii::$app->session->setFlash('success', 'New password saved.');

            return $this->goHome();
        }

        return $this->render('resetPassword', [
            'model' => $model,
        ]);
    }
    
    public function actionRmanager()
    {
        $model = new SignupForm();
         $model1 = new Rmanager();
        
        
         if ($model->load(Yii::$app->request->post()) &&  $model1->load(Yii::$app->request->post())) {
         
            $model->type = 1;
            
            if ($user = $model->signup()) {
                $email = \Yii::$app->mailer->compose(['html' => 'confirmLink-html','text' => 'confirmLink-text'],//html file, word file in email
                    ['id' => $user->id, 'auth_key' => $user->auth_key])//pass value)
                ->setTo($user->email)
                ->setFrom([\Yii::$app->params['supportEmail'] => \Yii::$app->name])
                ->setSubject('Signup Confirmation')
                ->send();
                if($email){
                    if (Yii::$app->getUser()->login($user)) {
                        $model1->User_Username=$user->username;
                        $model1->save();
                        Yii::$app->getSession()->setFlash('success','Verification email sent! Kindly check email and validate your account.');
                        return $this->render('validation');
                    }
                }
                else{
                Yii::$app->getSession()->setFlash('warning','Failed, contact Admin!');
                }
                return $this->goHome();
                }
            }
        
          return $this->render('rmanager',['model1'=>$model1,'model'=>$model]);
    }
    
    public function actionDeliveryman(){
         $model = new SignupForm();
         $model1 = new Deliveryman();

         if ($model->load(Yii::$app->request->post()) &&  $model1->load(Yii::$app->request->post())) {
         
            $model->type = 2;
            
            if ($user = $model->signup()) {
                $email = \Yii::$app->mailer->compose(['html' => 'confirmLink-html','text' => 'confirmLink-text'],//html file, word file in email
                    ['id' => $user->id, 'auth_key' => $user->auth_key])//pass value)
                ->setTo($user->email)
                ->setFrom([\Yii::$app->params['supportEmail'] => \Yii::$app->name])
                ->setSubject('Signup Confirmation')
                ->send();
                if($email){
                    if (Yii::$app->getUser()->login($user)) {
                        $model1->User_id=$user->id;
                        $model1->save();
                        Yii::$app->getSession()->setFlash('success','Verification email sent! Kindly check email and validate your account.');
                        return $this->render('validation');
                    }
                }
                else{
                Yii::$app->getSession()->setFlash('warning','Failed, contact Admin!');
                }
                return $this->goHome();
                }
            }
        
          return $this->render('deliveryman',['model1'=>$model1,'model'=>$model]);
    }
	public function actionRuser()
    {
        return $this->render('ruser');
    }

}
