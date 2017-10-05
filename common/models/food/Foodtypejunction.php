<?php

namespace common\models\food;

use Yii;

/**
 * This is the model class for table "foodtypejunction".
 *
 * @property integer $ID
 * @property integer $Food_ID
 * @property integer $Type_ID
 */
class Foodtypejunction extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'foodtypejunction';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['Food_ID', 'Type_ID'], 'required'],
            [['Food_ID', 'Type_ID'], 'integer'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'ID' => 'ID',
            'Food_ID' => 'Food  ID',
            'Type_ID' => 'Type  ID',
        ];
    }
}
