<?php

namespace backend\models;

use common\models\Payment;
use common\models\User;
use yii\data\ActiveDataProvider;
use common\models\PaymentGateWay\PaymentGateWayHistory;


Class OnlinepaymenthistorySearch extends Payment
{
	public $bill_id;
	public $collect_id;
	public $pid;
	public $status;
	public function rules()
	{
		return[
			[['bill_id','collect_id','pid','status'],'safe'],
		];
	}

		public function search($params)
	{
		$query = PaymentGatewayHistory::find()->orderBy('pid DESC');
		$dataProvider = new ActiveDataProvider(['query' => $query,]);
		

		$this->load($params);
		
	 	$query->andFilterWhere([
	          'pid' => $this->pid,
	          'status' => $this->status,
	        ]);

		$query->andFilterWhere(['like','collect_id' , $this->collect_id]);
		$query->andFilterWhere(['like','bill_id' , $this->bill_id]);
	 	
	 
		 return $dataProvider;
	}

}
	