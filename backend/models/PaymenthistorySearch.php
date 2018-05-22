<?php

namespace backend\models;

use common\models\Payment;
use common\models\User;
use yii\data\ActiveDataProvider;


Class PaymenthistorySearch extends Payment
{
	public $uid;
	public $create_date;
	public $username;

	public function rules()
	{
		return[
			[['id','uid','username','paid_type'],'safe'],
		];
	}

		public function search($params)
	{
		$query = Payment::find();
		$query->joinWith(['name']);

		$dataProvider = new ActiveDataProvider([
			'query' => $query,
			'sort' => ['defaultOrder' => ['id' => SORT_DESC]],
		]);
		

		$this->load($params);
		
	 	$query->andFilterWhere([
	           'uid' => $this->uid,
	           'paid_amount' => $this->paid_amount,
	           'paid_type' => $this->paid_type,
	           'username' => $this->username,
	       	
	        ]);
	 	 $query->andFilterWhere(['like','payment.id' , $this->id]);
		 $query->andFilterWhere(['like','paid_amount' , $this->paid_amount]);
		 $query->andFilterWhere(['like','paid_type' , $this->paid_type]);
		 return $dataProvider;
	}

}
	