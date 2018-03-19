<?php

namespace common\models\vouchers;

use Yii;
use yii\db\ActiveRecord;
use yii\data\ActiveDataProvider;
use common\models\vouchers\DiscountItem;
use common\models\vouchers\UserVoucher;
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
        return array_merge(parent::attributes(),['voucher_status.description','discount_items.description','uservoucher']);
    }

    public function getDiscount_types()
    {
        return $this->hasOne(DiscountType::className(),['id' => 'discount_type']); 
    }

    public function getDiscount_items()
    {
        return $this->hasOne(DiscountItem::className(),['id' => 'discount_item']); 
    }

    public function getVoucher_status()
    {
        return $this->hasOne(VouchersStatus::className(),['id' => 'status']); 
    }

    public function getUservoucher(){
        return $this->hasOne(UserVoucher::className(),['vid' => 'id']); 
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['code', 'discount_type', 'discount_item', 'usedTimes', 'inCharge', 'startDate'], 'required'],
            [['discount'],'required','on'=>'initial'],
            [['code'], 'string'],
            [['discount'], 'number'],
            [['discount_type', 'discount_item', 'status', 'usedTimes'], 'integer'],

            [['startDate','endDate'],'date', 'on'=> ['initial']],
            [['startDate','endDate'],'integer', 'on'=> ['save']],   

            [['startDate','endDate'],'date', 'on'=> ['generate']],
            [['discount','amount','digit'],'required', 'on' => ['generate']],
            

            ['digit', 'integer','min'=> 8,'max'=> 20],
            ['amount','integer','min'=> 1,'max'=> 100],
            ['discount','number','min'=> 1],

            [['id','voucher_status.description','discount_items.description','endDate'], 'safe'],
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

        ];
    }

    public function search($params,$action)
    {
        switch ($action) {
            case 2 :
                $query = self::find()->andWhere(['=','status',1]);
                break;
            case 5:
                $query = self::find()->andWhere(['=','status',5]);
                break;
            
            default:
                $query = self::find()->andWhere(['!=','status',5]);
                break;
        }
        

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $dataProvider->sort->attributes['voucher_status.description'] = [
            'asc'=>['discount_type'=>SORT_ASC],
            'desc'=>['discount_type'=>SORT_DESC],
        ];

        $dataProvider->sort->attributes['discount_items.description'] = [
            'asc'=>['discount_item'=>SORT_ASC],
            'desc'=>['discount_item'=>SORT_DESC],
        ];

        $this->load($params);

        $query
        ->andFilterWhere(['like','id' , $this->getAttribute('id')])
        ->andFilterWhere(['like','code' , $this->getAttribute('code')])
        ->andFilterWhere(['like','discount' , $this->getAttribute('discount')])
        ->andFilterWhere(['like','status' , $this->getAttribute('voucher_status.description')])
        ->andFilterWhere(['like','discount_item' , $this->getAttribute('discount_items.description')])
        ->andFilterWhere(['like','FROM_UNIXTIME(startDate, "%Y-%m-%d")' , $this->startDate])
        ->andFilterWhere(['like','FROM_UNIXTIME(endDate, "%Y-%m-%d")' , $this->endDate])
        ;

        return $dataProvider;
    }

    public function usersearch($params,$uid,$action)
    {
        if ($action == 2) 
        {
            $query = self::find();
            $query->where(['user_voucher.uid' => $uid])->joinWith(['uservoucher']);
        }
        else
        {
            $query = self::find();
        }

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $dataProvider->sort->attributes['voucher_type.description'] = [
            'asc'=>['discount_type'=>SORT_ASC],
            'desc'=>['discount_type'=>SORT_DESC],
        ];

        $dataProvider->sort->attributes['discount_item.description'] = [
            'asc'=>['discount_item'=>SORT_ASC],
            'desc'=>['discount_item'=>SORT_DESC],
        ];

        $this->load($params);

        $query
        ->andFilterWhere(['like','id' , $this->getAttribute('id')])
        ->andFilterWhere(['like','code' , $this->getAttribute('code')])
        ->andFilterWhere(['like','discount' , $this->getAttribute('discount')])
        ->andFilterWhere(['like','discount_type' , $this->getAttribute('discount_type.description')])
        ->andFilterWhere(['like','discount_item' , $this->getAttribute('discount_items.description')])
        ->andFilterWhere(['like','discount_type' , $this->getAttribute('voucher_type.type')])
        ->andFilterWhere(['like','FROM_UNIXTIME(startDate, "%Y-%m-%d")' , $this->startDate])
        ->andFilterWhere(['like','FROM_UNIXTIME(endDate, "%Y-%m-%d")' , $this->endDate])
        ;

        return $dataProvider;
    }
}
    