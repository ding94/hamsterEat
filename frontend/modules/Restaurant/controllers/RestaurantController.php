<?php

namespace frontend\modules\Restaurant\controllers;

use Yii;
use yii\web\Controller;
use yii\base\Model;
use common\models\Restaurant;
use common\models\Rmanager;
use common\models\Rmanagerlevel;
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
use frontend\modules\Restaurant\controllers\FoodselectionController;
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
                        'actions' => ['restaurant-service','food-service','providereason','active','deactive','pauserestaurant','resume-restaurant','cooking-detail','phonecooking','orderlist','food-on-off','selectionactive'],
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
            }
            return $this->render('restaurantservice',['restaurants'=>$restaurants]);
        }
        elseif(empty($staffs))
        {
            if ($rmanager) {
                return $this->render('restaurantservice',['restaurants'=>""]);
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

        $restaurant['Restaurant_Status'] = 'Closed';
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
        return $this->redirect(['/food/menu','rid'=>$id,'page'=>'menu']);
    }

    public function actionProvidereason($id,$rid,$item)
    {
        CommonController::restaurantPermission($rid);

        $reason = new ProblemOrder;
        $list = ArrayHelper::map(ProblemStatus::find()->all(),'id','description');

        if (Yii::$app->request->post()) {

            if($item == 3)
            {
                $true = FoodselectionController::enableOff($id);
                $valid = $true;
                if($true)
                {
                    $valid = self::CancelSelection($id);
                }
                
            }
            else
            {

                $valid = CancelController::CancelOrder($id);
            }
            
            if ($valid == true) {
                self::actionDeactive($id,$item);
                Yii::$app->session->setFlash('warning', Yii::t('m-restaurant',"Status changed! Please inform customer service."));
                return $this->redirect(Yii::$app->request->referrer); 
            }
            else
            {
                 return $this->redirect(Yii::$app->request->referrer); 
            }
        }
            
        return $this->renderAjax('reason',['reason'=>$reason,'list'=>$list]);

    }

    public function actionFoodOnOff($id,$rid)
    {
        CommonController::restaurantPermission($rid);
        $selectiondata = [];
        $model = Foodstatus::find()->where('foodstatus.Food_ID = :id and foodstatus.Status != -1 ',[':id'=>$id])->joinWith(['selection'])->one();
      
        if(empty($model))
        {
            Yii::$app->session->setFlash('warning', Yii::t('m-restaurant',"Food Already Deleted"));
            return $this->redirect(['/food/menu','rid'=>$rid,'page'=>'menu']); 
        }
        foreach($model->selection as $selection)
        {
            if($selection->Status != -1)
            {
                $food = Foodselectiontype::findOne($selection->Type_ID);
                $selectiondata[$food->originName][] = $selection;
            }
        }
        
        return $this->render("onoff",['model'=>$model,'rid'=>$rid,'selectiondata'=>$selectiondata]);
    }

    protected static function CancelSelection($id)
    {
        $post = Yii::$app->request->post();
        if(empty($post['ProblemOrder']))
        {
            return false;
        }

        $itemselection = Orderitemselection::find()->where('Selection_ID = :id and OrderItem_Status = 2',[':id'=>$id])->joinWith(['item'])->all();
       
        if(empty($itemselection))
        {
            return true;
        }
      
        foreach($itemselection as $selection)
        {
            $did = $selection->item->Delivery_ID;
            //$did = $itemselection[5]->item->Delivery_ID;
           
            $allitem = Orderitem::find()->where('Delivery_ID = :id',[':id'=>$did])->all();

            $count = count($allitem);

            if($count <= 1)
            {
                $isvalid = CancelController::deliveryCancel($selection->item);
               
                if(!$isvalid)
                {
                    break;
                }
            }
            else
            {
                //self::selectionCancel($selection->item);
               $isvalid=  CancelController::orderCancel($selection->item);
            }
            
        }
        
        return $isvalid;
       
    }

    public function actionResumeRestaurant($id)
    {
        CommonController::restaurantPermission($id);
        $model = self::findModel($id);
        $model->Restaurant_Status = "Operating";
        if($model->validate())
        {
            $model->save();
            Yii::$app->session->setFlash('success', Yii::t('m-restaurant',"Status change to operating."));
        }
        else
        {
            Yii::$app->session->setFlash('warning', Yii::t('m-restaurant',"Change status failed."));
        }

        return $this->redirect(['/food/menu','rid'=>$id,'page'=>'menu']);
    }

    public function actionActive($id)
    {
       
        $model = Foodstatus::find()->where('Food_ID=:id',[':id'=>$id])->one();
        $food = Food::find()->where('Food_ID=:id',[':id'=>$model['Food_ID']])->one();
        $restaurant = self::findModel($food['Restaurant_ID']);
        $rid =  $food['Restaurant_ID'];
         CommonController::restaurantPermission($rid);
        if ($restaurant['Restaurant_Status'] == 'Closed') {
            Yii::$app->session->setFlash('error', Yii::t('m-restaurant',"Restaurant was not opening."));
            return $this->redirect(Yii::$app->request->referrer);
        }
        $model->Status = 1;
        if($model->validate())
        {
            $model->save();
            Yii::$app->session->setFlash('success', Yii::t('m-restaurant',"Status change to operating."));
        }
        else
        {
            Yii::$app->session->setFlash('warning', Yii::t('m-restaurant',"Change status failed."));
        }
        
          
         return $this->redirect(Yii::$app->request->referrer); 
    }

    public function actionSelectionactive($id)
    {
        $model = Foodselection::findOne($id);
        if(!empty($model))
        {
            $foodStatus = Foodstatus::find()->where('Food_ID = :fid',[':fid'=>$model->Food_ID])->one();
          
            if($foodStatus->Status == 1)
            {
                 $model->Status = 1;
                if(!$model->save())
                {
                    Yii::$app->session->setFlash('warning', Yii::t('m-restaurant',"Change status failed.")); 
                }
            }
            else
            {
                 Yii::$app->session->setFlash('warning', Yii::t('m-restaurant',"Please Turn Off Food to Turn Off Food Selection"));
            }     
            
        }
        else
        {
            Yii::$app->session->setFlash('warning', Yii::t('m-restaurant',"Change status failed."));
        }
      
        return $this->redirect(Yii::$app->request->referrer);
    }

    public function actionDeactive($id,$item)
    {
        $sucess = false;
        switch ($item) {
            case 1:
                $model = self::findModel($id);
                $model->Restaurant_Status = "Closed";
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
               
                //$companyData[$companyName][$selectionName][] = $single;
                //if(empty($companyData[$companyname][$single->trim_selection]['quantity']))
                $companyData[$companyName][$foodName]['rowspan'] = 0;
                $companyData[$companyName][$foodName]['nickname'] = $single->food->Nickname;
                $companyData[$companyName][$foodName][$selectionName]['orderid'][$single->Order_ID]['remark'] = "";
                //$companyData[$companyName][$selectionName]['selection'] = $selectionName;
                //$companyData[$companyName][$selectionName]['count'] = $count;
                
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

        //var_dump($companyData['SGshop Malaysia']);exit;
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
                   
                    //$companyData[$k][$i]['rowspan'] += count($companyData[$k][$i][$key]['orderid']);
                  
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

        foreach($data as $item)
        {
            $allData[$item['food']['Food_ID']][] = $item;
        }
        
        return $this->render('orderlistpdf', ['rid'=>$rid,'allData'=>$allData,'restaurant'=>$restaurant]);
    }

    public function actionPhonecooking()
    {
        $staffs = Rmanagerlevel::find()->where('User_Username=:u',[':u' => Yii::$app->user->identity->username])->all();
        if ($staffs) {
            $count = 0;
            foreach ($staffs as $k => $staff) {
                $restaurants[$k] = Restaurant::find()->where('Restaurant_ID=:r',[':r' => $staff['Restaurant_ID']])->asArray()->one();
                $restaurants[$k]['Restaurant_Orders'] = Orderitem::find()->where('Restaurant_ID=:id AND OrderItem_Status=:s',[':id'=>$staff['Restaurant_ID'],':s'=>2])->joinwith(['food'])->count();
                $count += $restaurants[$k]['Restaurant_Orders'];
            }
            return $this->renderAjax('phonecooking',['restaurants'=>$restaurants,'count'=>$count]);
        }
        elseif(empty($staffs))
        {
            Yii::$app->session->setFlash('warning', Yii::t('m-restaurant',"You are not any staff or owner of restaurant."));
            return $this->redirect(Yii::$app->request->referrer);
        }
        Yii::$app->session->setFlash('warning', Yii::t('m-restaurant',"You Are Not The Right Person In This Page!"));
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