<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "foodtype".
 *
 * @property integer $FoodType_ID
 * @property integer $Food_ID
 * @property string $Selection_Type
 */
class Foodtype extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'foodtype';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['Food_ID', 'Selection_Type'], 'required'],
            [['Food_ID'], 'integer'],
            [['Selection_Type'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'FoodType_ID' => 'Food Type  ID',
            'Food_ID' => 'Food  ID',
            'Selection_Type' => 'Selection  Type',
        ];
    }
}
