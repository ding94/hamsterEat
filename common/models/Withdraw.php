<?php

namespace common\models;

use Yii;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;
use yii\data\ActiveDataProvider;

/**
 * This is the model class for table "withdraw".
 *
 * @property integer $id
 * @property integer $uid
 * @property string $withdraw_amount
 * @property integer $action
 * @property string $inCharge
 * @property string $reason
 * @property string $acc_name
 * @property integer $to_bank
 * @property string $bank_name
 * @property string $from_bank
 * @property integer $created_at
 * @property integer $updated_at
 */
class Withdraw extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'withdraw';
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
            [['uid', 'withdraw_amount', 'acc_name', 'to_bank'], 'required'],
            [[ 'reason'], 'string'],
            [['uid', 'action', 'inCharge','to_bank', 'created_at', 'updated_at'], 'integer'],
            [['withdraw_amount'], 'number','min'=>1],
            [['bank_name', 'from_bank'], 'string', 'max' => 255],
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
            'withdraw_amount' => 'Withdraw Amount',
            'action' => 'Action',
            'inCharge' => 'In Charge',
            'reason' => 'Reason',
            'acc_name' => 'Acc Name',
            'to_bank' => 'To Bank',
            'bank_name' => 'Bank Name',
            'from_bank' => 'From Bank',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }
}
