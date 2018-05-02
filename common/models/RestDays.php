<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "rest_days".
 *
 * @property int $id
 * @property string $rest_day_name
 * @property int $month
 * @property int $date
 */
class RestDays extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'rest_days';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['rest_day_name', 'month', 'date'], 'required'],
            [['rest_day_name'], 'string'],
            [['month', 'date'], 'integer'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'rest_day_name' => 'Rest Day Name',
            'month' => 'Month',
            'date' => 'Date',
        ];
    }
}
