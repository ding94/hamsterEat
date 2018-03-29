<?php
namespace backend\models;

use yii\data\ActiveDataProvider;
use common\models\sms\SmsLog;

Class SmsLogSearch extends SmsLog
{
	public $type;

	public function search($params)
	{
		$query = SmsLog::find()->joinWith(['noticType'])->orderBy(['id'=> SORT_DESC]);

		$dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);

       	if(!empty($this->created_at))
       	{
       		$date = explode("to", $this->created_at);
        	$first = strtotime($date[0]. ' 00:00:00');
        	$last = empty($data[1]) ? strtotime($date[0]. ' 23:59:59') : strtotime($date[1]. ' 23:59:59');
        	$query->andWhere(['between','created_at',$first,$last]);
       	}

        $query->andFilterWhere([
        	'notification_type.id'=>$this->type,
        	//'created_at' => strtotime($this->created_at),
        ]);

  		$query->andFilterWhere(['like','number',$this->number]);
  		$query->andFilterWhere(['like','number',$this->result]);


		return $dataProvider;
	}
}