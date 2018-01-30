<?php

namespace common\models\Order;

use Yii;

/**
 * This is the model class for table "orderpricehistory".
 *
 * @property int $id
 * @property int $did
 * @property double $total
 * @property double $subtotal
 * @property double $deliveryCharge
 * @property double $earlydiscount
 * @property double $voucherdiscount
 * @property int $time
 */
class Orderpricehistory extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'orderpricehistory';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['did', 'total', 'subtotal', 'deliveryCharge', 'time'], 'required'],
            [['did', 'time'], 'integer'],
            [['total', 'subtotal', 'deliveryCharge', 'earlydiscount', 'voucherdiscount'], 'number'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'did' => 'Did',
            'total' => 'Total',
            'subtotal' => 'Subtotal',
            'deliveryCharge' => 'Delivery Charge',
            'earlydiscount' => 'Earlydiscount',
            'voucherdiscount' => 'Voucherdiscount',
            'time' => 'Time',
        ];
    }
}
