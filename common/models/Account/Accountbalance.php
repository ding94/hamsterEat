<?php

namespace common\models\Account;

use Yii;

/**
 * This is the model class for table "accountbalance".
 *
 * @property integer $AB_ID
 * @property string $User_Username
 * @property integer $AB_topup
 * @property integer $AB_minus
 * @property integer $AB_DateTime
 */
class Accountbalance extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'accountbalance';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['AB_topup', 'AB_minus', 'AB_DateTime'], 'integer'],
            [['AB_topup', 'AB_minus','User_Balance'],'number'],
            [['User_Username'], 'string', 'max' => 255],
            
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'AB_ID' => 'Ab  ID',
            'User_Username' => 'User  Username',
            'AB_topup' => 'Ab Topup',
            'AB_minus' => 'Ab Minus',
            'AB_DateTime' => 'Ab  Date Time',
        ];
    }
}
