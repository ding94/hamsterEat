<?php
namespace backend\models;

use common\models\Restaurant;
use yii\data\ActiveDataProvider;

class RestaurantSearch extends Restaurant
{
	public $area;
	public $approve;

	public function rules()
	{
		return[
			[['Restaurant_ID','Restaurant_Manager','Restaurant_Name','Restaurant_Status','approve','area'],'safe'],
		];
	}

	public function search($params)
	{
		$query = Restaurant::find();

		$query->joinWith(['area','manager']);

		$dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

		$dataProvider->sort->attributes['area'] = [
	        'asc' => ['area.Area_State' => SORT_ASC],
	        'desc' => ['area.Area_State' => SORT_DESC],
	    ];

	    $dataProvider->sort->attributes['approve'] = [
	        'asc' => ['rmanager.Rmanager_Approval' => SORT_ASC],
	        'desc' => ['rmanager.Rmanager_Approval' => SORT_DESC],
	    ];

        $this->load($params);

        $query->andFilterWhere([
            'Restaurant_ID' => $this->Restaurant_ID,
            'Restaurant_Status' => $this->Restaurant_Status,
            'area.Area_State' => $this->area,
            'rmanager.Rmanager_Approval' => $this->approve,
        ]);

        $query->andFilterWhere(['like','Restaurant_Manager' ,$this->Restaurant_Manager]);

        return $dataProvider;
	}
}

