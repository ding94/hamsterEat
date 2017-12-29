<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "restauranttypejunction".
 *
 * @property integer $ID
 * @property integer $Restaurant_ID
 * @property integer $Type_ID
 */
class Restauranttypejunction extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'restauranttypejunction';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['Restaurant_ID', 'Type_ID'], 'required'],
            [['Restaurant_ID', 'Type_ID'], 'integer'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'ID' => 'ID',
            'Restaurant_ID' => 'Restaurant  ID',
            'Type_ID' => 'Type  ID',
        ];
    }

    public function getRestauranttype()
    {
        return $this->hasOne(Restauranttype::className(),['ID' =>'Type_ID']);
    }
}
