<?php

namespace backend\modules\Restaurant\controllers;

use Yii;
use yii\web\Controller;
use backend\models\ItemProfitSearch;
use backend\models\RestaurantSearch;
use backend\models\RmanagerSearch;
use common\models\Profit\RestaurantItemProfit;
use common\models\Restaurant;
use common\models\Food\Food;
use yii\web\NotFoundHttpException;
use yii\helpers\ArrayHelper;
use backend\controllers\CommonController;

class RestaurantController extends CommonController
{
    public function actionProfit($id,$first =0)
    {
        $tempmodel = Restaurant::find()->one();

        $restaurantlist = Restaurant::find()->asArray()->all();
        $restaurantlist = ArrayHelper::map($restaurantlist, 'Restaurant_ID', 'Restaurant_Name');

        if($first == 0)
        {
            $first = date("Y-m", strtotime("first day of this month")); 
        }

        if (Yii::$app->request->post()) {
            $post = Yii::$app->request->post();
            $oid = $post['Restaurant']['Restaurant_ID'];

            $firstDay = date('Y-m-01 00:00:00', strtotime($first));
            $lastDay = date('Y-m-t 23:59:59', strtotime("last day of".$first.""));

            $totalProfit = $this->monthyTotalProfit($firstDay,$lastDay,$id);
            $totalProfitOther = $this->monthyTotalProfit($firstDay,$lastDay,$oid);

            return $this->render('compare',['first'=>$first,'totalProfit' => $totalProfit,'totalProfitOther' => $totalProfitOther,'id'=>$id,'restaurantlist'=>$restaurantlist,'tempmodel'=>$tempmodel,'oid'=>$oid]);
        }
       
        $firstDay = date('Y-m-01 00:00:00', strtotime($first));
        $lastDay = date('Y-m-t 23:59:59', strtotime("last day of".$first.""));

        $totalProfit = $this->monthyTotalProfit($firstDay,$lastDay,$id);
       
        $searchModel = new ItemProfitSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams,$firstDay,$lastDay,$id,1);
        return $this->render('index',['model' => $dataProvider ,'searchModel'=>$searchModel,'first'=>$first,'totalProfit' => $totalProfit,'id'=>$id,'restaurantlist'=>$restaurantlist,'tempmodel'=>$tempmodel]);
    }

    public function actionRestaurantRankingPerMonth($month=0)
    {
        if($month == 0){
            $month = date('Y-n',strtotime('this year'));
        }
        $explodemonth = explode("-",$month);
        $restaurant = Restaurant::find()->asArray()->joinWith('restaurantEnName')->all();
        $months = CommonController::getYear($explodemonth[0]);
        $startend = CommonController::getStartEnd($explodemonth[0]);
        foreach ($startend as $i => $date) {
            foreach ($restaurant as $key => $value) {
                $model = RestaurantItemProfit::find()->where('rid = :rid',[':rid'=>$value['Restaurant_ID']])->andWhere(['between','created_at',$date[0],$date[1]])->asArray()->all();
                $modelcount = 0;
                if(empty($model)){
                    $modelcount = 0;
                    $data[$i][$value['restaurantEnName']['translation']] = $modelcount;
                } else {
                    foreach ($model as $k => $v) {
                        $modelcount+= $v['quantity'];
                    }
                    $data[$i][$value['restaurantEnName']['translation']] = $modelcount;
                }
            }
            arsort($data[$i]);   
        }
        $data = $data[$explodemonth[1]];
        if (count($data) > 10 ){
            $data = array_slice($data,0,10);
        }
        foreach ($data as $key => $value) {
            $data['rname'][] = $key;
            $data['count'][] = $value;
        }
        return $this->render('ranking',['month'=>$month,'data'=>$data]);
    }

    public function actionChangeOperation($id,$case)
    {
        $model = self::findModel($id);
        
        switch ($case) {
            case 1:
                $model['Restaurant_Status'] = 3;
                break;
            case 2:
                $model['Restaurant_Status'] = 2;
                break;
            default:
                break;
        }

        if($model->validate())
        {
        	$model->save();
            Yii::$app->session->setFlash('success', "Status changed!");
        }
        else
        {
            Yii::$app->session->setFlash('warning', "Change status failed.");
        }

        return $this->redirect(Yii::$app->request->referrer);
    }

    /*
    * get how many month use diff
    * use for loop to loop diff month
    * every end loop +1 month for first
    * lastday use to get last day of this month
    */
    protected static function monthyTotalProfit($first,$last,$id)
    {
        $datetime1 = date_create($first);
        $datetime2 = date_create($last);
        $totalMonth = $datetime1->diff($datetime2)->m;
       
        for($i = 0 ; $i<=$totalMonth ; $i++)
        {
            $month = date('Y-m', strtotime($first));
            $lastDay =  date('Y-m-t 23:59:59', strtotime($first));

            $cost = RestaurantItemProfit::find()->where('rid = :rid',[':rid'=>$id])->andWhere(['between','created_at',strtotime($first),strtotime($lastDay)])->sum('quantity * originalPrice');

            $sellPrice = RestaurantItemProfit::find()->where('rid = :rid',[':rid'=>$id])->andWhere(['between','created_at',strtotime($first),strtotime($lastDay)])->sum('quantity * finalPrice');

            $data[$month]['cost'] = is_null($cost) ? 0 : Yii::$app->formatter->format($cost, ['decimal', 2]);

            $data[$month]['sellPrice'] = is_null($sellPrice) ? 0 : Yii::$app->formatter->format($sellPrice, ['decimal', 2]);
            $first = date('Y-m-01 00:00:00', strtotime("+1 month", strtotime($first)));
        }
       
        return $data;
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

    public function actionShowRestaurants()
    {
        $searchModel = new RestaurantSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams,2);

        return $this->render('showrestaurant',['dataProvider'=>$dataProvider, 'searchModel'=>$searchModel]);
    }

    public function actionSpeedrating($rid)
    {
        $restaurant = Restaurant::find()->where('restaurant.Restaurant_ID=:rid',[':rid'=>$rid])->joinWith(['food','restaurantEnName'])->one();
        $foodname = Food::find()->where('Restaurant_ID=:rid',[':rid'=>$rid])->asArray()->all();
        $foodname = ArrayHelper::map($foodname, 'Food_ID', 'Name');

        if ($post=Yii::$app->request->post()) {
            $start = strtotime($post['Restaurant']['timestart']);
            $end = strtotime($post['Restaurant']['timeend']);
            $restaurant = Restaurant::find()->where('restaurant.Restaurant_ID=:rid',[':rid'=>$rid])
            ->joinWith(['food','food.orderitem'=>function($query)use($start,$end){$query->joinWith('order')->andWhere(['between','orders.Orders_DateTimeMade',$start,$end]);}])
            ->one();
            if (empty($restaurant['food'])) {
                Yii::$app->session->setFlash('warning', "No food was available");
                return $this->redirect(['/restaurant/restaurant/speedrating','rid'=>$rid]);
            }
        }

        if (empty($restaurant['food'])) {
            Yii::$app->session->setFlash('warning', "No food was available");
            return $this->redirect(['/restaurant/restaurant/show-restaurants']);
        }

        foreach ($restaurant['food'] as $first => $foods) {

            $data[$foods['Food_ID']]['pending'] = 0;
            $data[$foods['Food_ID']]['preparing'] = 0;
            $data[$foods['Food_ID']]['ready'] = 0;
            $data[$foods['Food_ID']]['pickedup'] = 0;
            $count=0;

            foreach ($foods['orderitem'] as $second => $oitem) {
                foreach ($data as $thrid => $food) {
                    $time1 = $oitem['item_status']['Change_PendingDateTime'] - $oitem['order']['Orders_DateTimeMade'];
                    $time2 = $oitem['item_status']['Change_PreparingDateTime'] - $oitem['item_status']['Change_PendingDateTime'];
                    $time3 = $oitem['item_status']['Change_ReadyForPickUpDateTime'] - $oitem['item_status']['Change_PreparingDateTime'];
                    $time4 = $oitem['item_status']['Change_PickedUpDateTime'] - $oitem['item_status']['Change_ReadyForPickUpDateTime'];
                    $valid = $time1 >0 && $time2 >0 && $time3 >0 && $time4 >0;

                    foreach ($food as $fourth => $value) {
                        if ($valid) {
                            switch ($fourth) {
                                case 'pending':
                                    $data[$foods['Food_ID']]['pending'] += $time1;
                                    break;

                                case 'preparing':
                                    $data[$foods['Food_ID']]['preparing'] += $time2;
                                    break;

                                case 'ready':
                                    $data[$foods['Food_ID']]['ready'] += $time3;
                                    break;

                                case 'pickedup':
                                    $data[$foods['Food_ID']]['pickedup'] += $time4;
                                    break;
                                
                                default:
                                    # code...
                                    break;
                            }
                        }
                    }
                    if ($valid == true) {
                        $count+=1;
                    }
                }
            }
            $data[$foods['Food_ID']]['divider'] = $count;

        }
        
        if (!empty($post)) {
            return $this->render('speedrating',['data'=>$data,'foodname'=>$foodname,'restaurant'=>$restaurant,'post'=>$post]);
        }
        return $this->render('speedrating',['data'=>$data,'foodname'=>$foodname,'restaurant'=>$restaurant]);
    }
}