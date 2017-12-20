<?php
namespace backend\models;

use common\models\Profit\RestaurantItemProfit;
use yii\data\ActiveDataProvider;

class ItemProfitSearch extends RestaurantItemProfit
{
	public function search($params)
	{
		$query = RestaurantItemProfit::find();

		$dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

		$this->load($params);

		return $dataProvider;
	}
}