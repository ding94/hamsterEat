<?php
namespace backend\models;

use common\models\food\Food;
use yii\data\ActiveDataProvider;

class FoodSearch extends Food
{
	public $status;
	public $foodType;
	public $restaurant;

	public function rules()
	{
		return [
			[['Name','BeforeMarkedUp','Price','Description','status','foodType'] ,'safe'],
		];
	}

	public function search($params,$id)
	{
		if($id == 0)
		{
			$query = Food::find();
		}
		else
		{
			$query = Food::find()->where('Restaurant_ID = :id' ,[':id' => $id]);
		}

		$query->innerJoinWith('foodType',true);

		$query->joinWith(['foodStatus']);

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

	    $dataProvider->sort->attributes['status'] = [
	        'asc' => ['status' => SORT_ASC],
	        'desc' => ['status' => SORT_DESC],
	    ];

        $this->load($params);

       	$query->andFilterWhere([
           'Status' => $this->status,
        ]);

       	$query->andFilterWhere(['like','Name' ,$this->Name]);
       	$query->andFilterWhere(['like','BeforeMarkedUp' ,$this->BeforeMarkedUp]);
       	$query->andFilterWhere(['like','Price' ,$this->Price]);
       	$query->andFilterWhere(['like','Description' ,$this->Description]);
        $query->andFilterWhere(['like','Type_Desc' ,$this->foodType]);

        return $dataProvider;
	}
}