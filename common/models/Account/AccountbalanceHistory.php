<?php

namespace common\models\Account;

use Yii;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "accountbalance_history".
 *
 * @property integer $id
 * @property integer $abid
 * @property integer $type
 * @property string $description
 * @property double $amount
 * @property integer $created_at
 * @property integer $updated_at
 */
class AccountbalanceHistory extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'accountbalance_history';
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
            [['abid', 'type', 'description', 'amount'], 'required'],
            [['abid', 'type', 'created_at', 'updated_at'], 'integer'],
            [['description'], 'string'],
            [['amount'], 'number'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'abid' => 'Abid',
            'type' => 'Type',
            'description' => 'Description',
            'amount' => 'Amount',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }
}
