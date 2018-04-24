<?php

namespace common\models;

use Yii;
use yii\data\ActiveDataProvider;

/**
 * This is the model class for table "news".
 *
 * @property integer $id
 * @property string $name
 * @property string $text
 * @property string $startTime
 * @property string $endTime
 */
class News extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'news';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['startTime', 'endTime'], 'safe'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'startTime' => 'Start Time',
            'endTime' => 'End Time',
        ];
    }

    public function search($params)
    {
        $query = self::find()->joinWith('enText','zhText');
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);
        return $dataProvider;
    }

    public function getNewsDate()
    {
        return date('Y-m-d', strtotime($this->startTime));
    }

    public function getEnText()
    {
        return $this->hasOne(NewsText::className(),['nid' =>'id'])->andWhere(['=','language','en']);
    }

    public function getZhText()
    {
        return $this->hasOne(NewsText::className(),['nid' =>'id'])->andWhere(['=','language','zh']);
    }
}
