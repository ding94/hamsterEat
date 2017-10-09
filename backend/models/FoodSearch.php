<?php
namespace backend\models;

use common\models\food\Foodselection;
use yii\data\ActiveDataProvider;

class FoodSearch extends Foodselection
{
	public function search($params,$id)
	{
		$query = Foodselection::find()->where('food.Restaurant_ID = :id' ,[':id' => $id]);

		$query->joinWith(['foodselectiontype','food']);

		$dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);		

        $this->load($params);



        return $dataProvider;
	}
}