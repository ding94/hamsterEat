<?php
namespace frontend\models;

use Yii;
use yii\data\ActiveDataProvider;
use common\models\Order\Orderitem;

class OrderHistorySearch extends Orderitem
{
	public $keyWordArray = [1=>'Delivery ID',2=>"Order ID",3=>"FoodName"];
	public $keyWordStatus =1;
	public $keyWord;
	public $status;
	public $statusType = 1;
	public $first;
	public $last;

	/*
	* oid => order id
	* fid => fid
	* did => delivery id
	* status =>  status
	*/
	public function rules()
	{
		return [
			[['keyWordStatus','keyWord','status','first','last','statusType'] ,'safe'],
		];
	}

	/*
	* type => 1 is Delivery Man
	*      => 2 is Restaurant
	*/
	public function search($params,$id,$type)
	{
		$query = Orderitem::find()->distinct()->joinWith(['food','order'])->orderBy(['orderitem.Delivery_ID'=>SORT_DESC]);

		if($type == 2)
		{
			$query->where("Restaurant_ID = :rid and Orders_Status != 1",[':rid'=>$id]);
			
		}
		else
		{
			$query->where("deliveryman = :did",['did'=>$id]);
			$query->joinWith(['address']);
		}

		$this->load($params);
		
		if(empty($this->first) || empty($this->last))
		{
			$this->first = date("Y-m-d", strtotime("first day of this month"));
			$this->last = date("Y-m-d", strtotime("last day of this month"));
		}

		if($this->statusType == 1)
		{
			$query->andFilterWhere(['Orders_Status' => $this->status]);
		}
		else
		{
			$query->andFilterWhere(['OrderItem_Status' => $this->status]);
		}

		switch ($this->keyWordStatus) {
			case 1:
				$query->andFilterWhere(['orders.Delivery_ID' => $this->keyWord]);
				break;
			case 2:
				$query->andFilterWhere(['Order_ID' => $this->keyWord]);
				break;
			case 3:
				$query->andFilterWhere(['like','food.Name', $this->keyWord]);
				break;
			default:
				# code...
				break;
		}

		$query->andWhere(['between','Orders_DateTimeMade',strtotime($this->first),strtotime($this->last)]);

		return $query;
	}
}