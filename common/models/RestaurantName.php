<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "restaurant_name".
 *
 * @property int $id
 * @property string $language
 * @property string $translation
 */
class RestaurantName extends \yii\db\ActiveRecord
{
    public $en_name;
    public $zh_name;
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'restaurant_name';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['rid','language','en_name'], 'required'],
            [['rid'],'integer'],
            [['language', 'translation','en_name','zh_name'], 'string'],
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
            'en_name' => 'English Name',
            'zh_name' => 'Mandarin Name',
            'translation' => 'Translation',
        ];
    }
}
