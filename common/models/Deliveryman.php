<?php

namespace common\models;

use Yii;


/**
 * This is the model class for table "deliveryman".
 *
 * @property string $User_id
 * @property string $DeliveryMan_CarPlate
 * @property int $DeliveryMan_LicenseNo
 * @property int $DeliveryMan_Approval
 * @property int $DeliveryMan_Assignment
 * @property int $DeliveryMan_AreaGroup
 * @property string $DeliveryMan_VehicleType
 * @property int $DeliveryMan_DateTimeApplied
 * @property int $DeliveryMan_DateTimeApproved
 */
class Deliveryman extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'deliveryman';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['User_id', 'DeliveryMan_CarPlate', 'DeliveryMan_LicenseNo', 'DeliveryMan_AreaGroup', 'DeliveryMan_VehicleType', 'DeliveryMan_DateTimeApplied'], 'required'],
            [['User_id','DeliveryMan_LicenseNo', 'DeliveryMan_Approval', 'DeliveryMan_Assignment', 'DeliveryMan_AreaGroup', 'DeliveryMan_DateTimeApplied', 'DeliveryMan_DateTimeApproved'], 'integer'],
            [['DeliveryMan_CarPlate', 'DeliveryMan_VehicleType'], 'string', 'max' => 255],
            [['User_id'], 'unique'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'User_id' => 'User ID',
            'DeliveryMan_CarPlate' => 'Delivery Man  Car Plate',
            'DeliveryMan_LicenseNo' => 'Delivery Man  License No',
            'DeliveryMan_Approval' => 'Delivery Man  Approval',
            'DeliveryMan_Assignment' => 'Delivery Man  Assignment',
            'DeliveryMan_AreaGroup' => 'Delivery Man  Area Group',
            'DeliveryMan_VehicleType' => 'Delivery Man  Vehicle Type',
            'DeliveryMan_DateTimeApplied' => 'Delivery Man  Date Time Applied',
            'DeliveryMan_DateTimeApproved' => 'Delivery Man  Date Time Approved',
        ];
    }
}
