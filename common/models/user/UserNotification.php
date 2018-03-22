<?php

namespace common\models\user;

use Yii;
use common\models\notic\NotificationSetting;

/**
 * This is the model class for table "user_notification".
 *
 * @property int $uid
 * @property int $setting_id
 * @property string $enable
 *
 * @property NotificationSetting $setting
 */
class UserNotification extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'user_notification';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['uid', 'setting_id', 'enable'], 'required'],
            [['uid', 'setting_id'], 'integer'],
            [['enable'], 'string'],
            [['uid', 'setting_id'], 'unique', 'targetAttribute' => ['uid', 'setting_id']],
            [['setting_id'], 'exist', 'skipOnError' => true, 'targetClass' => NotificationSetting::className(), 'targetAttribute' => ['setting_id' => 'id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'uid' => 'Uid',
            'setting_id' => 'Setting ID',
            'enable' => 'Enable',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSetting()
    {
        return $this->hasOne(NotificationSetting::className(), ['id' => 'setting_id']);
    }
}
