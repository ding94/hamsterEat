<?php

namespace common\models;

use Yii;
use yii\db\ActiveRecord;
use yii\data\ActiveDataProvider;
use common\models\VouchersType;
/**
 * This is the model class for table "vouchers".
 *
 * @property integer $id
 * @property string $code
 * @property double $discount
 * @property integer $discount_type
 * @property integer $discount_item
 * @property integer $status
 * @property integer $usedTimes
 * @property string $inCharge
 * @property integer $startDate
 * @property integer $endDate
 */
class Vouchers extends \yii\db\ActiveRecord
{

    public $amount;
    public $digit;
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'vouchers';
    }

    public function attributes()
    {
        return array_merge(parent::attributes(),['voucher_type.description']);
    }

    public function getVoucher_type()
    {
        return $this->hasOne(VouchersType::className(),['id' => 'discount_type']); 
    }

    public function getVoucher_item()
    {
        return $this->hasOne(VouchersType::className(),['id' => 'discount_item']); 
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['code', 'discount_type', 'discount_item', 'usedTimes', 'inCharge', 'startDate'], 'required'],
            [['discount'],'required','on'=>'initial'],
            [['code', 'inCharge'], 'string'],
            [['discount'], 'number'],
            [['discount_type', 'discount_item', 'status', 'usedTimes'], 'integer'],

            [['startDate','endDate'],'date', 'on'=> ['initial']],
            [['startDate','endDate'],'integer', 'on'=> ['save']],   

            [['startDate','endDate'],'date', 'on'=> ['generate']],
            [['discount','amount','digit'],'required', 'on' => ['generate']],
            

            ['digit', 'integer','min'=> 8,'max'=> 20],
            ['amount','integer','min'=> 1,'max'=> 100],
            ['discount','integer','min'=> 1],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'code' => 'Code',
            'discount' => 'Discount',
            'discount_type' => 'Discount Type',
            'discount_item' => 'Discount Item',
            'status' => 'Status',
            'usedTimes' => 'Used Times',
            'inCharge' => 'In Charge',
            'startDate' => 'Start Date',
            'endDate' => 'End Date',
            'voucher_type.description' => 'Discount type',
            'voucher_item.description' => 'Discount from',

        ];
    }

    public function search($params,$action)
    {

        
        if ($action == 2) {
            $query = self::find()->andWhere(['or',['discount_type' => 1],['discount_type' => 4]]    );
        }
        else
        {
            $query = self::find();
        }

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);

        //$query->andFilterWhere(['like','description' , $this->getAttribute('ticket_status.description')]);

        return $dataProvider;
    }
}
