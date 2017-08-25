<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "area".
 *
 * @property integer $area_ID
 * @property integer $Area_Postcode
 * @property string $Area_Area
 * @property string $Area_State
 */
class Area extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */

    public $detectArea= "";
    public static function tableName()
    {
        return 'area';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['Area_Postcode'], 'required'],
            [['Area_Postcode'], 'integer'],
            [['Area_Area', 'Area_State'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'area_ID' => 'Area  ID',
            'Area_Postcode' => 'Area  Postcode',
            'Area_Area' => 'Area ',
            'Area_State' => 'Area  State',
        ];
    }
}
