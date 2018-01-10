<?php
namespace backend\models;

use common\models\Order\Orders;
use common\models\Order\Orderitem;
use yii\data\ActiveDataProvider;
use common\models\Order\Orderitemstatuschange;
use common\models\Order\Ordersstatuschange;
use common\models\Order\Orderitemselection;

Class OrderSearch extends Orders
{
	
	public function search($params,$case)
	{
		switch ($case) {
			case 1:
				$query = Orders::find()->where('OrderItem_Status=:s',[':s'=>2])->orderBy('Orders_DateTimeMade DESC');
				$query->joinWith(['order_item']);
				$query->joinWith(['order_status']);
				break;
			case 2:
				$query = Orders::find()->where('Orders_Status=:s',[':s'=>2])->andWhere('Orders_DateTimeMade > '.strtotime(date('Y-m-d')))->orderBy('Orders_DateTimeMade DESC');
				$query->joinWith(['order_item']);
				$query->joinWith(['address']);
				break;
			case 3:
				$query = Orderitem::find()->where('OrderItem_Status=:s',[':s'=>2])->orderBy('Order_ID DESC');
				$query->joinWith(['order']);
				$query->joinWith(['order_selection']);
				$query->joinWith(['food']);

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