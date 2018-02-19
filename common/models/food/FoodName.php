<?php

namespace common\models\food;

use Yii;

/**
 * This is the model class for table "foodName".
 *
 * @property int $id Food id
 * @property string $language
 * @property string $translation
 */
class FoodName extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'foodName';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [[ 'language','translation'], 'required'],
            [['id'], 'integer'],
            [['translation'], 'string'],
            [['language'], 'string', 'max' => 16],
            ['id','required','on'=>'copy'],
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
            'translation' => 'Name',
        ];
    }
}
