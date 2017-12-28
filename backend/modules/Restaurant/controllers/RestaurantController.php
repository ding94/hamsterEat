<?php

namespace backend\modules\Restaurant\controllers;

use Yii;
use yii\web\Controller;
use backend\models\ItemProfitSearch;
use common\models\Profit\RestaurantItemProfit;
use common\models\Restaurant;
use yii\web\NotFoundHttpException;
use yii\helpers\ArrayHelper;

class RestaurantController extends Controller
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
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams,$firstDay,$lastDay,$id);
        return $this->render('index',['model' => $dataProvider ,'searchModel'=>$searchModel,'first'=>$first,'totalProfit' => $totalProfit,'id'=>$id,'restaurantlist'=>$restaurantlist,'tempmodel'=>$tempmodel]);
    }

    public function actionCompareEarnings($id,$first = 0,$oid = 0)
    {
        if ($first == 0) {
            $first = date("Y-m", strtotime("first day of this month"));
        }

        $model = Restaurant::find()->one();

        if (Yii::$app->request->post()) {
            $post = Yii::$app->request->post();
            $oid = $post['Restaurant']['Restaurant_ID'];
        }

        $restaurantlist = Restaurant::find()->asArray()->all();
        $restaurantlist = ArrayHelper::map($restaurantlist, 'Restaurant_ID', 'Restaurant_Name');

        $firstDay = date('Y-m-01 00:00:00', strtotime($first));
        $lastDay = date('Y-m-t 23:59:59', strtotime("last day of".$first.""));

        $totalProfit = $this->monthyTotalProfit($firstDay,$lastDay,$id);

        return $this->render('compare',['first'=>$first,'totalProfit' => $totalProfit,'id'=>$id,'restaurantlist'=>$restaurantlist,'model'=>$model]);
    }

    public function actionChangeOperation($id,$case)
    {
        $model = self::findModel($id);

        switch ($case) {
            case 1:
                $model['Restaurant_Status'] = 'Closed';
                break;
            case 2:
                $model['Restaurant_Status'] = 'Operating';
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
}