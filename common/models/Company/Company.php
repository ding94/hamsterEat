<?php

namespace common\models\Company;

use Yii;
use yii\db\ActiveRecord;
use yii\data\ActiveDataProvider;
use yii\behaviors\TimestampBehavior;
use common\models\DeliverymanCompany;
use common\models\Order\DeliveryAddress;

/**
 * This is the model class for table "company".
 *
 * @property integer $id
 * @property string $name
 * @property integer $owner_id
 * @property string $license_no
 * @property string $address
 * @property string $postcode
 * @property string $area
 * @property integer $status
 * @property string $created_at
 * @property integer $area_group
 */
class Company extends \yii\db\ActiveRecord
{
    public $username;
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'company';
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
            [['name','contact_name','contact_number','license_no','address', 'postcode', 'area'], 'required'],
            [['owner_id', 'status', 'area_group','created_at','updated_at','postcode'], 'integer'],
            [['contact_name','name', 'license_no', 'address', 'area','contact_number'], 'string', 'max' => 255],
            [['name'],'safe'],
            ['username', 'string'],
            [['username','owner_id'], 'required','on'=>'register'],

        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => 'Name',
            'owner_id' => 'Owner ID',
            'license_no' => 'License No',
            'contact_name' => 'Contact Name',
            'contact_number'=> 'Contact Number',
            'address' => 'Address',
            'postcode' => 'Postcode',
            'area' => 'Area',
            'status' => 'Status',
            'created_at' => 'Created At',
            'area_group' => 'Area Group',
        ];
    }

    public function search($params,$action)
    {

        $query = self::find();
        $query = $query->joinWith('deliverymancompany');

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);
        
        $this->load($params);

        $query->andFilterWhere(['like','name' , $this->name]);

        return $dataProvider;
    }

    public function getEmployee()
    {
        return $this->hasOne(CompanyEmployees::className(),['cid' => 'id']);
    }

    public function getDeliverymancompany()
    {
        return $this->hasOne(DeliverymanCompany::className(),['cid'=>'id']);
    }

    public function getDeliveryaddress()
    {
        return $this->hasMany(DeliveryAddress::className(),['cid'=>'id']);
    }
}
