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
			[['bill_id','collect_id','pid','status','created_at'],'safe'],
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
	 	 if(!empty($this->created_at))
        {
        	$date = explode("to", $this->created_at);
        	
        	$first = strtotime($date[0]. ' 00:00:00');
        	
	        $last =strtotime($date[1]. ' 23:59:59');
	        	
        	$query->andWhere(['between','created_at',$first,$last]);
        }
     	
		 return $dataProvider;
	}

}
	