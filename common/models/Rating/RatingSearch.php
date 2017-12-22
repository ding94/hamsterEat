<?php
namespace common\models\Rating;

use common\models\Order\Orders;
use yii\data\ActiveDataProvider;

class RatingSearch extends Orders
{
	public function rules()
    {
        return [
            [['Delivery_ID','User_Username'],'safe' ],
        ];
    }

	public function search($params)
	{
		$query = Orders::find()->where(['Orders_Status' => 7]);

	
		$dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

		$query->joinWith(['servicerating','foodrating','foodrating.foods','foodrating.foodstatus']);

		$this->load($params);

		 $query->andFilterWhere([ Orders::tableName().'.Delivery_ID' => $this->Delivery_ID])
		 	 	->andFilterWhere(['like','User_Username' , $this->User_Username]);
		return $dataProvider;
	}
}