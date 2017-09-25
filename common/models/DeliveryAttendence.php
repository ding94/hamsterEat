<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "delivery_attendence".
 *
 * @property integer $id
 * @property integer $uid
 * @property string $day
 * @property string $month
 */
class DeliveryAttendence extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'delivery_attendence';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['uid', 'day', 'month'], 'required'],
            [['uid'], 'integer'],
            [['day'], 'string'],
            [['month'], 'string', 'max' => 30],
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
            'day' => 'Day',
            'month' => 'Month',
        ];
    }
}
