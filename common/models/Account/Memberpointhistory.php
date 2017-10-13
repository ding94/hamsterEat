<?php

namespace common\models\Account;

use Yii;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "memberpointhistory".
 *
 * @property integer $id
 * @property integer $mpid
 * @property integer $type
 * @property string $description
 * @property integer $amount
 * @property integer $created_at
 * @property integer $updated_at
 */
class Memberpointhistory extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'memberpointhistory';
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
            [['mpid', 'type', 'description', 'amount'], 'required'],
            [['mpid', 'type', 'amount', 'created_at', 'updated_at'], 'integer'],
            [['description'], 'string'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'mpid' => 'Mpid',
            'type' => 'Type',
            'description' => 'Description',
            'amount' => 'Amount',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }
}
