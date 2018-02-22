<?php

namespace common\models\Order;

use common\models\User;
use common\models\Company\Company;
use Yii;

/**
 * This is the model class for table "delivery_address".
 *
 * @property integer $delivery_id
 * @property integer $cid
 * @property string $name
 * @property string $contactno
 * @property string $location
 * @property string $postcode
 * @property string $area
 * @property integer $deliveryman
 * @property integer $type
 */
class DeliveryAddress extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'delivery_address';
    }

    public function getFulladdress()
    {
        return $this->location .' ,'. $this->area . ' ,'. $this->postcode;
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['deliveryman', 'type'], 'required'],
            [['cid'],'required','message'=>Yii::t('checkout','Selection').Yii::t('common',' cannot be blank.')],
            [['name'],'required','message'=>Yii::t('common','name').Yii::t('common',' cannot be blank.')],
            [['contactno'],'required','message'=>Yii::t('common','Contact No').Yii::t('common',' cannot be blank.')],
            [['delivery_id', 'deliveryman', 'type', 'postcode'], 'integer'],
            ['contactno','match','pattern'=>'/^[0]{1}[1-9]{1}[0-9]{7,9}$/'],
            [['name', 'contactno', 'location', 'area'], 'string', 'max' => 255],
            [['location', 'postcode', 'area' ],'required','when'=>function($model){
                return  $model->cid == 0;
            },'whenClient' => "function(attribute,value){
                if($('input[name=cid]').length <= 0)
                {
                    return $('#deliveryaddress-cid input:checked').val() == 0 
                }
                else
                {
                    return $('#deliveryaddress-cid input:checked').val() == 0 || $('input[name=cid]').val() == 0; 
                }
            }"],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'delivery_id' => 'Delivery ID',
            'cid' => 'Selection',
            'name' => 'Name',
            'contactno' => 'Contactno',
            'location' => 'Location',
            'postcode' => 'Postcode',
            'area' => 'Area',
            'deliveryman' => 'Deliveryman',
            'type' => 'Type',
        ];
    }
	
	public function getCompany()
    {
        return $this->hasOne(Company::className(),['id' => 'cid']);
    }

    public function getDeliveryName()
    {
        $model = User::findOne($this->deliveryman);
        return $model->username;
    }

    public function getCompanyName()
    {
        if($this->type == 1)
        {
            $model = Company::findOne($this->cid);
            return $model->name;
        }
        else
        {
            return "";
        }
    }
}
