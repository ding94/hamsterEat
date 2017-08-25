<?php

namespace common\models\user;

use Yii;

/**
 * This is the model class for table "useraddress".
 *
 * @property string $User_Username
 * @property integer $User_Postcode1
 * @property string $User_Area1
 * @property string $User_Street1
 * @property string $User_HouseNo1
 * @property integer $User_Postcode2
 * @property string $User_Area2
 * @property string $User_Street2
 * @property string $User_HouseNo2
 * @property integer $User_Postcode3
 * @property string $User_Area3
 * @property string $User_Street3
 * @property string $User_HouseNo3
 */
class Useraddress extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'useraddress';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['User_Postcode1', 'User_Postcode2', 'User_Postcode3'], 'integer'],
            [['User_Username', 'User_Area1', 'User_Street1', 'User_HouseNo1', 'User_Area2', 'User_Street2', 'User_HouseNo2', 'User_Area3', 'User_Street3', 'User_HouseNo3'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'User_Username' => 'User  Username',
            'User_Postcode1' => 'User  Postcode1',
            'User_Area1' => 'User  Area1',
            'User_Street1' => 'User  Street1',
            'User_HouseNo1' => 'User  House No1',
            'User_Postcode2' => 'User  Postcode2',
            'User_Area2' => 'User  Area2',
            'User_Street2' => 'User  Street2',
            'User_HouseNo2' => 'User  House No2',
            'User_Postcode3' => 'User  Postcode3',
            'User_Area3' => 'User  Area3',
            'User_Street3' => 'User  Street3',
            'User_HouseNo3' => 'User  House No3',
        ];
    }
}
