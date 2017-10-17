<?php

namespace common\models\Package;

use Yii;

/**
 * This is the model class for table "user_package".
 *
 * @property integer $id
 * @property integer $uid
 * @property integer $type
 * @property string $subscribe_time
 * @property string $end_period
 * @property integer $sub_period
 * @property integer $created_at
 * @property integer $updated_at
 */
class UserPackage extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'user_package';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['uid', 'type', 'subscribe_time', 'end_period', 'sub_period', 'created_at', 'updated_at'], 'required'],
            [['uid', 'type', 'sub_period', 'created_at', 'updated_at'], 'integer'],
            [['subscribe_time', 'end_period'], 'safe'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'uid' => 'Uid',
            'type' => 'Type',
            'subscribe_time' => 'Subscribe Time',
            'end_period' => 'End Period',
            'sub_period' => 'Sub Period',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }
}
