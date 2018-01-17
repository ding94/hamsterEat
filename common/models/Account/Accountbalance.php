<?php

namespace common\models\Account;

use Yii;
use common\models\Account\AccountbalanceHistory;

/**
 * This is the model class for table "accountbalance".
 *
 * @property integer $AB_ID
 * @property string $User_Username
 * @property integer $AB_topup
 * @property integer $AB_minus
 * @property integer $AB_DateTime
 */
class Accountbalance extends \yii\db\ActiveRecord
{
    /*
    * type detect which desciprition to write
    * defailtAmount get the intial enter amount
    */
    public $type;
    public $defaultAmount;
    public $deliveryid;
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'accountbalance';
    }

    /*
    * type 
    * => 0 for deduct
    * => 1 for plus
    * abid  acount balance id
    */
    public function afterSave($insert, $changedAttributes) 
    {
        $history= new AccountbalanceHistory;
        $history->abid = $this->AB_ID;
        $history->amount = $this->defaultAmount;
        $history->created_at =  new \yii\db\Expression('NOW()');

        switch ($this->type) {
            case 1:
                $history->description = "Top Up RM ".$this->defaultAmount;
                $history->type = 1;
                $history->system_type = "Top Up";
                break;
            case 2:
                $history->description = "Top Up Undo RM ".$this->defaultAmount;  
                $history->type = 0;
                $history->system_type = "Top Up Undo";
                break;
            case 3:
                $history->description = "Withdraw RM" . $this->defaultAmount . " With RM 2 Transation Fee";  
                $history->type = 0;
                $history->system_type = "Withdraw";
                break;
            case 4:
                $history->description = "Withdraw Fail Retrieve Back RM" . $this->defaultAmount;
                $history->type = 1;
                $history->system_type = "Withdraw Fail";
                break;
            case 5;
                $history->description = "Placed An Order, id ".$this->deliveryid. " with total " .$this->defaultAmount;
                $history->type = 0;
                $history->system_type = "Order";
                break;
            default:
                # code...
                break;
        }
        
        $history->save();
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['AB_ID','AB_DateTime'], 'integer'],
            [['AB_topup', 'AB_minus','User_Balance'],'number'],
            [['User_Username'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'AB_ID' => 'ID',
            'User_Username' => 'User  Username',
            'AB_topup' => 'Total Topup',
            'AB_minus' => 'Total Used',
            'AB_DateTime' => 'Ab  Date Time',
        ];
    }

    public function getHistory()
    {
        return $this->hasMany(AccountbalanceHistory::className(),['abid'=>'AB_ID']);
    }
}
