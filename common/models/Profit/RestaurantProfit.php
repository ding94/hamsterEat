<?php

namespace common\models\Profit;

use Yii;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord; 

/**
 * This is the model class for table "restaurant_profit".
 *
 * @property integer $did
 * @property integer $cid
 * @property integer $rid
 * @property double $earlyDiscount
 * @property double $voucherDiscount
 * @property double $total
 * @property integer $create_at
 * @property integer $updated_at
 */
class RestaurantProfit extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'restaurant_profit';
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
            [['did', 'cid', 'earlyDiscount', 'voucherDiscount', 'total'], 'required'],
            [['did', 'cid', 'created_at', 'updated_at'], 'integer'],
            [['earlyDiscount', 'voucherDiscount', 'total'], 'number'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'did' => 'Did',
            'cid' => 'Cid',
            'earlyDiscount' => 'Early Discount',
            'voucherDiscount' => 'Voucher Discount',
            'total' => 'Total',
            'created_at' => 'Create At',
            'updated_at' => 'Updated At',
        ];
    }

    public function getItemProfit()
    {
        return $this->hasMany(RestaurantItemProfit::className(),['did'=>'did']);
    }
}
