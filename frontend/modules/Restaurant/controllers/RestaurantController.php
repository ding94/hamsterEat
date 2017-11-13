<?php

namespace frontend\modules\Restaurant\controllers;

use Yii;
use yii\web\Controller;
use backend\models\RestaurantSearch;
use common\models\Restaurant;
use common\models\Rmanager;
use common\models\Orders;
use common\models\Orderitem;
use common\models\Account\Accountbalance;
use common\models\problem\ProblemOrder;
use common\models\problem\ProblemStatus;
use common\models\food\Food;
use common\models\food\Foodstatus;
use yii\helpers\ArrayHelper;
use yii\web\NotFoundHttpException;
use yii\filters\AccessControl;
use frontend\controllers\CommonController;

class RestaurantController extends CommonController
{
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'actions' => ['restaurant-service','food-service','providereason','active','deactive','pauserestaurant'],
                        'allow' => true,
                        'roles' => ['?','@'],
                    ]
                ],
            ],
        ];
    }

	public function actionIndex()
    {
    	$searchModel = new RestaurantSearch();
    	$dataProvider = $searchModel->search(Yii::$app->request->queryParams);

    	return $this->render('index',['model' => $dataProvider , 'searchModel' => $searchModel]);
    }

    public function actionRestaurantService()
    {
        $rightperson = Rmanager::find()->where('uid=:id',[':id' => Yii::$app->user->identity->id])->one();
        if ($rightperson) {
            $restaurant = Restaurant::find()->where('Restaurant_Manager=:r',[':r' => Yii::$app->user->identity->username])->all();
           
            return $this->render('restaurantservice',['restaurant'=>$restaurant]);
        }

        Yii::$app->session->setFlash('warning', "You Are Not The Right Person In This Page!");
        return $this->redirect(Yii::$app->request->referrer); 
    }

    public function actionFoodService($id)
    {
        $restaurant = Restaurant::find()->where('Restaurant_ID=:id',[':id'=>$id])->one();
        if ($restaurant['Restaurant_Manager'] == Yii::$app->user->identity->username) {
            $foods = Food::find()->where('Restaurant_ID=:rid',[':rid'=>$id])->all();

            $this->layout = "/user";
            return $this->render('foodservice',['foods'=>$foods,'rid'=>$id]);
        }
        else
        {
            Yii::$app->session->setFlash('error', "You are not allow to perfrom this action!");
                return $this->redirect(['/site/index']);
        }
    }

    public function actionPauserestaurant($id,$item)
    {
        $restaurant = self::findModel($id);
        $restaurant['Restaurant_Status'] = 'Closed';

        $foods = Food::find()->JoinWith(['foodStatus'])->where('Restaurant_ID=:id',[':id'=>$restaurant['Restaurant_ID']])->andWhere('Status >= 0')->all();

        foreach ($foods as $k => $food) {
            $valid = self::CancelOrder($food['Food_ID']);
            $status = Foodstatus::find()->where('Food_ID=:id',[':id'=>$food['Food_ID']])->one();
            $status['Status'] = 0;
            $status->save();
            if ($valid == false) {
                Yii::$app->session->setFlash('error', "Operation Paused! Error Food ID = ".$food['Food_ID']);
            }
        }
        if ($valid == true) {
            $restaurant->save();
            Yii::$app->session->setFlash('warning', "Status changed! Please inform customer service.");
        }
        return $this->redirect(['/Restaurant/default/restaurant-details','rid'=>$id]);
    }

    public function actionProvidereason($id,$rid,$item)
    {
        $reason = new ProblemOrder;
        $list = ArrayHelper::map(ProblemStatus::find()->all(),'id','description');

        if (Yii::$app->request->post()) {
            $valid = self::CancelOrder($id);
            if ($valid == true) {
                self::actionDeactive($id,$item);
                Yii::$app->session->setFlash('warning', "Status changed! Please inform customer service.");
                return $this->redirect(['/food/menu','rid'=>$rid,'page'=>'menu']);
            }
        }
            
        return $this->renderAjax('reason',['reason'=>$reason,'list'=>$list]);

    }

    protected function CancelOrder($id)
    {
        $orderitem = Orderitem::find()->where('Food_ID=:id AND OrderItem_Status=:s',[':id'=>$id, ':s'=>'Pending'])->all();
        if (!empty($orderitem)) 
        {
            foreach ($orderitem as $k => $value) 
            {
                $order = Orders::find()->where('Delivery_ID=:id',[':id'=>$value['Delivery_ID']])->one();
                if ($order['Orders_DateTimeMade'] > strtotime(date('Y-m-d'))) 
                {
                    $reason = new ProblemOrder; // set new value to db, away from covering value
                    $reason['reason'] = 3;
                    $reason->load(Yii::$app->request->post());
                    $reason['Order_ID'] = $value['Order_ID'];
                    $reason['Delivery_ID'] = $value['Delivery_ID'];
                    $reason['status'] = 1;
                    $reason['datetime'] = time();
                    $order['Orders_Status'] = 'Canceled';
                    $value['OrderItem_Status'] = 'Canceled';

                    //check did user use balance to pay
                    if ($order['Orders_PaymentMethod'] == 'Account Balance') {
                        $reason['refund'] = $order['Orders_TotalPrice'];
                        $acc = Accountbalance::find()->where('User_Username=:us',[':us'=>$order['User_Username']])->one();
                        $acc['User_Balance'] += $order['Orders_TotalPrice'];
                        $acc['AB_minus'] -= $order['Orders_TotalPrice'];
                        if ($acc->validate()) {
                                $acc->save();
                                $order['Orders_Status'] = 'Canceled and Refunded';
                                $value['OrderItem_Status'] = 'Canceled and Refunded';
                        }
                    }
                    if ($reason->validate() && $value->validate() && $order->validate()) {
                        $reason->save();
                        $value->save();
                        $order->save();
                    }
                }
            }
            //use this formular for most accurate data protect
            //if (count($orderitem) == ($k+1) ) {}
        }
        return true;
    }

    public function actionActive($id,$item)
    {
        switch ($item) {
            case 1:
                $model = self::findModel($id);
                $model->Restaurant_Status = "Operating";
                if($model->validate())
                {
                    $model->save();
                    Yii::$app->session->setFlash('success', "Status change to operating.");
                }
                else
                {
                    Yii::$app->session->setFlash('warning', "Change status failed.");
                }
                break;

            case 2:
                $model = Foodstatus::find()->where('Food_ID=:id',[':id'=>$id])->one();
                $food = Food::find()->where('Food_ID=:id',[':id'=>$model['Food_ID']])->one();
                $restaurant = self::findModel($food['Restaurant_ID']);
                if ($restaurant['Restaurant_Status'] == 'Closed') {
                    Yii::$app->session->setFlash('error', "Restaurant was not opening.");
                    return $this->redirect(Yii::$app->request->referrer);
                }
                $model->Status = 1;
                if($model->validate())
                {
                    $model->save();
                    Yii::$app->session->setFlash('success', "Status change to operating.");
                }
                else
                {
                    Yii::$app->session->setFlash('warning', "Change status failed.");
                }
                break;
            
            default:
                # code...
                break;
        }
        

        return $this->redirect(Yii::$app->request->referrer);
    }

    public function actionDeactive($id,$item)
    {
        switch ($item) {
            case 1:
                $model = self::findModel($id);
                $model->Restaurant_Status = "Closed";
                if($model->validate())
                {
                    $model->save();
                    Yii::$app->session->setFlash('warning', "Status change to closed.");
                }
                else
                {
                    Yii::$app->session->setFlash('error', "Change status failed.");
                }
                break;

            case 2:
                $model = Foodstatus::find()->where('Food_ID=:id',[':id'=>$id])->one();
                $model->Status = 0;
                if($model->validate())
                {
                    $model->save();
                    Yii::$app->session->setFlash('warning', "Status change to paused.");
                }
                else
                {
                    Yii::$app->session->setFlash('error', "Change status failed.");
                }
                break;
            
            default:
                # code...
                break;
        }
        
        return $this->redirect(Yii::$app->request->referrer);
    }

    protected function findModel($id)
    {
        $model = Restaurant::find()->where('Restaurant_ID = :id',[':id' =>$id])->one();
        if ($model !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested restaurant does not exist.');
        }
    }
}