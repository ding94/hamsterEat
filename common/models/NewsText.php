<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "news_text".
 *
 * @property int $id
 * @property int $nid
 * @property string $language
 * @property string $text
 */
class NewsText extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'news_text';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['nid', 'language','name', 'text'], 'required'],
            [['nid'], 'integer'],
            [['language','name', 'text'], 'string'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'nid' => 'Nid',
            'language' => 'Language',
            'text' => 'Text',
        ];
    }
}
