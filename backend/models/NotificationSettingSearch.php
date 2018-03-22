<?php
namespace backend\models;

use yii\data\ActiveDataProvider;
use common\models\notic\NotificationSetting;

Class NotificationSettingSearch extends NotificationSetting
{
	public $type;
	public $status;
	public $notificationType;
	public $enable;

	public function rules()
	{
		return[
			[['type','status','notificationType','enable'],'integer'],
		];
	}

	public function search($params)
	{
		$query = NotificationSetting::find()->joinWith(['t','s','settingType']);

		$dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);

        $query->andFilterWhere([
        	'notification_type.id'=>$this->type,
        	'status_type.id'=>$this->status,
        	'notification_setting_type.id'=>$this->notificationType,
        	'enable'=>$this->enable,
        ]);

		return $dataProvider;
	}
}