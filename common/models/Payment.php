<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "payment".
 *
 * @property integer $id
 * @property integer $uid
 * @property string $paid_amount
 * @property integer $paid_type
 * @property integer $bank_acc
 * @property string $item
 * @property string $original_price
 * @property integer $voucher_id
 * @property integer $discount
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

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['uid', 'paid_amount', 'paid_type', 'item', 'original_price'], 'required'],
            [['uid', 'paid_type', 'bank_acc', 'voucher_id', 'discount'], 'integer'],
            [['paid_amount', 'original_price'], 'number'],
            [['item'], 'string', 'max' => 255],
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
            'bank_acc' => 'Bank Acc',
            'item' => 'Item',
            'original_price' => 'Original Price',
            'voucher_id' => 'Voucher ID',
            'discount' => 'Discount',
        ];
    }
}
