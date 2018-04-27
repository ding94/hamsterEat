<?php

namespace backend\models;

use common\models\Payment;
use common\models\User;
use yii\data\ActiveDataProvider;
use common\models\PaymentGateWay\PaymentGateWayHistory;


Class OnlinepaymenthistorySearch extends Payment
{
	

	public function rules()
	{
		return[
			//[['id','uid','username','paid_type'],'safe'],
		];
	}

		public function search($params)
	{
		$query = PaymentGatewayHistory::find()->orderBy('pid DESC');

		$dataProvider = new ActiveDataProvider([
			'query' => $query,
		]);
		

		$this->load($params);
		
	 	$query->andFilterWhere([
	          
	        ]);
	 	
		 return $dataProvider;
	}

}
	