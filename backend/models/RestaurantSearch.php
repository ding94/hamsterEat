<?php
namespace backend\models;

use common\models\Restaurant;
use yii\data\ActiveDataProvider;

class RestaurantSearch extends Restaurant
{
	public $area;

	public function rules()
	{
		return[
			[['Restaurant_ID','Restaurant_Manager','Restaurant_Name','Restaurant_Status','area'],'safe'],
		];
	}

	public function search($params)
	{
		$query = Restaurant::find();

		$query->joinWith(['area']);

		$dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

		$dataProvider->sort->attributes['area'] = [
	        'asc' => ['area.Area_State' => SORT_ASC],
	        'desc' => ['area.Area_State' => SORT_DESC],
	    ];

        $this->load($params);

        $query->andFilterWhere([
            'Restaurant_ID' => $this->Restaurant_ID,
            'Restaurant_Status' => $this->Restaurant_Status,
            'area.Area_State' => $this->area,
        ]);

        $query->andFilterWhere(['like','Restaurant_Manager' ,$this->Restaurant_Manager]);

        return $dataProvider;
	}
}

