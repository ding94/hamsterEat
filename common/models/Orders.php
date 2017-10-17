<?php

namespace common\models;

use Yii;
use common\models\Rating\Servicerating;
use common\models\Rating\Foodrating;
use common\models\food\Food;
use common\models\Rating\RatingStatus;
use common\models\Orderitemstatuschange;
use common\models\Ordersstatuschange;
use common\models\Orderitemselection;
use common\models\Orderitem;
/**
 * This is the model class for table "orders".
 *
 * @property integer $Delivery_ID
 * @property string $User_Username
 * @property double $Orders_Subtotal
 * @property double $Orders_DeliveryCharge
 * @property double $Orders_TotalPrice
 * @property string $Orders_Date
 * @property string $Orders_Time
 * @property string $Orders_Location
 * @property integer $Orders_Postcode
 * @property string $Orders_Area
 * @property integer $Orders_SessionGroup
 * @property string $Orders_PaymentMethod
 * @property string $Orders_Deliveryman
 * @property string $Orders_Status
 * @property integer $Orders_DateTimeMade
 * @property double $Orders_DiscountCodeAmount
 * @property double $Orders_DiscountVoucherAmount
 * @property double $Orders_DiscountEarlyAmount
 * @property double $Orders_DiscountTotalAmount
 * @property string $Orders_InvoiceURL
 */
class Orders extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'orders';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['Orders_Location','Orders_Postcode','Orders_Area'],'required','on'=>'edit'],
            [['Orders_Subtotal', 'Orders_DeliveryCharge', 'Orders_TotalPrice', 'Orders_DiscountCodeAmount', 'Orders_DiscountVoucherAmount', 'Orders_DiscountEarlyAmount', 'Orders_DiscountTotalAmount'], 'number'],
            [['Orders_Date', 'Orders_Time'], 'safe'],
            [['Orders_Postcode', 'Orders_SessionGroup', 'Orders_DateTimeMade'], 'integer'],
            [['User_Username', 'Orders_PaymentMethod', 'Orders_Deliveryman', 'Orders_InvoiceURL'], 'string', 'max' => 255],
            [['Orders_Location', 'Orders_Area', 'Orders_Status'], 'string', 'max' => 50],
            [['Delivery_ID'],'safe'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'Delivery_ID' => 'Delivery  ID',
            'User_Username' => 'User  Username',
            'Orders_Subtotal' => 'Orders  Subtotal',
            'Orders_DeliveryCharge' => 'Orders  Delivery Charge',
            'Orders_TotalPrice' => 'Orders  Total Price',
            'Orders_Date' => 'Orders  Date',
            'Orders_Time' => 'Orders  Time',
            'Orders_Location' => 'Orders  Location',
            'Orders_Postcode' => 'Orders  Postcode',
            'Orders_Area' => 'Orders  Area',
            'Orders_SessionGroup' => 'Orders  Session Group',
            'Orders_PaymentMethod' => 'Orders  Payment Method',
            'Orders_Deliveryman' => 'Orders  Deliveryman',
            'Orders_Status' => 'Orders  Status',
            'Orders_DateTimeMade' => 'Orders  Date Time Made',
            'Orders_DiscountCodeAmount' => 'Orders  Discount Code Amount',
            'Orders_DiscountVoucherAmount' => 'Orders  Discount Voucher Amount',
            'Orders_DiscountEarlyAmount' => 'Orders  Discount Early Amount',
            'Orders_DiscountTotalAmount' => 'Orders  Discount Total Amount',
            'Orders_InvoiceURL' => 'Orders  Invoice Url',
        ];
    }

    public function getServicerating()
    {
        return $this->hasOne(Servicerating::className(),['delivery_id'=>'Delivery_ID']);
    }

    public function getFoodrating()
    {
        return $this->hasMany(Foodrating::className(),['delivery_id' =>'Delivery_ID']);
    }

    public function getFoodstatus()
    {
        return $this->hasOne(RatingStatus::className(),['id' => $this->foodrating->FoodRating_Rating]);
    }

    public function getFoods()
    {
        return $this->hasOne(Food::className(),['Food_ID'=> $this->foodrating->Food_ID]);
    }

    public function attributes()
    {
        return array_merge(parent::attributes(),['order_status.OChange_PendingDateTime']);
    }

    public function getOrder_status()
    {
        return $this->hasOne(Ordersstatuschange::className(),['Delivery_ID' => 'Delivery_ID']); 
    }

    public function getOrder_item()
    {
        return $this->hasOne(Orderitem::className(),['Delivery_ID' => 'Delivery_ID']); 
    }

    /*public function getFood_linking()
    {
        return $this->hasOne(Food::className(),['Food_ID' => $this->order_item->Food_ID]); 
    }

    public function getItems_status()
    {
        return $this->hasOne(Orderitemselection::className(),['Order_ID' => $this->order_item->Order_ID]); 
    }*/
}
