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
			[['foodName','OrderItem_Status'],'safe'],
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
	        'asc' => ['foodName' => SORT_ASC],
	        'desc' => ['foodName' => SORT_DESC],
	    ];

        $this->load($params);
     
        $query->andFilterWhere([
           'orderitem.Order_ID' => $this->Order_ID,
           'OrderItem_Quantity' => $this->OrderItem_Quantity,
           'OrderItem_LineTotal' => $this->OrderItem_LineTotal,
           'OrderItem_Status' => $this->OrderItem_Status,
        ]);

        $query->andFilterWhere(['like','OrderItem_Remark',$this->OrderItem_Remark]);
        $query->andFilterWhere(['like','Description',$this->foodName]);

        return $dataProvider;
	}
}