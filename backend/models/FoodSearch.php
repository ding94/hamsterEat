<?php
namespace backend\models;

use common\models\food\Food;
use yii\data\ActiveDataProvider;

class FoodSearch extends Food
{
	public function search($params,$id)
	{
		$query = Food::find()->where('Restaurant_ID = :id',[':id' => $id]);

		$dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);		

        $this->load($params);



        return $dataProvider;
	}
}