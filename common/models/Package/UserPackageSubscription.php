<?php

namespace common\models\Package;

use Yii;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "user_package_subscription".
 *
 * @property integer $created_at
 * @property integer $updated_at
 * @property integer $pid
 * @property string $subscribe_time
 * @property integer $sub_period
 * @property string $end_period
 * @property string $next_payment
 */
class UserPackageSubscription extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'user_package_subscription';
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
            [[ 'pid', 'subscribe_time', 'sub_period', 'end_period', 'next_payment'], 'required'],
            [['created_at', 'updated_at', 'pid', 'sub_period'], 'integer'],
            [['subscribe_time', 'end_period', 'next_payment'], 'safe'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
            'pid' => 'Pid',
            'subscribe_time' => 'Subscribe Time',
            'sub_period' => 'Sub Period',
            'end_period' => 'End Period',
            'next_payment' => 'Next Payment',
        ];
    }
}
