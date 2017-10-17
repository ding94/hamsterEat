<?php

namespace common\models\Package;

use Yii;

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

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['pid', 'fid', 'quantity', 'created_at', 'updated_at'], 'required'],
            [['pid', 'fid', 'quantity', 'created_at', 'updated_at'], 'integer'],
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
            'quantity' => 'Quantity',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }
}
