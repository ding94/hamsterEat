<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "foodselection".
 *
 * @property integer $Selection_ID
 * @property integer $Food_ID
 * @property string $Selection_Name
 * @property string $Selection_Type
 * @property double $Selection_Price
 * @property string $Selection_Desc
 */
class Foodselection extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'foodselection';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['Selection_ID', 'Food_ID', 'Selection_Name', 'Selection_Type', 'Selection_Price', 'Selection_Desc'], 'required'],
            [['Selection_ID', 'Food_ID'], 'integer'],
            [['Selection_Price'], 'number'],
            [['Selection_Name', 'Selection_Type', 'Selection_Desc'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'Selection_ID' => 'Selection  ID',
            'Food_ID' => 'Food  ID',
            'Selection_Name' => 'Selection  Name',
            'Selection_Type' => 'Selection  Type',
            'Selection_Price' => 'Selection  Price',
            'Selection_Desc' => 'Selection  Desc',
        ];
    }
}
