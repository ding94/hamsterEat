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

        $query->andFilterWhere([
        	'notification_type.id'=>$this->type,
        ]);

  		$query->andFilterWhere(['like','number',$this->number]);

		return $dataProvider;
	}
}