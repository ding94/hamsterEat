<?php

namespace frontend\modules\Restaurant\controllers;

use Yii;
use yii\web\Controller;
use yii\base\Model;
use common\models\Restaurant;
use common\models\Rmanager;
use common\models\Rmanagerlevel;
use common\models\RestaurantName;
use common\models\Order\Orders;
use common\models\Order\Orderitem;
use common\models\Order\Orderitemselection;
use common\models\problem\ProblemOrder;
use common\models\problem\ProblemStatus;
use common\models\Company\Company;
use common\models\food\Food;
use common\models\food\Foodselection;
use common\models\food\Foodselectiontype;
use common\models\food\Foodstatus;
use yii\helpers\ArrayHelper;
use yii\helpers\Json;
use yii\helpers\Url;
use yii\web\NotFoundHttpException;
use yii\filters\AccessControl;
use frontend\controllers\CommonController;
use frontend\controllers\PaymentController;
use frontend\modules\Restaurant\controllers\CancelController;

class RestaurantController extends CommonController
{
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'actions' => ['restaurant-service','food-service','providereason','active','deactive','pauserestaurant','resume-restaurant','cooking-detail','phonecooking','orderlist','food-on-off','selectionactive','record-restaurant-name'],
                        'allow' => true,
                        'roles' => ['restaurant manager'],
                    ]
                ],
            ],
        ];
    }

    public function actionRestaurantService()
    {
        $rmanager = CommonController::rmanagerApproval();
        $staffs = Rmanagerlevel::find()->where('User_Username=:u',[':u' => Yii::$app->user->identity->username])->all();
        if ($staffs) {
            foreach ($staffs as $k => $staff) {
                $restaurants[$k] = Restaurant::find()->where('Restaurant_ID=:r',[':r' => $staff['Restaurant_ID']])->one();
                $resname[$k] = CommonController::getRestaurantName($staff['Restaurant_ID']);

            }
            return $this->render('restaurantservice',['restaurants'=>$restaurants,'resname'=>$resname]);
        }
        elseif(empty($staffs))
        {
            if ($rmanager) {
                return $this->render('restaurantservice',['restaurants'=>"",'resname'=>'']);
            }
        }
        Yii::$app->session->setFlash('warning', Yii::t('m-restaurant',"You Are Not The Right Person In This Page!"));
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
            Yii::$app->session->setFlash('error', Yii::t('common',"You are not allow to perfrom this action!"));
                return $this->redirect(['/site/index']);
        }
    }

    public function actionPauserestaurant($id,$item)
    {
        CommonController::restaurantPermission($id);
        $restaurant = self::findModel($id);

        $restaurant['Restaurant_Status'] = 3;
        $valid = true;
        $foods = Food::find()->JoinWith(['foodStatus'])->where('Restaurant_ID=:id',[':id'=>$restaurant['Restaurant_ID']])->andWhere('Status >= 0')->all();

        foreach ($foods as $k => $food) {
            $valid = CancelController::CancelOrder($food['Food_ID']);
            $status = Foodstatus::find()->where('Food_ID=:id',[':id'=>$food['Food_ID']])->one();
            $status['Status'] = 0;
            
            if ($valid == false) {
                Yii::$app->session->setFlash('error', Yii::t('m-restaurant',"Operation Paused! Error Food ID")." = ".$food['Food_ID']);
                break;
            }
            $status->save();
        }
        if ($valid == true) {
            $restaurant->save();
            Yii::$app->session->setFlash('warning', Yii::t('m-restaurant',"Status changed! Please inform customer service."));
        }
        return $this->redirect(['/Food/default/menu','rid'=>$id]);
    }

    public function actionResumeRestaurant($id)
    {
        CommonController::restaurantPermission($id);
        $model = self::findModel($id);
        $model->Restaurant_Status = 2;
        if($model->validate())
        {
            $model->save();
            Yii::$app->session->setFlash('success', Yii::t('m-restaurant',"Status change to operating.")."<br>Please Turn On Food For Display Food");
        }
        else
        {
            Yii::$app->session->setFlash('warning', Yii::t('m-restaurant',"Change status failed."));
        }

        return $this->redirect(['/Food/default/menu','rid'=>$id]);
    }

    /*
    * group all order in status in pending
    * base on company id
    */
    public function actionCookingDetail($rid)
    { 
        $linkData = CommonController::restaurantPermission($rid);
        $companyData = [];
        $singleData=[];

        $item = Orderitem::find()->distinct()->where("Restaurant_ID = :rid and OrderItem_Status = 2",[':rid'=>$rid])->joinWith(['food','address']);
        //$item->andWhere("Orders_Status != 1 and Orders_Status != 6");
        $allitem = $item->all();

        if(empty($allitem))
        {
            Yii::$app->session->setFlash('warning', Yii::t('m-restaurant',"Empty Order"));
            return $this->redirect(Yii::$app->request->referrer);
        }
        
        foreach ($allitem as $k => $single) 
        {
            if($single->address->type == 1 && $single->address->cid > 0)
            {
                $companyName = Company::findOne($single->address->cid)->name;
                $foodName = $single->food->originName;
                $empty = json_encode(['empty'=>['name'=>'N/A','nick'=>'N/A']]);

                $selectionName = empty(Json::decode($single->trim_selection)) ? $empty : $single->trim_selection;
               
                $companyData[$companyName][$foodName]['rowspan'] = 0;
                $companyData[$companyName][$foodName]['nickname'] = $single->food->Nickname;
                $companyData[$companyName][$foodName][$selectionName]['orderid'][$single->Order_ID]['remark'] = "";
            
                if(!array_key_exists('quantity',$companyData[$companyName][$single->food->originName][$selectionName]))
                {
                    $companyData[$companyName][$foodName][$selectionName]['quantity'] = 0;
                }
               
                $companyData[$companyName][$foodName][$selectionName]['quantity'] += $single->OrderItem_Quantity;
                
                $companyData[$companyName][$foodName][$selectionName]['orderid'][$single->Order_ID]['single_quantity'] = $single->OrderItem_Quantity;

                if(!empty($single->OrderItem_Remark))
                {
                    $companyData[$companyName][$foodName][$selectionName]['orderid'][$single->Order_ID]['remark'] = $single->OrderItem_Remark;
                }
            }
            else
            {
                $did = $single->Delivery_ID;
                $singleData[$did]['foodname'] = $single->food->Name;
                $singleData[$did]['nickname'] = $single->food->Nickname;
                $singleData[$did]['quantity'] = $single->OrderItem_Quantity;
                $singleData[$did]['selection'] = Json::decode($single->trim_selection);
                $singleData[$did]['orderid'] = $single->Order_ID;
                if(!empty($single->OrderItem_Remark))
                {
                     $singleData[$did]['remark'] = $single->OrderItem_Remark;
                }
            }
        }

        foreach ($companyData as $k => $company) 
        {
            foreach($company as $i => $food)
            {
                $arrayKey = array_keys($food);
                foreach($arrayKey as $a=> $key)
                {
                    $companyData[$k][$i][$a] = $companyData[$k][$i][$key];
                    if($companyData[$k][$i][$a] != 0)
                    {
                        $companyData[$k][$i][0] += count($companyData[$k][$i][$key]['orderid']);
                        $companyData[$k][$i][$a]['selection'] = Json::decode($key);
                    }
                   
                    unset($companyData[$k][$i][$key]);
                }
            }
        }
    
        return $this->render('cooking',['singleData'=>$singleData,'companyData'=>$companyData]);
    }

    public function actionOrderlist($rid,$status = "",$mode = 1)
    {
        $restaurant = Restaurant::find()->where('Restaurant_ID = :rid', [':rid'=>$rid])->one();
        // food data with condition of today's orders and other table
        $allData = [];
        $data= Orderitem::find()->where('Restaurant_ID = :id',['id'=>$rid])->joinWith(['item_status'=>function($query){
            $query->where(['>=','Change_PendingDateTime',strtotime(date('Y-m-d'))]);},
            'food','order_selection'=>function($query){ $query->orderby('Selection_ID ASC');} ])->all();
        $resname = CommonController::getRestaurantName($rid);
        foreach($data as $item)
        {
            $allData[$item['food']['Food_ID']][] = $item;
        }
        
        return $this->render('orderlistpdf', ['rid'=>$rid,'resname'=>$resname,'allData'=>$allData,'restaurant'=>$restaurant]);
    }

    public function actionPhonecooking()
    {
        $staffs = Rmanagerlevel::find()->where('User_Username=:u',[':u' => Yii::$app->user->identity->username])->all();
        if ($staffs) {
            $count = 0;
            foreach ($staffs as $k => $staff) {
                $restaurants[$k] = Restaurant::find()->where('Restaurant_ID=:r',[':r' => $staff['Restaurant_ID']])->asArray()->one();
                $restaurants[$k]['Restaurant_Orders'] = Orderitem::find()->where('Restaurant_ID=:id AND OrderItem_Status=:s',[':id'=>$staff['Restaurant_ID'],':s'=>2])->joinwith(['food'])->count();
                $resname[$k] = CommonController::getRestaurantName($staff['Restaurant_ID']);
                $count += $restaurants[$k]['Restaurant_Orders'];
            }
            return $this->renderAjax('phonecooking',['restaurants'=>$restaurants,'resname'=>$resname,'count'=>$count]);
        }
        elseif(empty($staffs))
        {
            Yii::$app->session->setFlash('warning', Yii::t('m-restaurant',"You are not any staff or owner of restaurant."));
            return $this->redirect(Yii::$app->request->referrer);
        }
        Yii::$app->session->setFlash('warning', Yii::t('m-restaurant',"You Are Not The Right Person In This Page!"));
        return $this->redirect(Yii::$app->request->referrer);
    }

    public function actionDeactive($id,$item)
    {  
        switch ($item) {
            case 1:
                $model = self::findModel($id);
                $model->Restaurant_Status = 3;
                $sucess = $model->save();
                break;
            case 2:
                $model = Foodstatus::find()->where('Food_ID=:id',[':id'=>$id])->one();
                $model->Status = 0;
             
                $sucess = $model->save();
                return $sucess;
                break;
            case 3:
                $model = Foodselection::findOne($id);
                $model->Status = 0;
                $sucess = $model->save();
                return $sucess;
            break;
            default:
                # code...
                break;
        }
        
        if($sucess)
        {
                 
            Yii::$app->session->setFlash('warning', Yii::t('m-restaurant',"Status change to paused."));
        }
        else
        {
            Yii::$app->session->setFlash('error', Yii::t('m-restaurant',"Change status failed."));
        }
        return $this->redirect(Yii::$app->request->referrer);
    }


    public function findModel($id)
    {
        $model = Restaurant::find()->where('Restaurant_ID = :id',[':id' =>$id])->one();
        if ($model !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested restaurant does not exist.');
        }
    }

    public function actionRecordRestaurantName()
    {
        $restaurants = Restaurant::find()->all();
        foreach ($restaurants as $k => $restaurant) {
            $valid = RestaurantName::find()->where('rid=:rid',[':rid'=>$restaurant['Restaurant_ID']])->andWhere(['=','language','en'])->one();
            if (empty($valid)) {
                $res = new RestaurantName();
                $res['rid'] = $restaurant['Restaurant_ID'];
                $res['language'] = 'en';
                $res['translation'] = $restaurant['Restaurant_Name'];
                $res->save(false);
                var_dump($res['rid']);
            }
            else{
                var_dump('fail');
            }
        }
    }
}