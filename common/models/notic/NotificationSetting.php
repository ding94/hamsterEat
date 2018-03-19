<?php

namespace common\models\notic;

use Yii;

/**
 * This is the model class for table "notification_setting".
 *
 * @property int $type
 * @property int $id
 * @property string $description
 * @property int $enable_notification 0=> turn off 1=>turn on
 * @property int $enable_email 0=> turn off 1=>turn on
 * @property int $enamble_sms 0=> turn off 1=>turn on
 */
class NotificationSetting extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'notification_setting';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['type', 'id', 'description'], 'required'],
            [['type', 'id', 'enable_notification', 'enable_email', 'enamble_sms'], 'integer'],
            [['description'], 'string'],
            [['type', 'id'], 'unique', 'targetAttribute' => ['type', 'id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'type' => 'Type',
            'id' => 'ID',
            'description' => 'Description',
            'enable_notification' => 'Enable Notification',
            'enable_email' => 'Enable Email',
            'enamble_sms' => 'Enamble Sms',
        ];
    }
}
