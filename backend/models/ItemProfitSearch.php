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
		
		$query = RestaurantItemProfit::find()->where(['between','created_at',strtotime($first),strtotime($last)]);

		if($id !=0)
		{
			if($params['r'] == 'restaurant/restaurant/profit')
			{
				$query->andWhere('rid = :rid',[':rid'=>$id]);
			}
			else
			{
				$query->andWhere('did = :did',[':did'=>$id]);
			}
			
		}
	
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