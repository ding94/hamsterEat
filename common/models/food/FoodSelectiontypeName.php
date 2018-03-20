<?php

namespace common\models\food;

use Yii;

/**
 * This is the model class for table "foodSelectiontypeName".
 *
 * @property int $id
 * @property string $language
 * @property string $translation
 */
class FoodSelectiontypeName extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'foodSelectiontypeName';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['translation'], 'required','message'=>Yii::t('food','Type Name').Yii::t('common',' cannot be blank.')],
            [['id'], 'integer'],
            ['language','default','value'=>'en'],
            [['translation'], 'string'],
            [['language'], 'string', 'max' => 16],
            [['id','language'],'required','on'=>'copy'],
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
            'translation' => 'Type Name',
        ];
    }
}
