<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "user_voucher".
 *
 * @property integer $id
 * @property integer $uid
 * @property integer $vid
 * @property string $code
 * @property string $endDate
 */
class UserVoucher extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'user_voucher';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['uid', 'vid', 'code', 'endDate'], 'required'],
            [['uid', 'vid'], 'integer'],
            [['code'], 'string'],
            [['endDate'], 'safe'],
            [['endDate'],'date','on'=>'initial'],
            [['endDate'],'integer','on'=>'save'],
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
            'vid' => 'Vid',
            'code' => 'Code',
            'endDate' => 'End Date',
        ];
    }
}
