<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "accounttopup".
 *
 * @property integer $Account_TransactionID
 * @property string $User_Username
 * @property string $Account_ChosenBank
 * @property double $Account_TopUpAmount
 * @property integer $Account_TransactionDate
 * @property integer $Account_TransactionNumber
 * @property string $Account_ReceiptPicPath
 * @property integer $Account_SubmitDateTime
 */
class Accounttopup extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'accounttopup';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['Account_TopUpAmount'], 'number'],
            [['Account_TransactionDate', 'Account_TransactionNumber', 'Account_SubmitDateTime'], 'integer'],
            [['User_Username', 'Account_ChosenBank', 'Account_ReceiptPicPath'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'Account_TransactionID' => 'Account  Transaction ID',
            'User_Username' => 'User  Username',
            'Account_ChosenBank' => 'Account  Chosen Bank',
            'Account_TopUpAmount' => 'Account  Top Up Amount',
            'Account_TransactionDate' => 'Account  Transaction Date',
            'Account_TransactionNumber' => 'Account  Transaction Number',
            'Account_ReceiptPicPath' => 'Account  Receipt Pic Path',
            'Account_SubmitDateTime' => 'Account  Submit Date Time',
        ];
    }
}
