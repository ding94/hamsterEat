<?php

namespace common\models\problem;

use Yii;
use common\models\Orders;
use common\models\Orderitem;
use common\models\Orderitemselection;
/**
 * This is the model class for table "problem_order".
 *
 * @property integer $Order_ID
 * @property integer $reason
 * @property integer $status
 * @property integer $datetime
 * @property double $refund
 */
class ProblemOrder extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'problem_order';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['Order_ID', 'reason', 'status', 'datetime','Delivery_ID'], 'required'],
            [['Order_ID', 'reason', 'status', 'datetime'], 'integer'],
            [['refund'], 'number'],
            [['description'],'string'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'Order_ID' => 'Order ID',
            'reason' => 'Reason',
            'status' => 'Status',
            'datetime' => 'Datetime',
            'refund' => 'Refund',
        ];
    }

    public function getOrder()
    {
        return $this->hasOne(Orders::className(),['Delivery_ID' => 'Delivery_ID']); 
    }

    public function getOrder_item()
    {
        return $this->hasOne(Orderitem::className(),['Order_ID' => 'Order_ID']); 
    }

    public function getOrder_item_select()
    {
        return $this->hasOne(Orderitemselection::className(),['Order_ID' => 'Order_ID']); 
    }
}
