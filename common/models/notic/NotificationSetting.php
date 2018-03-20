<?php

namespace common\models\notic;

use Yii;

/**
 * This is the model class for table "notification_setting".
 *
 * @property int $id
 * @property int $tid notification_type id
 * @property int $setting_type_id notification setting type
 * @property int $sid status id
 * @property string $description
 * @property string $enable 0=> turn off 1=>turn on 2=>admin on
 *
 * @property NotifcationType $t
 * @property NotificationSettingType $settingType
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
            [['tid', 'setting_type_id', 'sid', 'description'], 'required'],
            [['tid', 'setting_type_id', 'sid'], 'integer'],
            [['description', 'enable'], 'string'],
            [['tid'], 'exist', 'skipOnError' => true, 'targetClass' => NotifcationType::className(), 'targetAttribute' => ['tid' => 'id']],
            [['setting_type_id'], 'exist', 'skipOnError' => true, 'targetClass' => NotificationSettingType::className(), 'targetAttribute' => ['setting_type_id' => 'id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'tid' => 'Tid',
            'setting_type_id' => 'Setting Type ID',
            'sid' => 'Sid',
            'description' => 'Description',
            'enable' => 'Enable',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getT()
    {
        return $this->hasOne(NotifcationType::className(), ['id' => 'tid']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSettingType()
    {
        return $this->hasOne(NotificationSettingType::className(), ['id' => 'setting_type_id']);
    }
}
