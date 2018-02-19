<?php

namespace common\models\food;

use Yii;

/**
 * This is the model class for table "foodSelectionName".
 *
 * @property int $id
 * @property string $language
 * @property string $translation
 */
class FoodSelectionName extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'foodSelectionName';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['language', 'translation'], 'required'],
            [['id'], 'integer'],
            ['id','required','on'=>'copy'],
            [['translation'], 'string'],
            [['language'], 'string', 'max' => 16],
            [['id', 'language'], 'unique', 'targetAttribute' => ['id', 'language']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'language' => 'Language',
            'translation' => 'Selection Name',
        ];
    }
}
