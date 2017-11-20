<?php

namespace common\models;

use Yii;
use common\models\User;
use frontend\models\Deliveryman;
use yii\helpers\Json;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;
/**
 * This is the model class for table "delivery_attendence".
 *
 * @property integer $id
 * @property integer $uid
 * @property string $day
 * @property string $month
 */
class DeliveryAttendence extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'delivery_attendence';
    }

    public function behaviors()
    {
        return [
            TimestampBehavior::className(),
            'timestamp' => [
                'class' => 'yii\behaviors\TimestampBehavior',
                'attributes' => [
                    ActiveRecord::EVENT_BEFORE_INSERT => ['created_at', 'updated_at'],
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
            [['uid', 'day', 'month'], 'required'],
            [['uid','created_at','updated_at'], 'integer'],
            [['day'], 'string'],
            [['month'], 'string', 'max' => 30],
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
            'day' => 'Day',
            'month' => 'Month',
        ];
    }

    public function getDeliveryman()
    {
        return $this->hasOne(Deliveryman::className(),['User_id'=>'uid']);
    }

    public function getUser()
    {
        return $this->hasOne(User::className(),['id' => 'uid']);
    }

    public function getTodaySign($data,$date)
    {
       $data = json_decode($data);

       $result = $data->$date->result;
       if($result == 1)
       {
            return "Already Sign In";
       }
       else
        {
             return "Not Sign In";
        }
      
    }

}
