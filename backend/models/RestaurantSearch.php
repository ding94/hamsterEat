<?php
namespace backend\models;

use common\models\Restaurant;
use yii\data\ActiveDataProvider;

class RestaurantSearch extends Restaurant
{
	public $user;

	public function rules()
	{
		return[
			[['user'],'safe'],
		];
	}

	public function search($params)
	{
		$query = Restaurant::find();

		$dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);

        return $dataProvider;
	}
}

