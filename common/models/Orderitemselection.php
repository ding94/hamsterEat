<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "orderitemselection".
 *
 * @property integer $OrderItemSelection_ID
 * @property integer $Order_ID
 * @property integer $FoodType_ID
 * @property integer $Selection_ID
 */
class Orderitemselection extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'orderitemselection';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
           
            [['Order_ID', 'FoodType_ID', 'Selection_ID'], 'integer'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'OrderItemSelection_ID' => 'Order Item Selection  ID',
            'Order_ID' => 'Order  ID',
            'FoodType_ID' => 'Food Type  ID',
            'Selection_ID' => 'Selection  ID',
        ];
    }
}
