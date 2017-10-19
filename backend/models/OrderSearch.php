<?php
namespace backend\models;

use common\models\Orders;
use yii\data\ActiveDataProvider;
use common\models\Orderitemstatuschange;
use common\models\Ordersstatuschange;
use common\models\Orderitemselection;

Class OrderSearch extends Orders
{
	
	public function search($params)
	{
		
		$query = Orders::find()->where(['!=' ,'Orders_Status' ,'Not Placed']);
		
		$query->joinWith(['order_status']);
		$query->joinWith(['order_item']);


		$dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $dataProvider->sort->attributes['Delivery_ID'] = [
	        'asc' => ['Delivery_ID' => SORT_ASC],
	        'desc' => ['Delivery_ID' => SORT_DESC],
	    ];

        $this->load($params);

        $query->andFilterWhere(['like','Orders.Delivery_ID',$this->Delivery_ID]);

        return $dataProvider;
	}
}