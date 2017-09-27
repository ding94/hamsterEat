<?php

namespace frontend\models;

use Yii;

/**
 * This is the model class for table "deliveryman".
 *
 * @property integer $User_id
 * @property string $DeliveryMan_CarPlate
 * @property integer $DeliveryMan_LicenseNo
 * @property integer $DeliveryMan_Approval
 * @property integer $DeliveryMan_Assignment
 * @property string $User_Firstname
 * @property string $User_Lastname
 * @property string $DeliveryMan_VehicleType
 * @property integer $DeliveryMan_DateTimeApplied
 * @property integer $DeliveryMan_DateTimeApproved
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
            [['User_id'], 'required'],
            [['User_id', 'DeliveryMan_LicenseNo', 'DeliveryMan_Approval', 'DeliveryMan_Assignment', 'DeliveryMan_DateTimeApplied', 'DeliveryMan_DateTimeApproved'], 'integer'],
            [['DeliveryMan_CarPlate', 'User_Firstname', 'User_Lastname', 'DeliveryMan_VehicleType'], 'string', 'max' => 255],
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
            'User_Firstname' => 'User  Firstname',
            'User_Lastname' => 'User  Lastname',
            'DeliveryMan_VehicleType' => 'Delivery Man  Vehicle Type',
            'DeliveryMan_DateTimeApplied' => 'Delivery Man  Date Time Applied',
            'DeliveryMan_DateTimeApproved' => 'Delivery Man  Date Time Approved',
        ];
    }
}
