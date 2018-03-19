<?php
namespace backend\models;

use common\models\Order\Orderitem;
use yii\data\ActiveDataProvider;

Class ItemSearch extends Orderitem
{
	public $foodName;

	public function rules()
	{
		return[
			[['foodName'],'safe'],
		];
	}

	public function search($params,$id)
	{
		$query = Orderitem::find()->joinWith(['order_selection','food']);
		if($id != 0 )
		{
			$query->where('Delivery_ID = :id',[':id'=>$id]);
		}

		$dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $dataProvider->sort->attributes['Order_ID'] = [
	        'asc' => ['Order_ID' => SORT_ASC],
	        'desc' => ['Order_ID' => SORT_DESC],
	    ];

	    $dataProvider->sort->attributes['foodName'] = [
	        'asc' => ['food.Name' => SORT_ASC],
	        'desc' => ['food.Name' => SORT_DESC],
	    ];

        $this->load($params);
     
        $query->andFilterWhere([
           'orderitem.Order_ID' => $this->Order_ID,
           'OrderItem_Quantity' => $this->OrderItem_Quantity,
           'OrderItem_LineTotal' => $this->OrderItem_LineTotal,
           'OrderItem_Status' => $this->OrderItem_Status,
        ]);

        $query->andFilterWhere(['like','OrderItem_Remark',$this->OrderItem_Remark]);
        $query->andFilterWhere(['like','food.Name',$this->foodName]);

        return $dataProvider;
	}
}