<?php

namespace common\models\Company;

use Yii;

/**
 * This is the model class for table "company".
 *
 * @property integer $id
 * @property string $name
 * @property integer $owner_id
 * @property string $license_no
 * @property string $address
 * @property string $postcode
 * @property string $area
 * @property integer $status
 * @property string $created_at
 * @property integer $area_group
 */
class Company extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'company';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name', 'owner_id', 'license_no', 'address', 'postcode', 'area', 'created_at'], 'required'],
            [['owner_id', 'status', 'area_group'], 'integer'],
            [['created_at'], 'safe'],
            [['name', 'license_no', 'address', 'postcode', 'area'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => 'Name',
            'owner_id' => 'Owner ID',
            'license_no' => 'License No',
            'address' => 'Address',
            'postcode' => 'Postcode',
            'area' => 'Area',
            'status' => 'Status',
            'created_at' => 'Created At',
            'area_group' => 'Area Group',
        ];
    }
}
