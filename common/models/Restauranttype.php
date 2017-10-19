<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "restauranttype".
 *
 * @property integer $ID
 * @property string $Type_Name
 */
class Restauranttype extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'restauranttype';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['Type_Name'], 'required'],
            [['Type_Name'], 'string'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'ID' => 'ID',
            'Type_Name' => 'Type  Name',
        ];
    }
}
