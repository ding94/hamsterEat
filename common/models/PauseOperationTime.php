<?php

namespace common\models;
use yii\data\ActiveDataProvider;

use Yii;

/**
 * This is the model class for table "pause_operation_time".
 *
 * @property int $id
 * @property string $symbol
 * @property string $date_format
 * @property int $time
 */
class PauseOperationTime extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'pause_operation_time';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['symbol', 'date_format', 'time'], 'required'],
            [['time'], 'integer'],
            [['symbol', 'date_format'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'symbol' => 'Symbol',
            'date_format' => 'Date Format',
            'time' => 'Time',
        ];
    }

    public function search($params)
    {
        $query = self::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);
        
        $this->load($params);

        $query
        ->andFilterWhere(['like','time' , $this->getAttribute('time')]);

        return $dataProvider;
    }
}
