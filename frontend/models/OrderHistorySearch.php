<?php
namespace frontend\models;

use Yii;
use yii\data\ActiveDataProvider;
use common\models\Order\Orderitem;

class OrderHistorySearch extends Orderitem
{
	public $oid;
	public $fid;
	public $did;
	public $status;
	public $type = 1;
	public $first;
	public $last;

	/*
	* oid => order id
	* fid => fid
	* did => delivery id
	* status =>  status
	* type => 1=> delviery status 2=> order status
	*/
	public function rules()
	{
		return [
			[['oid','fid','did','first','last','status','type'] ,'safe'],
		];
	}

	public function search($params,$rid)
	{
		$query = Orderitem::find()->distinct()->where("Restaurant_ID = :rid and Orders_Status != 1",[':rid'=>$rid,])->joinWith(['food','order'])->orderBy(['orderitem.Delivery_ID'=>SORT_DESC]);

		$this->load($params);
		
		if(empty($this->first) || empty($this->last))
		{
			$this->first = date("Y-m-d", strtotime("first day of this month"));
			$this->last = date("Y-m-d", strtotime("last day of this month"));
		}

		if($this->type == 1)
		{
			$query->andFilterWhere(['Orders_Status' => $this->status]);
		}
		else
		{
			$query->andFilterWhere(['OrderItem_Status' => $this->status]);
		}

		$query->andFilterWhere(['Order_ID' => $this->oid]);
		$query->andFilterWhere(['orders.Delivery_ID' => $this->did]);
		$query->andFilterWhere(['orderitem.Food_ID' => $this->fid]);
		$query->andWhere(['between','Orders_DateTimeMade',strtotime($this->first),strtotime($this->last)]);

		return $query;
	}
}