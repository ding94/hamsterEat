<?php

namespace common\models\food;

use Yii;

/**
 * This is the model class for table "foodtype".
 *
 * @property integer $ID
 * @property integer $Food_ID
 * @property string $Type_Desc
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
            [['Type_Desc'], 'required'],
            [['Type_Desc'], 'string'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'ID' => 'ID',
            'Type_Desc' => 'Type  Desc',
        ];
    }

    public function getFood()
    {
        return $this->hasMany(Food::className(), ['Food_ID'=>'Food_ID'])->viaTable('foodtypejunction', ['Type_ID'=>'ID']);
    }
}
