<?php
namespace backend\models;

use common\models\Order\Orders;
use common\models\Order\Orderitem;
use yii\data\ActiveDataProvider;

Class ItemSearch extends Orderitem
{
	public $foodName;
	public $User_Username;
	public $Orders_PaymentMethod;
	public $Orders_DateTimeMade;
	public function rules()
	{
		return[
			[['Order_ID','User_Username','Orders_PaymentMethod','Orders_DateTimeMade','foodName','OrderItem_Status'],'safe'],
		];
	}

	public function search($params,$id=0)
	{

		$query = Orderitem::find()->distinct()->orderBy('Order_ID DESC');
				$query->joinWith(['order']);
				$query->joinWith(['order_selection']);
				$query->joinWith(['food']);

		if($id != 0 )
		{	
			$query->where('orderitem.Delivery_ID = :id',[':id'=>$id]);
		}

		$dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $dataProvider->sort->attributes['Order_ID'] = [
	        'asc' => ['Order_ID' => SORT_ASC],
	        'desc' => ['Order_ID' => SORT_DESC],
	    ];

	    $dataProvider->sort->attributes['FoodName'] = [
	        'asc' => ['foodName' => SORT_ASC],
	        'desc' => ['foodName' => SORT_DESC],
	    ];
	    
	    $dataProvider->sort->attributes['User_Username'] =
	    [
	    'asc' => ['orders.User_Username' => SORT_ASC], // TABLE_NAME.COLUMN_NAME
	    'desc' => ['orders.User_Username' => SORT_DESC],
	    ];

	    $dataProvider->sort->attributes['Orders_PaymentMethod'] = [
	        'asc' => ['orders.Orders_PaymentMethod' => SORT_ASC],
	        'desc' => ['orders.Orders_PaymentMethod' => SORT_DESC],
	    ];

 	   $dataProvider->sort->attributes['Orders_DateTimeMade'] = [
	        'asc' => ['orders.Orders_DateTimeMade' => SORT_ASC],
	        'desc' => ['orders.Orders_DateTimeMade' => SORT_DESC],
	    ];

	    $dataProvider->sort->attributes['Orders_DateTimeMade'] = [
	        'asc' => ['orders.Orders_DateTimeMade' => SORT_ASC],
	        'desc' => ['orders.Orders_DateTimeMade' => SORT_DESC],
	    ];
        $this->load($params);
     	
        $query->andFilterWhere([
           'orderitem.Order_ID' => $this->Order_ID,
           'OrderItem_Quantity' => $this->OrderItem_Quantity,
           'OrderItem_LineTotal' => $this->OrderItem_LineTotal,
           'OrderItem_Status' => $this->OrderItem_Status,
        ]);
	    //$query->andFilterWhere(['like','orderitem.Delivery_ID',$this->Delivery_ID]);
	    $query->andFilterWhere(['like','orders.User_Username',$this->User_Username]);
	    $query->andFilterWhere(['like','orders.Orders_PaymentMethod',$this->Orders_PaymentMethod]);
        $query->andFilterWhere(['like','OrderItem_Remark',$this->OrderItem_Remark]);
        $query->andFilterWhere(['like','Description',$this->foodName]);

	  if(!empty($this->Orders_DateTimeMade))
        {
        	$date = explode("to", $this->Orders_DateTimeMade);
        	
        	$first = strtotime($date[0]. ' 00:00:00');
        	$last = empty($data[1]) ? strtotime($date[0]. ' 23:59:59') : strtotime($date[1]. ' 23:59:59');
        	$query->andWhere(['between','orders.Orders_DateTimeMade',$first,$last]);
        }
        return $dataProvider;
	}
}