<?php

namespace common\models\notic;

use Yii;

/**
 * This is the model class for table "notification_setting_type".
 *
 * @property int $id
 * @property string $description
 *
 * @property NotificationSetting[] $notificationSettings
 */
class NotificationSettingType extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'notification_setting_type';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'description'], 'required'],
            [['id'], 'integer'],
            [['description'], 'string', 'max' => 50],
            [['id'], 'unique'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'description' => 'Description',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getNotificationSettings()
    {
        return $this->hasMany(NotificationSetting::className(), ['setting_type_id' => 'id']);
    }
}
