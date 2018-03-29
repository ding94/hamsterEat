<?php
namespace backend\models;

use Yii;
use yii\data\ActiveDataProvider;
use common\models\Deliveryman;

class DeliveryManSearch extends Deliveryman
{
	public $status;
	public $username;
	public $timeApplied;
	public $timeApprove;

	public function rules()
    {
        return [
        	[['status','timeApprove','timeApplied'],'number'],
            [['username'] ,'safe'],
        ];
    }

	public function search($params)
	{
		$query = Deliveryman::find();
		$query->joinWith(['user']);
		$dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);

        if(!empty($this->timeApplied))
        {
        	$date = explode("to", $this->timeApplied);
        	
        	$first = strtotime($date[0]. ' 00:00:00');
        	$last = empty($data[1]) ? strtotime($date[0]. ' 23:59:59') : strtotime($date[1]. ' 23:59:59');
        	$query->andWhere(['between','DeliveryMan_DateTimeApplied',$first,$last]);
        }

        if(!empty($this->timeApprove))
        {
        	$date = explode("to", $this->timeApprove);
        	
        	$first = strtotime($date[0]. ' 00:00:00');
        	$last = empty($data[1]) ? strtotime($date[0]. ' 23:59:59') : strtotime($date[1]. ' 23:59:59');
        	$query->andWhere(['between','DeliveryMan_DateTimeApproved',$first,$last]);
        }

        $query->andFilterWhere([
        	'DeliveryMan_Approval'=>$this->status,
        ]);
        $query->andFilterWhere(['like','user.username',$this->username]);

        return $dataProvider;
	}
}