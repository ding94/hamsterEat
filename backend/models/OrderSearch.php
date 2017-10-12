<?php
namespace backend\models;

use common\models\Orders;
use yii\data\ActiveDataProvider;

Class OrderSearch extends Orders
{
	public function search($params)
	{
		
		$query = Orders::find()->where(['!=' ,'Orders_Status' ,'Not Placed']);
		
		$dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        
        $this->load($params);

       

        return $dataProvider;
	}
}