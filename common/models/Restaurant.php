<?php

namespace common\models;

use Yii;
use common\models\Area;
use common\models\Rmanager;
use common\models\Restauranttype;
use common\models\Restauranttypejunction;

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
            [['Restaurant_Name', 'Restaurant_Street', 'Restaurant_UnitNo', 'Restaurant_Pricing', 'Restaurant_LicenseNo','Restaurant_AreaGroup','approval'], 'required'],
            [['Restaurant_Postcode', 'Restaurant_Pricing', 'Restaurant_Rating', 'Restaurant_DateTimeCreated', 'Restaurant_AreaGroup','approval'], 'integer'],
            [['Restaurant_Manager', 'Restaurant_Name', 'Restaurant_RestaurantPicPath', 'Restaurant_Status', 'Restaurant_LicenseNo'], 'string', 'max' => 255],
            [['Restaurant_Area', 'Restaurant_Street', 'Restaurant_UnitNo'], 'string', 'max' => 50],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'Restaurant_ID' => 'ID',
            'Restaurant_Manager' => 'Restaurant Manager',
            'Restaurant_Name' => 'Name',
            'Restaurant_Postcode' => 'Restaurant  Postcode',
            'Restaurant_Area' => 'Restaurant  Area',
            'Restaurant_Street' => 'Restaurant  Street',
            'Restaurant_UnitNo' => 'Restaurant  Unit No',
            'Restaurant_RestaurantPicPath' => 'Restaurant  Restaurant Pic Path',
            'Restaurant_Pricing' => 'Restaurant  Pricing',
            'Restaurant_Status' => 'Restaurant  Status',
            'Restaurant_LicenseNo' => 'Restaurant  License No',
            'Restaurant_Rating' => 'Restaurant  Rating',
            'Restaurant_DateTimeCreated' => 'Restaurant  Date Time Created',
            'Restaurant_AreaGroup' => 'Restaurant  Area Group',
        ];
    }

    public function getArea()
    {
        return $this->hasOne(Area::className(),['Area_ID' => 'Restaurant_AreaGroup']);
    }

    public function getManager()
    {
        return $this->hasOne(Rmanager::className(),['User_Username' => 'Restaurant_Manager']);
    }

    public function getFulladdress() 
    {
        return $this->Restaurant_UnitNo .','. $this->Restaurant_Street .','. $this->Restaurant_Area .','. $this->Restaurant_Postcode;
    }

    public function getRestaurantType()
    {
        return $this->hasMany(Restauranttype::className(), ['ID'=>'Type_ID'])->viaTable('restauranttypejunction', ['Restaurant_ID'=>'Restaurant_ID']);
    }

    public function getRJunction()
    {
        return $this->hasMany(Restauranttypejunction::className(),['Restaurant_ID' => 'Restaurant_ID']);
    }
}
