<?php

namespace common\models;

use Yii;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "deliveryman_company".
 *
 * @property integer $id
 * @property integer $uid
 * @property integer $cid
 * @property integer $created_at
 * @property integer $updated_at
 */
class DeliverymanCompany extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'deliveryman_company';
    }

    public function behaviors()
    {
        return [
            TimestampBehavior::className(),
            'timestamp' => [
                'class' => 'yii\behaviors\TimestampBehavior',
                'attributes' => [
                    ActiveRecord::EVENT_BEFORE_INSERT => ['created_at', 'updated_at'],
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
            [['uid', 'cid'], 'required'],
            [['uid', 'cid', 'created_at', 'updated_at'], 'integer'],
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
            'cid' => 'Cid',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }
}
