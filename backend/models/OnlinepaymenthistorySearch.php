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
		$query = PaymentGatewayHistory::find();
		$dataProvider = new ActiveDataProvider([
			'query' => $query,
			'sort' => ['defaultOrder' => ['pid' => SORT_DESC]],
		]);
		
        $this->load($params);
		$dataProvider->sort->attributes['bill_id'] = [
			'asc'=>['bill_id'=>SORT_ASC],
			'desc'=>['bill_id'=>SORT_DESC],
		];
		$dataProvider->sort->attributes['collect_id'] = [
            'asc'=>['collect_id'=>SORT_ASC],
            'desc'=>['collect_id'=>SORT_DESC],
        ];

        $dataProvider->sort->attributes['pid'] = [
            'asc'=>['pid'=>SORT_ASC],
            'desc'=>['pid'=>SORT_DESC],
        ];

        $dataProvider->sort->attributes['status'] = [
            'asc'=>['status'=>SORT_ASC],
            'desc'=>['status'=>SORT_DESC],
        ];


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
	