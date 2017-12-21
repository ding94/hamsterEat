<?php

namespace common\models\Profit;

use Yii;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord; 
use frontend\controllers\CartController;

/**
 * This is the model class for table "restaurant_item_profit".
 *
 * @property integer $oid
 * @property integer $rid
 * @property integer $did
 * @property integer $quantity
 * @property integer $originalPrice
 * @property double $finalPrice
 * @property integer $created_at
 * @property integer $updated_at
 */
class RestaurantItemProfit extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'restaurant_item_profit';
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
            [['oid', 'rid','sid', 'did','fid', 'quantity', 'originalPrice', 'finalPrice'], 'required'],
            [['oid', 'rid', 'did', 'quantity', 'created_at', 'updated_at'], 'integer'],
            [['sid','fid'], 'string', 'max' => 255],
            [['finalPrice','originalPrice'], 'number'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'oid' => 'Order ID',
            'rid' => 'Restaurant ID',
            'did' => 'Delivery ID',
            'quantity' => 'Quantity',
            'originalPrice' => 'Original Price',
            'finalPrice' => 'Final Price',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }

    public function getFinalSum()
    {
        $data = RestaurantProfit::findOne($this->did);
        return $data->total;
    }

    public function getDiscount()
    {
        $data = RestaurantProfit::findOne($this->did);
        $discount = $data->earlyDiscount == 0 ? $data->voucherDiscount : $data->earlyDiscount;
        return $discount;
    }

    public function getTotalSum()
    {
        $data = RestaurantProfit::findOne($this->did);
        $discount = $data->earlyDiscount == 0 ? $data->voucherDiscount : $data->earlyDiscount;
        return $data->total - $discount;
    }


    public function getOriginal()
    {
        return  CartController::actionDisplay2decimal($this->originalPrice);
    }

    public function getCost()
    {
        return  CartController::actionDisplay2decimal($this->originalPrice * $this->quantity);
    }

    public function getSellPrice()
    {
        return  CartController::actionDisplay2decimal($this->finalPrice * $this->quantity);
    }
}
