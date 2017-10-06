<?php

namespace common\models;

use Yii;
use yii\helpers\ArrayHelper;
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
            [['Area_Postcode','Area_ID'], 'integer'],
            [['Area_Area', 'Area_State'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'Area_ID' => 'Area  ID',
            'Area_Postcode' => 'Area  Postcode',
            'Area_Area' => 'Area ',
            'Area_State' => 'Area  State',
        ];
    }

    public function getAllstate()
    {
        return ArrayHelper::map(self::find()->all(),'Area_State','Area_State');
    }
}
