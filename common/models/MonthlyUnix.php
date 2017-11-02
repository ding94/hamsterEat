<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "monthly_unix".
 *
 * @property integer $ID
 * @property string $Month
 * @property integer $Year
 * @property integer $FirstDay
 * @property integer $LastDay
 */
class MonthlyUnix extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'monthly_unix';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['Month', 'Year', 'FirstDay', 'LastDay'], 'required'],
            [['Month'], 'string'],
            [['Year', 'FirstDay', 'LastDay'], 'integer'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'ID' => 'ID',
            'Month' => 'Month',
            'Year' => 'Year',
            'FirstDay' => 'First Day',
            'LastDay' => 'Last Day',
        ];
    }
}
