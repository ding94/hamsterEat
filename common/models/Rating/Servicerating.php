<?php

namespace common\models\Rating;

use Yii;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;
/**
 * This is the model class for table "servicerating".
 *
 * @property integer $delivery_id
 * @property integer $ServiceRating_DeliverySpeed
 * @property integer $ServiceRating_Service
 * @property integer $ServiceRating_UserExperience
 * @property integer $User_Id
 * @property string $ServiceRating_Comment
 * @property integer $created_at
 * @property integer $updated_at
 */
class Servicerating extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'servicerating';
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

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['delivery_id', 'User_Id', 'ServiceRating_DeliverySpeed', 'ServiceRating_Service', 'ServiceRating_UserExperience'], 'required'],
            [['delivery_id', 'ServiceRating_DeliverySpeed', 'ServiceRating_Service', 'ServiceRating_UserExperience', 'User_Id', 'created_at', 'updated_at'], 'integer'],
            [['ServiceRating_Comment'], 'string'],
        
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'delivery_id' => 'Delivery ID',
            'ServiceRating_DeliverySpeed' => 'Delivery Speed',
            'ServiceRating_Service' => 'Service Provide',
            'ServiceRating_UserExperience' => 'User Experience',
            'User_Id' => 'User  ID',
            'ServiceRating_Comment' => 'Comment',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }
}
