<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "expansion".
 *
 * @property string $User_Username
 * @property integer $Expansion_Postcode
 * @property string $Expansion_Area
 * @property integer $Expansion_DateTime
 */
class Expansion extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'expansion';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['Expansion_Postcode', 'Expansion_DateTime'], 'string', 'max' => 5],
            [['User_Username', 'Expansion_Area'], 'string', 'max' => 50],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'User_Username' => 'User  Username',
            'Expansion_Postcode' => 'Postcode',
            'Expansion_Area' => 'Area',
            'Expansion_DateTime' => 'Expansion  Date Time',
        ];
    }
}
