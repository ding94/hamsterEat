<?php
namespace backend\models;

use common\models\Orders;
use common\models\Orderitem;
use yii\data\ActiveDataProvider;
use common\models\Orderitemstatuschange;
use common\models\Ordersstatuschange;
use common\models\Orderitemselection;

Class OrderSearch extends Orders
{
	
	public function search($params,$case)
	{
		switch ($case) {
			case 1:
				$query = Orders::find()->orderBy('Orders_Date DESC');
				$query->joinWith(['order_item']);
				$query->joinWith(['order_status']);
				break;
				
			default:
				# code...
				break;
		}
		
		$dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $dataProvider->sort->attributes['Delivery_ID'] = [
	        'asc' => ['Delivery_ID' => SORT_ASC],
	        'desc' => ['Delivery_ID' => SORT_DESC],
	    ];

        $this->load($params);

        $query->andFilterWhere(['like','Orders.Delivery_ID',$this->Delivery_ID]);
        $query->andFilterWhere(['like','User_Username',$this->User_Username]);
        $query->andFilterWhere(['like','Orders_Date',$this->Orders_Date]);
        $query->andFilterWhere(['like','Orders_Time',$this->Orders_Time]);
        $query->andFilterWhere(['like','Orders_Status',$this->Orders_Status]);

        return $dataProvider;
	}
}