<?php

namespace common\models\PaymentGateWay;

use Yii;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;
use common\models\Payment;

/**
 * This is the model class for table "payment_gateway_history".
 *
 * @property string $collect_id collect id from billplz api
 * @property string $bill_id bill id from billplz api
 * @property int $pid payment id
 * @property int $status
 * @property int $created_at
 * @property int $updated_at
 *
 * @property Payment $p
 */
class PaymentGatewayHistory extends ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'payment_gateway_history';
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
            [['collect_id', 'bill_id', 'pid', 'status'], 'required'],
            [['pid', 'status', 'created_at', 'updated_at'], 'integer'],
            [['collect_id', 'bill_id'], 'string', 'max' => 25],
            [['collect_id', 'bill_id'], 'unique', 'targetAttribute' => ['collect_id', 'bill_id']],
            [['pid'], 'exist', 'skipOnError' => true, 'targetClass' => Payment::className(), 'targetAttribute' => ['pid' => 'id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'collect_id' => 'Collect ID',
            'bill_id' => 'Bill ID',
            'pid' => 'Pid',
            'status' => 'Status',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getP()
    {
        return $this->hasOne(Payment::className(), ['id' => 'pid']);
    }
}
