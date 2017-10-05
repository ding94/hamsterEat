<?php

namespace common\models\Rating;

use Yii;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;
use common\models\Rating\RatingStatus;
/**
 * This is the model class for table "servicerating".
 *
 * @property integer $delivery_id
 * @property integer $DeliverySpeed
 * @property integer $Service
 * @property integer $UserExperience
 * @property integer $User_Id
 * @property string $Comment
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
            [['delivery_id', 'DeliverySpeed', 'Service', 'UserExperience', 'User_Id'], 'required'],
            [['delivery_id', 'DeliverySpeed', 'Service', 'UserExperience', 'User_Id', 'created_at', 'updated_at'], 'integer'],
            [['Comment'], 'string'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'delivery_id' => 'Delivery ID',
            'DeliverySpeed' => 'Delivery Speed',
            'Service' => 'Service',
            'UserExperience' => 'User Experience',
            'User_Id' => 'User  ID',
            'Comment' => 'Comment',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }
}
