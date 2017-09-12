<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "restaurant".
 *
 * @property integer $Restaurant_ID
 * @property string $Restaurant_Manager
 * @property string $Restaurant_Name
 * @property integer $Restaurant_Postcode
 * @property string $Restaurant_Area
 * @property string $Restaurant_Street
 * @property string $Restaurant_UnitNo
 * @property string $Restaurant_RestaurantPicPath
 * @property string $Restaurant_Tag
 * @property integer $Restaurant_Pricing
 * @property string $Restaurant_Status
 * @property string $Restaurant_LicenseNo
 * @property integer $Restaurant_Rating
 * @property integer $Restaurant_DateTimeCreated
 * @property integer $Restaurant_AreaGroup
 */
class Restaurant extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'restaurant';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['Restaurant_Name', 'Restaurant_Street', 'Restaurant_UnitNo', 'Restaurant_Tag', 'Restaurant_Pricing', 'Restaurant_LicenseNo'], 'required'],
            [['Restaurant_Postcode', 'Restaurant_Pricing', 'Restaurant_Rating', 'Restaurant_DateTimeCreated', 'Restaurant_AreaGroup'], 'integer'],
            [['Restaurant_Manager', 'Restaurant_RestaurantPicPath', 'Restaurant_Name', 'Restaurant_Status', 'Restaurant_LicenseNo'], 'string', 'max' => 255],
            [['Restaurant_Area', 'Restaurant_Street', 'Restaurant_UnitNo'], 'string', 'max' => 50],
            [['Restaurant_Tag'],'safe']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'Restaurant_ID' => 'Restaurant  ID',
            'Restaurant_Manager' => 'Restaurant  Manager',
            'Restaurant_Name' => 'Restaurant  Name',
            'Restaurant_Postcode' => 'Restaurant  Postcode',
            'Restaurant_Area' => 'Restaurant  Area',
            'Restaurant_Street' => 'Restaurant  Street',
            'Restaurant_UnitNo' => 'Restaurant  Unit No',
            'Restaurant_RestaurantPicPath' => 'Restaurant  Restaurant Pic Path',
            'Restaurant_Tag' => 'Restaurant  Tag',
            'Restaurant_Pricing' => 'Restaurant  Pricing',
            'Restaurant_Status' => 'Restaurant  Status',
            'Restaurant_LicenseNo' => 'Restaurant  License No',
            'Restaurant_Rating' => 'Restaurant  Rating',
            'Restaurant_DateTimeCreated' => 'Restaurant  Date Time Created',
            'Restaurant_AreaGroup' => 'Restaurant  Area Group',
        ];
    }
}
