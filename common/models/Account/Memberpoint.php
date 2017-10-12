<?php

namespace common\models\Account;

use Yii;
use common\models\Account\Memberpointhistory;

/**
 * This is the model class for table "memberpoint".
 *
 * @property integer $id
 * @property integer $uid
 * @property integer $point
 * @property integer $positive
 * @property integer $negative
 */
class Memberpoint extends \yii\db\ActiveRecord
{
    public $amount;
    public $type;

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'memberpoint';
    }

    public function afterSave($insert, $changedAttributes) 
    {
        $history= new Memberpointhistory;
        $history->mpid = $this->id;
        $history->type = $this->type;
        switch ($this->type) {
            case 1:
                $history->description = "Convert ".$this->amount." from payment to memberpoint";
                break;
            case 2:
                $history->description = "Convert ".$this->amount." from memberpoint to account balance";
                break;
            default:
                # code...
                break;
        }
        $history->amount = $this->amount;
        $history->save();
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['uid', 'point', 'positive', 'negative'], 'required'],
            [['uid', 'point', 'positive', 'negative'], 'integer'],
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
            'point' => 'Point',
            'positive' => 'Positive',
            'negative' => 'Negative',
        ];
    }
}
