<?php
namespace frontend\modules\Restaurant\controllers;

use yii;
use yii\web\Controller;
use yii\helpers\Json;
use yii\data\Pagination;
use yii\data\ArrayDataProvider;
use frontend\controllers\CommonController;
use common\models\Profit\{RestaurantItemProfit};
use common\models\food\Food;


class StatisticsController extends CommonController
{
	public function actionIndex($first = 0,$last = 0,$rid)
	{
		$linkData = CommonController::restaurantPermission($rid);
		$link = CommonController::getRestaurantUrl($linkData,$rid);
		if($first == 0 || $last == 0)
		{
			$first = date("Y-m-d", strtotime("first day of this month"));
		
			$last = date("Y-m-d", strtotime("last day of this month"));
		}

		$ts_first = strtotime($first);
		$ts_last = strtotime($last);

		$food = Food::find()->where('Restaurant_ID=:rid',[':rid'=>$rid])->asArray()->all();

		foreach ($food as $key => $value) {
			$food_array = ['id'=>(int)$value['Food_ID'],'name'=>$value['Name']];
            $json_food = Json::encode($food_array);

			$model = RestaurantItemProfit::find()->where('fid = :fid',[':fid'=>$json_food])->andWhere(['between','created_at',$ts_first,$ts_last])->asArray()->all();

            $modelcount = 0;
            if(empty($model)){
                $modelcount = 0;
                $data[$key][Yii::t('order','Food Name')] = $value['Name'];
                $data[$key][Yii::t('m-restaurant','Quantity Sold')] = $modelcount;
            } else {
                foreach ($model as $k => $v) {
                    $modelcount+= $v['quantity'];
                }
                $data[$key][Yii::t('order','Food Name')] = $value['Name'];
                $data[$key][Yii::t('m-restaurant','Quantity Sold')] = $modelcount;
            }
		}
		
		$provider = new ArrayDataProvider([
	        'allModels' => $data,
	        'pagination' => [
	            'pageSize' => 10,
	        ],
	        'sort' => [
	        	'defaultOrder' => [
				    Yii::t('m-restaurant','Quantity Sold') => SORT_DESC,
				],
				'attributes' => [
					Yii::t('m-restaurant','Quantity Sold')=>[
						'asc'=>[Yii::t('m-restaurant','Quantity Sold')=> SORT_ASC],
						'desc'=>[Yii::t('m-restaurant','Quantity Sold')=> SORT_DESC],
					],
				],
			],
	    ]);

		return $this->render('index',['first'=>$first,'last'=>$last,'provider'=>$provider,'link'=>$link]);
	}
}