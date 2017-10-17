<?php

namespace common\models\Package;

use Yii;

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

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['userpid', 'paymentid', 'created_at', 'updated_at'], 'required'],
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
