<?php

namespace common\models\translate;

use Yii;

/**
 * This is the model class for table "sentences_source".
 *
 * @property int $id
 * @property string $category
 * @property string $message
 *
 * @property Sentences[] $sentences
 */
class SentencesSource extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'sentences_source';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['message'], 'string'],
            [['category'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'category' => 'Category',
            'message' => 'Message',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSentences()
    {
        return $this->hasMany(Sentences::className(), ['id' => 'id']);
    }
}
