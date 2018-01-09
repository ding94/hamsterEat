<?php

namespace common\models\food;

use Yii;

/**
 * This is the model class for table "foodstatus".
 *
 * @property integer $ID
 * @property integer $Food_ID
 * @property integer $Status
 * @property integer $StartTime
 * @property integer $StopTime
 */
class Foodstatus extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'foodstatus';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['Food_ID', 'Status'], 'required'],
            [['Food_ID', 'Status', 'StartTime', 'StopTime'], 'integer'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'ID' => 'ID',
            'Food_ID' => 'Food  ID',
            'Status' => 'Status',
            'StartTime' => 'Start Time',
            'StopTime' => 'Stop Time',
        ];
    }

    public function search($params)
    {
        $query = self::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);

        return $dataProvider;

    }
}
