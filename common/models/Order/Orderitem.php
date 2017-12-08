<?php

namespace common\models\Order;

use Yii;
use yii\helpers\Json;
use common\models\food\Food;
use common\models\food\Foodselection;
use common\models\food\Foodselectiontype;
use common\models\Order\Orderitemstatuschange;
use common\models\Order\Orderitemselection;

/**
 * This is the model class for table "orderitem".
 *
 * @property integer $Delivery_ID
 * @property integer $Food_ID
 * @property integer $Order_ID
 * @property integer $OrderItem_Quantity
 * @property double $OrderItem_LineTotal
 * @property string $OrderItem_Status
 * @property string $OrderItem_Remark
 */
class Orderitem extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public $item = [];
    public static function tableName()
    {
        return 'orderitem';
    }

    public function afterSave($insert, $changedAttributes)
    {
        switch ($this->OrderItem_Status) {
            case 'Pending':
                $status = new Orderitemstatuschange;
                $status->Order_ID = $this->Order_ID;
                $status->Change_PendingDateTime = time();
                $status->save();
                break;
            case 'Preparing':
                $status = Orderitemstatuschange::findOne($this->Order_ID);
                $status->Change_PreparingDateTime = time();
                $status->save();
                break;
            case 'Ready For Pick Up':
                $status = Orderitemstatuschange::findOne($this->Order_ID);
                $status->Change_ReadyForPickUpDateTime = time();
                $status->save();
                break;
            case 'Picked Up':
                $status = Orderitemstatuschange::findOne($this->Order_ID);
                $status->   Change_PickedUpDateTime = time();
                $status->save();
                break;
            default:
                # code...
                break;
        }
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['Food_ID','OrderItem_Quantity','OrderItem_SelectionTotal','OrderItem_LineTotal','OrderItem_Status'],'required'],
            [['Delivery_ID', 'Food_ID', 'OrderItem_Quantity'], 'integer'],
            [['OrderItem_LineTotal','OrderItem_SelectionTotal'], 'number'],
            [['OrderItem_Status', 'OrderItem_Remark'], 'string', 'max' => 255],
            [['Order_ID'],'safe'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'Delivery_ID' => 'Delivery ID',
            'Food_ID' => 'Food ID',
            'Order_ID' => 'Order ID',
            'OrderItem_Quantity' => 'Order Item Quantity',
            'OrderItem_SelectionTotal' => 'Order Item Selection Total',
            'OrderItem_LineTotal' => 'Order Item Line Total',
            'OrderItem_Status' => 'Order Item Status',
            'OrderItem_Remark' => 'Order Item Remark',
        ];
    }

    public function getFood()
    {
         return $this->hasOne(Food::className(),['Food_ID'=>'Food_ID']);
    }

    public function getFoodtype()
    {
         return $this->hasOne(Foodtype::className(),['Food_ID'=>'Food_ID']);
    }

    public function getItem_status()
    {
        return $this->hasOne(Orderitemstatuschange::className(),['Order_ID' => 'Order_ID']); 
    }

    public function getOrder_selection()
    {
        return $this->hasMany(Orderitemselection::className(),['Order_ID' => 'Order_ID']); 
    }

    public function getFood_selection_name($model)
    {
        $data = "";
        $itemselection = Orderitemselection::find()->where('Order_ID=:oid',[':oid'=>$model['Order_ID']])->all();
        foreach ($itemselection as $k => $value) {
            $selection = Foodselection::find()->where('ID=:id',[':id'=>$value['Selection_ID']])->one();
            if (!empty($selection)) {
                $data .= $selection['Name'].', ';
            }
        }
        return $data;
    }

    public function getOrder()
    {
        return $this->hasOne(Orders::className(),['Delivery_ID' => 'Delivery_ID']); 
    }

    public function getOrder_status()
    {
        return $this->hasOne(Ordersstatuschange::className(),['Order_ID' => 'Order_ID']); 
    }
    
    public function getRestaurant()
    {
        return $this->hasOne(Restaurant::className(),['Restaurant_ID' => $this->food->Restaurant_ID]);
    }

    public function getProblem_Order()
    {
        return $this->hasOne(ProblemOrder::className(),['Order_ID' => 'Order_ID']);
    }

    public function getAddress()
    {
        return $this->hasOne(DeliveryAddress::className(),['delivery_id'=>'Delivery_ID']);
    }

    public function getTrim_selection()
    {
        $array = [];
        
        $data = Orderitemselection::find()->where('Order_ID = :id',[':id'=>$this->Order_ID])->orderBy(['Selection_ID'=>    SORT_ASC])->all();

        foreach($data as $single)
        {
            if(empty($array[$single->FoodType_ID]))
            {
                $array[$single->FoodType_ID] = Foodselection::findOne($single->Selection_ID)->Name;
            }
            else
            {
                $array[$single->FoodType_ID] .= Foodselection::findOne($single->Selection_ID)->Name;
            }
            
        }
        return Json::encode($array);
    }
}
