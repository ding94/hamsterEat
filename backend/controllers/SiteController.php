<?php
namespace backend\controllers;

use Yii;
use yii\web\Controller;
use yii\filters\VerbFilter;
use yii\helpers\ArrayHelper;
use yii\filters\AccessControl;
use backend\models\AdminLogin;
use backend\controllers\CommonController;
use common\models\Profit\RestaurantProfit;
use common\models\Profit\RestaurantItemProfit;
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
                        'actions' => ['logout', 'index'],
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
        }
        
        $queryDelivery = RestaurantProfit::find()->Where(['between','created_at',strtotime($first),strtotime($last)]);
        $queryOrder = RestaurantItemProfit::find()->where(['between','created_at',strtotime($first),strtotime($last)]);

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

        $data['final']['totalDelivery'] = number_format($queryDelivery->sum('total-earlyDiscount-voucherDiscount+deliveryCharge'));
        $data['final']['totalDeliveryCharge'] = number_format($queryDelivery->sum('deliveryCharge'));
        $data['final']['earlyDiscount'] = number_format($queryDelivery->sum('earlyDiscount'));
        $data['final']['voucherDiscount'] = number_format($queryDelivery->sum('voucherDiscount'));
        $data['final']['orderFinalPrice'] = number_format($queryOrder->sum('quantity * finalPrice'));
        $data['final']['orderOrignalPrice'] = number_format($queryOrder->sum('quantity * originalPrice'));
       
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
