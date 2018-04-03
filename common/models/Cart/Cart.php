<?php

namespace common\models\Cart;

use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;
use Yii;
use common\models\food\{Food,Foodstatus};
use common\models\Cart\CartSelection;

/**
 * This is the model class for table "cart".
 *
 * @property integer $id
 * @property integer $uid
 * @property integer $fid
 * @property integer $quantity
 * @property double $price
 * @property double $selectionprice
 * @property integer $area
 * @property string $remark
 * @property integer $created_at
 * @property integer $updated_at
 */
class Cart extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public $groupselection = [];
    private $idCache;

    public static function tableName()
    {
        return 'cart';
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

    public function beforeDelete()
    {
        $this->idCache = $this->id;
        return parent::beforeDelete();
    }

    public function afterDelete()
    {
        $children = CartSelection::find()->where('cid = :cid',[':cid'=> $this->idCache])->all();
        if(!empty($children))
        {
            foreach($children as $child)
            {
                $child->delete();
            }  
        }
        parent::afterDelete();
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['uid', 'fid', 'quantity', 'price', 'selectionprice', 'area'], 'required'],
            [['uid', 'fid', 'quantity', 'area', 'created_at', 'updated_at'], 'integer'],
            [['price', 'selectionprice'], 'number'],
            [['remark'], 'string', 'max' => 255],
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
            'fid' => 'Fid',
            'quantity' => 'Quantity',
            'price' => 'Price',
            'selectionprice' => 'Selectionprice',
            'area' => 'Area',
            'remark' => 'Remark',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }

    public function getFood()
    {
        return $this->hasOne(Food::className(),['Food_ID' => 'fid']);
    }

    public function getSelection()
    {
        return $this->hasMany(CartSelection::className(),['cid' => 'id']);
    }

    /*
    * detect food status and limit to disable user process to checkout
    */
    public function getStatus()
    {
        $data = Foodstatus::find()->where('Food_ID = :fid and Status = 1',[':fid'=>$this->fid])->andWhere(['>','food_limit','0'])->one();
      
        if(!empty($data) && $data->food_limit - $this->quantity >= 0)
        {
            return 1;
        }
        
        return  0 ;
    }
}
