<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "orderitemstatuschange".
 *
 * @property integer $Order_ID
 * @property integer $Change_PendingDateTime
 * @property integer $Change_PreparingDateTime
 * @property integer $Change_ReadyForPickUpDateTime
 * @property integer $Change_PickedUpDateTime
 */
class Orderitemstatuschange extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'orderitemstatuschange';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['Order_ID', 'Change_PendingDateTime', 'Change_PreparingDateTime', 'Change_ReadyForPickUpDateTime', 'Change_PickedUpDateTime'], 'integer'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'Order_ID' => 'Order  ID',
            'Change_PendingDateTime' => 'Change  Pending Date Time',
            'Change_PreparingDateTime' => 'Change  Preparing Date Time',
            'Change_ReadyForPickUpDateTime' => 'Change  Ready For Pick Up Date Time',
            'Change_PickedUpDateTime' => 'Change  Picked Up Date Time',
        ];
    }
}
