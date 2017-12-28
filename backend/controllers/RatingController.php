<?php
namespace backend\controllers;

use Yii;
use yii\web\Controller;
use common\models\Rating\RatingSearch;
use common\models\Order\DeliveryAddress;
use common\models\Rating\Servicerating;
use common\models\Rating\Foodrating;
use common\models\food\Food;
use backend\controllers\CommonController;

Class RatingController extends Controller
{
	public function actionIndex()
	{
		$searchModel = new RatingSearch();
    	$dataProvider = $searchModel->search(Yii::$app->request->queryParams);

    	return $this->render('index',['model' => $dataProvider , 'searchModel' => $searchModel]);
	}

	public function actionFoodRatingStats($year = 0, $type = 0, $fid)
	{
		if ($year == 0){
			$year = date('Y',strtotime('this year'));
		}
		$months = CommonController::getYear($year);
		$startend = CommonController::getStartEnd($year);
		foreach ($startend as $key => $value) {
			$ratingpermonth = self::calRatingForFoodPerMonth($value[0],$value[1],$fid);
			$ratingthisyear[] = $ratingpermonth;
		}
		$data['months'] = $months;
		$data['allrating'] = $ratingthisyear;
		$arrayType = [0=>'bar','1'=>'horizontalBar','2'=>'line'];
		return $this->render('rating-stats',['data'=>$data,'arrayType'=>$arrayType,'year'=>$year,'type'=>$type]);
	}

	public function actionAverageRestaurantRatingStats($year = 0, $type = 0, $rid)
	{
		if ($year == 0){
			$year = date('Y',strtotime('this year'));
		}
		$months = CommonController::getYear($year);
		$startend = CommonController::getStartEnd($year);
		foreach ($startend as $key => $value) {
			$ratingpermonth = self::calRatingForRestaurantPerMonth($value[0],$value[1],$rid);
			$ratingthisyear[] = $ratingpermonth;
		}
		$data['months'] = $months;
		$data['allrating'] = $ratingthisyear;
		$arrayType = [0=>'bar','1'=>'horizontalBar','2'=>'line'];
		return $this->render('rating-stats',['data'=>$data,'arrayType'=>$arrayType,'year'=>$year,'type'=>$type]);
	}

	public static function calRatingForFoodPerMonth($start,$end,$fid)
	{
		$food = Foodrating::find()->select(['FoodRating_Rating'])->where('Food_ID=:fid',[':fid'=>$fid])->andWhere(['between','created_at',$start,$end])->asArray()->all();
		$totalrating = 0;
		$count = 0;
		if (!empty($food)) {
			foreach ($food as $key => $value) {
				$count +=1;
				$totalrating +=$value['FoodRating_Rating'];
			}
		} else {
			$totalrating = 0;
			$count = 0;
		}

		if ($totalrating != 0) {
			$averagerating = $totalrating/$count;
		} else {
			$averagerating = 0;
		}
		return $averagerating;
	}

	public static function calRatingForRestaurantPerMonth($start,$end,$rid)
	{
		$food = Food::find()->select(['Food_ID'])->where('Restaurant_ID=:rid',[':rid'=>$rid])->asArray()->all();
		if(!empty($food)){
			foreach ($food as $key => $value) {
				$foodrating[] = self::calRatingForFoodPerMonth($start,$end,$value['Food_ID']);
			}
		} else {
			$foodrating = [];
		}
		$count = 0;
		$totalrating = 0;
		if(!empty($foodrating)){
			foreach ($foodrating as $key => $value) {
				$count += 1;
				$totalrating += $value;
			}
			$averagerating = $totalrating/$count;
		} else {
			$averagerating = 0;
		}
		return $averagerating;
	}

	public static function calRatingPerMonth($start,$end,$cid)
	{
		$did = DeliveryAddress::find()->select(['delivery_id'])->where('cid=:cid',[':cid'=>$cid])->asArray()->all();
		$totalrating = 0;
		$count = 0;
		foreach ($did as $key => $value) {
			$ratingmodel = Servicerating::find()->where('delivery_id=:delivery_id',[':delivery_id'=>$value['delivery_id']])->andWhere(['between','created_at',$start,$end])->one();
			if (!empty($ratingmodel)) {
				$count+=1;
				$totalrating += ($ratingmodel['DeliverySpeed'] + $ratingmodel['Service'] + $ratingmodel['UserExperience'])/3;
			} else {
				$totalrating += 0;
			}
		}
		if ($totalrating != 0) {
			$averagerating = $totalrating/$count;
		} else {
			$averagerating = 0;
		}
		return $averagerating;
	}
}