<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "notification_setting".
 *
 * @property integer $id
 * @property string $description
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
            [['description','url'], 'required'],
            [['description','url'], 'string', 'max' => 50],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'description' => 'Desciption',
        ];
    }
}
