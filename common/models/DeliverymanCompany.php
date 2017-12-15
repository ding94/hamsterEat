<?php

namespace common\models;

use Yii;

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

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['uid', 'cid', 'created_at', 'updated_at'], 'required'],
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
