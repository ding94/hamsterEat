<?php

namespace common\models\Package;

use Yii;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "user_package_detail".
 *
 * @property integer $id
 * @property integer $pid
 * @property integer $fid
 * @property integer $quantity
 * @property integer $created_at
 * @property integer $updated_at
 */
class UserPackageDetail extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'user_package_detail';
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
            [['pid', 'fid', 'quantity','totalPrice'], 'required'],
            [['pid', 'fid', 'quantity', 'created_at', 'updated_at'], 'integer'],
            ['totalPrice','number'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'pid' => 'Pid',
            'fid' => 'Fid',
            'quantity' => 'Total Quantity',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }
}
