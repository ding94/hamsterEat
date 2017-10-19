<?php

namespace common\models\Package;

use Yii;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "user_package_payment".
 *
 * @property integer $id
 * @property integer $userpid
 * @property integer $paymentid
 * @property integer $created_at
 * @property integer $updated_at
 */
class UserPackagePayment extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'user_package_payment';
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
            [['userpid', 'paymentid'], 'required'],
            [['userpid', 'paymentid', 'created_at', 'updated_at'], 'integer'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'userpid' => 'Userpid',
            'paymentid' => 'Paymentid',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }
}
