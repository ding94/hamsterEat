<?php

namespace common\models\Account;

use Yii;

/**
 * This is the model class for table "account_force".
 *
 * @property integer $id
 * @property integer $uid
 * @property double $amount
 * @property integer $reduceOrPlus
 * @property string $reason
 * @property integer $adminid
 * @property integer $create_at
 * @property integer $updated_at
 */
class AccountForce extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'account_force';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['uid', 'amount', 'reduceOrPlus', 'reason', 'adminid', 'create_at', 'updated_at'], 'required'],
            [['uid', 'reduceOrPlus', 'adminid', 'create_at', 'updated_at'], 'integer'],
            [['amount'], 'number'],
            [['reason'], 'string', 'max' => 255],
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
            'amount' => 'Amount',
            'reduceOrPlus' => 'Reduce Or Plus',
            'reason' => 'Reason',
            'adminid' => 'Adminid',
            'create_at' => 'Create At',
            'updated_at' => 'Updated At',
        ];
    }
}
