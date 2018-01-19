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

	public function rules()
	{
		return [
			[['Delivery_ID','User_Username','Orders_PaymentMethod','Orders_DateTimeMade','Orders_Status'],'safe'],
		];
	}

	public function search($params,$case)
	{
		switch ($case) {
			case 1:
				$query = Orders::find()->distinct()->where('OrderItem_Status=:s',[':s'=>2])->orderBy('Orders_DateTimeMade DESC');
				$query->joinWith(['order_item']);
				$query->joinWith(['order_status']);
				break;
			case 2:
				$query = Orders::find()->distinct()->where('Orders_Status=:s',[':s'=>2])->andWhere('Orders_DateTimeMade > '.strtotime(date('Y-m-d')))->orderBy('Orders_DateTimeMade DESC');
				$query->joinWith(['order_item']);
				$query->joinWith(['address']);
				break;
			case 3:
				$query = Orderitem::find()->distinct()->where('OrderItem_Status=:s',[':s'=>2])->orderBy('Order_ID DESC');
				$query->joinWith(['order']);
				$query->joinWith(['order_selection']);
				$query->joinWith(['food']);

				break;
			case 4:
				$query = Orders::find()->orderBy('orders.Delivery_ID DESC');
				//$query->joinWith(['order_item']);
				//$query->joinWith(['order_status']);
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
     	$query->andFilterWhere([
           'orders.Delivery_ID' => $this->Delivery_ID,
           'User_Username' => $this->User_Username,
           'Orders_PaymentMethod' => $this->Orders_PaymentMethod,
           'Orders_Status' => $this->Orders_Status,
        ]);
     
        $query->andFilterWhere(['like','Orders_Date',$this->Orders_Date]);
        $query->andFilterWhere(['like','Orders_Time',$this->Orders_Time]);
        $query->andFilterWhere(['like','Orders_Status',$this->Orders_Status]);

        return $dataProvider;
	}
}