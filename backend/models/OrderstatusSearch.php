<?php
namespace backend\models;

use common\models\Order\Orders;
use common\models\Order\Orderitem;
use yii\data\ActiveDataProvider;


Class OrderstatusSearch extends Orders
{

	public function rules()
	{
		return[
			[['Delivery_ID','Orders_Status','Orders_PaymentMethod','Orders_DateTimeMade'],'safe'],
		];
	}

	public function search($params)
	{

		$query = Orders::find()->distinct();
				$query->joinWith(['order_item']);
				$query->joinWith(['order_status']);
				
		
		$dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort'=> ['defaultOrder' => ['Delivery_ID' => SORT_DESC]],
        ]);
     
        $this->load($params);
     
        $query->andFilterWhere([
           'Orders_Status' => $this->Orders_Status,
        ]);
     	
       	$query->andFilterWhere(['like','orders.Delivery_ID',$this->Delivery_ID]);
       	$query->andFilterWhere(['like','Orders_Status',$this->Orders_Status]);
       	$query->andFilterWhere(['like','Orders_PaymentMethod',$this->Orders_PaymentMethod]);
       	 
       	if(!empty($this->Orders_DateTimeMade))
        {
        	$date = explode("to", $this->Orders_DateTimeMade);
        	
        	$first = strtotime($date[0]. ' 00:00:00');
        	$last =  strtotime($date[1]. ' 23:59:59');
        	$query->andWhere(['between','orders.Orders_DateTimeMade',$first,$last]);
        }

        return $dataProvider;
	}
}