<?php

namespace common\models\Order;

use Yii;
use common\models\Rating\Servicerating;
use common\models\Rating\Foodrating;
use common\models\food\Food;
use common\models\Rating\RatingStatus;
use common\models\Order\Orderitemstatuschange;
use common\models\Order\Ordersstatuschange;
use common\models\Order\Orderitemselection;
use common\models\Order\Orderitem;
use common\models\Order\StatusType;
use common\models\User;
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
 * @property string $Orders_PaymentMethod
 * @property string $Orders_Deliveryman
 * @property string $Orders_Status
 * @property integer $Orders_DateTimeMade
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

    public function afterSave($insert, $changedAttributes)
    {
      
        switch ($this->Orders_Status) {
            case 2:
                $status = Ordersstatuschange::findOne($this->Delivery_ID);
                if(empty($status))
                {
                    $status = new Ordersstatuschange;
                    $status->Delivery_ID =  $this->Delivery_ID;
                    $status->OChange_PendingDateTime = time();
                    $status->save();
                }
                break;
            case 3:
                $status = Ordersstatuschange::findOne($this->Delivery_ID);
                $status->OChange_PreparingDateTime = time();
                $status->save();
                break;
            case 11:
                $status = Ordersstatuschange::findOne($this->Delivery_ID);
                $status->OChange_PickUpInProcessDateTime = time();
                $status->save();
                break;
            case 5:
                $status = Ordersstatuschange::findOne($this->Delivery_ID);
                $status->OChange_OnTheWayDateTime = time();
                $status->save();
                break;
            case 6:
                $status = Ordersstatuschange::findOne($this->Delivery_ID);
                $status->OChange_CompletedDateTime = time();
                $status->save();
                break;
            default:
                # code...
                break;
        }
    }

    public function rules()
    {
        return [
            [['User_Username','Orders_Subtotal','Orders_DeliveryCharge','Orders_TotalPrice','Orders_PaymentMethod','Orders_Status','Orders_DateTimeMade','Orders_Time','Orders_Date'],'required'],
            [['Orders_Subtotal', 'Orders_DeliveryCharge', 'Orders_TotalPrice','Orders_DiscountEarlyAmount', 'Orders_DiscountTotalAmount'], 'number'],
            [['Orders_DiscountEarlyAmount','Orders_DiscountTotalAmount'],'default','value' => 0],
            [[ 'Orders_DateTimeMade','Orders_Status'], 'integer'],
            [['User_Username', 'Orders_PaymentMethod'], 'string', 'max' => 255],
            [['Orders_Time' ],'time'],
            [['Orders_Date'],'date', 'format' => 'php:Y-m-d'],
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
            'Orders_SessionGroup' => 'Orders  Session Group',
            'Orders_PaymentMethod' => 'Orders  Payment Method',
            'Orders_Status' => 'Orders  Status',
            'Orders_DateTimeMade' => 'Orders  Date Time Made',
            'Orders_DiscountEarlyAmount' => 'Orders  Discount Early Amount',
            'Orders_DiscountTotalAmount' => 'Orders  Discount Total Amount',
            'User_contactno' => "User's Contact Number",
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

    public function getItem()
    {
         return $this->hasMany(Orderitem::className(),['Delivery_ID' => 'Delivery_ID']); 
    }

    public function getFood()
    {
        return $this->hasOne(Food::className(),['Food_ID'=>$this->item->Food_ID]);
    }

    public function getUser()
    {
        return $this->hasOne(User::className(),['username'=>'User_Username']);
    }

    public function getAddress()
    {
        return $this->hasOne(DeliveryAddress::className(),['delivery_id'=>'Delivery_ID']);
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
