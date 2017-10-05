<?php

namespace common\models\Rating;

use Yii;

/**
 * This is the model class for table "rating_status".
 *
 * @property integer $id
 * @property string $title
 * @property string $labelName
 */
class RatingStatus extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'rating_status';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'title', 'labelName'], 'required'],
            [['id'], 'integer'],
            [['title', 'labelName'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'title' => 'Title',
            'labelName' => 'Label Name',
        ];
    }
}
