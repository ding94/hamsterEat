<?php

namespace common\models;

use Yii;
use yii\db\ActiveRecord;
use yii\data\ActiveDataProvider;

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
            [['rest_day_name','start_time','end_time'], 'required'],
            [['rest_day_name'], 'string'],
            [['start_time','end_time'],'date','on'=>['input']],
            [['start_time','end_time'],'integer','on'=>['save']],
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
            'start_time' => 'Start Time',
            'end_time' => 'End Time',
        ];
    }

    public function search($params,$action)
    {
        $query = self::find();
        
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);

        return $dataProvider;
    }
}
