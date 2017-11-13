<?php

namespace common\models\user;

use Yii;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "useraddress".
 *
 * @property integer $id
 * @property integer $uid
 * @property string $address
 * @property integer $postcode
 * @property string $city
 * @property string $state
 * @property string $country
 * @property integer $level
 * @property integer $created_at
 * @property integer $updated_at
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

    public function behaviors()
    {
        return [
            TimestampBehavior::className(),
            'timestamp' => [
                'class' => 'yii\behaviors\TimestampBehavior',
                'attributes' => [
                    ActiveRecord::EVENT_BEFORE_INSERT => ['created_at','updated_at'],
                    ActiveRecord::EVENT_BEFORE_UPDATE => ['updated_at'],
                ],
            ],   
        ];
    } 

    public function getFullAddress()
    {
        return $this->address .", ". $this->city .", ". $this->state ;
    }  

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['uid', 'address', 'postcode', 'city','recipient','contactno'], 'required'],
            [['uid', 'postcode', 'level', 'created_at', 'updated_at'], 'integer'],
            [['address'], 'string'],
            [['city', 'state', 'country'], 'string', 'max' => 50],
            ['country', 'default', 'value' => 'Malaysia'],
            ['state','default','value' => 'Johor'],
            ['level','default','value' => '0'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'uid' => 'Uid',
            'address' => 'Address',
            'postcode' => 'Postcode',
            'city' => 'City',
            'state' => 'State',
            'country' => 'Country',
            'level' => 'Level',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }
}
