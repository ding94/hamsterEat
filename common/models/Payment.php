<?php

namespace common\models;

use Yii;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "payment".
 *
 * @property int $id
 * @property int $uid
 * @property double $paid_amount
 * @property string $paid_type 1=> account balance,2=> fpx
 * @property int $item_id currently only delivery id
 * @property int $created_at
 * @property int $updated_at
 */
class Payment extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'payment';
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
            [['uid', 'paid_amount', 'paid_type', 'item_id'], 'required'],
            [['uid', 'item_id'], 'integer'],
            [['paid_amount'], 'number'],
            [['paid_type'], 'string'],
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
            'paid_amount' => 'Paid Amount',
            'paid_type' => 'Paid Type',
            'item_id' => 'Item ID',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }
}
