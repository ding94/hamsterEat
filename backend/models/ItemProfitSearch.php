<?php
namespace backend\models;

use common\models\Profit\RestaurantItemProfit;
use yii\data\ActiveDataProvider;

class ItemProfitSearch extends RestaurantItemProfit
{
	public $oid;

	public function rules()
	{
		return [
			[['oid'] ,'safe'],
		];
	}
	/*
	* first = first day
	* last = last day
	* id = delviery id
	* rid = restaurant id
	*/
	public function search($params,$first,$last,$id)
	{
		
		$query = RestaurantItemProfit::find();

		if($id !=0)
		{
			if($params['r'] == 'restaurant/restaurant/profit')
			{
				$query->andWhere('rid = :rid',[':rid'=>$id]);
				$first = date('Y-m-01 00:00:00', strtotime($first));
				$last = date('Y-m-t 00:00:00', strtotime($last));
			}
			else
			{
				$query->andWhere('did = :did',[':did'=>$id]);
			}
			
		}
		
		$query->andWhere(['between','created_at',strtotime($first),strtotime($last)]);
		$dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

		$this->load($params);

		$query->andFilterWhere([
           'oid' => $this->oid,
        ]);
		return $dataProvider;
	}
}