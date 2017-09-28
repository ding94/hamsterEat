<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "ordersstatuschange".
 *
 * @property integer $Delivery_ID
 * @property integer $OChange_PendingDateTime
 * @property integer $OChange_PreparingDateTime
 * @property integer $OChange_PickUpInProcessDateTime
 * @property integer $OChange_OnTheWayDateTime
 * @property integer $OChange_CompletedDateTime
 */
class Ordersstatuschange extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'ordersstatuschange';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['Delivery_ID', 'OChange_PendingDateTime', 'OChange_PreparingDateTime', 'OChange_PickUpInProcessDateTime', 'OChange_OnTheWayDateTime', 'OChange_CompletedDateTime'], 'integer'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'Delivery_ID' => 'Delivery  ID',
            'OChange_PendingDateTime' => 'Ochange  Pending Date Time',
            'OChange_PreparingDateTime' => 'Ochange  Preparing Date Time',
            'OChange_PickUpInProcessDateTime' => 'Ochange  Pick Up In Process Date Time',
            'OChange_OnTheWayDateTime' => 'Ochange  On The Way Date Time',
            'OChange_CompletedDateTime' => 'Ochange  Completed Date Time',
        ];
    }
}
