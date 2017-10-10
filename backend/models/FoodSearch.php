<?php
namespace backend\models;

use common\models\food\Food;
use yii\data\ActiveDataProvider;

class FoodSearch extends Food
{
	public $status;
	public function rules()
	{
		return [
			[['Name','BeforeMarkedUp','Price','Description','status'] ,'safe'],
		];
	}

	public function search($params,$id)
	{
		$query = Food::find()->where('Restaurant_ID = :id' ,[':id' => $id]);

		$query->innerJoinWith('foodType',true);

		$dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $dataProvider->sort->attributes['foodName'] = [
	        'asc' => ['food.Name' => SORT_ASC],
	        'desc' => ['food.Name' => SORT_DESC],
	    ];

	    $dataProvider->sort->attributes['foodPrice'] = [
	        'asc' => ['food.Price' => SORT_ASC],
	        'desc' => ['food.Price' => SORT_DESC],
	    ];	

        $this->load($params);

        return $dataProvider;
	}
}