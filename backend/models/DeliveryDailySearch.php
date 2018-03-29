<?php
namespace backend\models;

use Yii;
use yii\data\ActiveDataProvider;
use common\models\DeliveryAttendence;

class DeliveryDailySearch extends DeliveryAttendence
{
	public function attributes()
    {
        return array_merge(parent::attributes(),['user.username']);
    }

	public function rules()
    {
        return [
            [['user.username'] ,'safe'],
        ];
    }

	public function search($params,$month)
	{
		$query = DeliveryAttendence::find()->where('month = :month',[':month' => $month]);

		$dataProvider = new ActiveDataProvider([
			'query' => $query,	
			'sort'=> ['defaultOrder' => ['updated_at'=>SORT_DESC]]
		]);
		
		$dataProvider->sort->attributes['user.username'] = [
            'asc'=>['username'=>SORT_ASC],
            'desc'=>['username'=>SORT_DESC],
        ];
		$query->joinWith(['user']);

		$this->load($params);
	
		return $dataProvider;
	}

}