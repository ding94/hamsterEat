<?php

namespace common\models;

use Yii;
use yii\data\ActiveDataProvider;

/**
 * This is the model class for table "banner".
 *
 * @property integer $bannerid
 * @property string $name
 * @property string $title
 * @property string $redirectUrl
 * @property string $startTime
 * @property string $endTime
 */
class Banner extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'banner';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name', 'startTime', 'endTime'], 'required'],
            [['startTime', 'endTime'], 'safe'],
            [['name'], 'string', 'max' => 50],
            [['title', 'redirectUrl'], 'string', 'max' => 55],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'bannerid' => 'Bannerid',
            'name' => 'Name',
            'title' => 'Title',
            'redirectUrl' => 'Redirect Url',
            'startTime' => 'Start Time',
            'endTime' => 'End Time',
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
