<?php
namespace backend\controllers;

use Yii;
use yii\web\Controller;
use yii\filters\VerbFilter;
use yii\helpers\ArrayHelper;
use yii\filters\AccessControl;
use backend\models\AdminLogin;
use backend\models\ControllersLink;
use backend\models\auth\AdminAuthAssignment;
use backend\models\auth\AdminAuthItemChild;
use backend\controllers\CommonController;
use common\models\Profit\RestaurantProfit;
use common\models\Profit\RestaurantItemProfit;
use common\models\User;
use backend\models\AdminChangepassword;
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
                'rules' => [
                    [
                        'actions' => ['login', 'error'],
                        'allow' => true,
                    ],
                    [
                        'actions' => ['logout', 'index','change-password','controllers','resendconfirmlink'],
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
        ];
    }

    /**
     * Displays homepage.
     *
     * @return string
     */
    public function actionIndex($first = 0,$last = 0,$type = 0)
    {
        if($first == 0 && $last == 0)
        {
            $first = date("Y-m-d", strtotime("first day of this month"));
            $last = date("Y-m-d", strtotime("+1 days")); 
        };
       
        if(strtotime($first)>strtotime($last)){
          
            Yii::$app->session->setFlash('error', 'Starting date cannot bigger than End date');
              return $this->redirect(['index']);  
        };  
    
        $queryDelivery = RestaurantProfit::find()->Where(['between','created_at',strtotime($first. ' 00:00:00'),strtotime($last.' 23:59:59')]);
        $queryOrder = RestaurantItemProfit::find()->where(['between','created_at',strtotime($first.' 00:00:00'),strtotime($last.' 23:59:59')]);
      
        $days= CommonController::getMonth($first,$last,1);
        $delivery = $queryDelivery->select(['did','created_at'])->asArray()->all();

        $order = $queryOrder->select(['oid','created_at'])->asArray()->all();
       
        $arrayDelivery = ArrayHelper::map($delivery,'did','created_at');
        $arrayOrder = ArrayHelper::map($order,'oid','created_at');

        $countDelivery =$this->countTotal($arrayDelivery,$first,$last);
        $countOrder =$this->countTotal($arrayOrder,$first,$last);
    
        $data['date']= $days;
        $data['countOrder'] = CommonController::convertToArray($countOrder);
        $data['countDelivery'] = CommonController::convertToArray($countDelivery);

        $data['final']['totalDelivery'] = number_format($queryDelivery->sum('total-earlyDiscount-voucherDiscount+deliveryCharge'),2);
        $data['final']['totalDeliveryCharge'] = number_format($queryDelivery->sum('deliveryCharge'),2);
        $data['final']['earlyDiscount'] = number_format($queryDelivery->sum('earlyDiscount'),2);
        $data['final']['voucherDiscount'] = number_format($queryDelivery->sum('voucherDiscount'),2);
        $data['final']['orderFinalPrice'] = number_format($queryDelivery->sum('total'),2);
        $data['final']['orderOrignalPrice'] = number_format($queryOrder->sum('quantity * originalPrice'),2);
        $arrayType = [0=>'bar','1'=>'horizontalBar','2'=>'line'];

       
        return $this->render('index',['data'=>$data,'first'=>$first,'last'=>$last,'arrayType'=>$arrayType,'type'=>$type]);
    }

    /**
     * Login action.
     *
     * @return string
     */
    public function actionLogin()
    {
        if (!Yii::$app->user->isGuest) {
            return $this->goHome();
        }

        $model = new AdminLogin();
        if ($model->load(Yii::$app->request->post()) && $model->login()) {
            return $this->goBack();
        } else {
            return $this->render('login', [
                'model' => $model,
            ]);
        }
    }

    public function actionResendconfirmlink($id)
    {
        $user = User::findOne($id);
        $email = \Yii::$app->mailer->compose(['html' => 'confirmLink-html'],//html file, word file in email
            ['id' => $user->id, 'auth_key' =>  $user->auth_key,'back'=>0])//pass value)
            ->setTo( $user->email)
            ->setFrom([\Yii::$app->params['supportEmail'] => \Yii::$app->name])
            ->setSubject('Signup Confirmation')
            ->send();
        if($email){
            Yii::$app->getSession()->setFlash('success','Verification email sent! Kindly check email and validate your account.');
                   
        } else{
            Yii::$app->getSession()->setFlash('warning','Failed, contact Admin!');
        }
        return $this->redirect(['/user/index']);
    }

    /**
     * Logout action.
     *
     * @return string
     */
    public function actionLogout()
    {
        Yii::$app->user->logout();

        return $this->goHome();
    }

    public function actionControllers()
    {
        $auth = AdminAuthAssignment::find()->where('user_id=:id',[':id'=>Yii::$app->user->identity->id])->one()->item_name;
        $available = AdminAuthItemChild::find()->where('parent=:p',[':p'=>$auth])->all();
        $controller = ControllersLink::find()->orderby('controller ASC')->all();
        $count = 0;
        foreach ($controller as $k => $value) {
            foreach ($available as $k2 => $value2) {
                if ($value['link']==$value2['child']) {
                    $link[$count] = $value;
                    $count+=1;
                }
            }
        }

        return $this->render('controllers',['link'=>$link]);
    }

    public function actionChangePassword()
    {
        $model = new AdminChangepassword;
        if($model->load(Yii::$app->request->post()) ){
            if ($model->check()) {
                 Yii::$app->session->setFlash('success', 'Successfully changed password');
                    return $this->redirect(['/site/change-password']);
            }
            
            else {
                Yii::$app->session->setFlash('warning', 'changed password failed');
            }
          
        }
        return $this->render('changepassword',['model'=>$model]);
    }

    protected static function countTotal($array,$first,$last)
    {
        $count = CommonController::getMonth($first,$last,2);
        foreach($array as $i => $value)
        {
            $date = date("Y-m-d", $value);
            //var_dump(strtotime(),$date);exit;
            //var_dump($arrayItem[]);exit;
            $count[$date] += 1;
        }

        return $count;
    }
}
