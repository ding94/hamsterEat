<?php

namespace common\models\user;

use Yii;

/**
 * This is the model class for table "userdetails".
 *
 * @property string $User_Username
 * @property string $User_FirstName
 * @property string $User_LastName
 * @property string $User_PicPath
 * @property integer $User_MemberPoints
 * @property double $User_AccountBalance
 * @property string $User_ContactNo
 */
class Userdetails extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'userdetails';
    }

    public function getFullname() {
        if(empty($this->User_FirstName) && empty($this->User_LastName))
        {
            return "not set";
        }

        if(empty($this->User_LastName))
        {
            return $this->User_FirstName;
        }
        return $this->User_FirstName . ' ' . $this->User_LastName;
    }
    
    public function rules()
    {
        return [
            [['User_id'], 'required'],

            ['User_ContactNo', 'trim'],
            [['User_ContactNo'], 'required','message'=>Yii::t('common','Contact No').Yii::t('common',' cannot be blank.')],
            [['User_ContactNo'], 'match', 'pattern' => '/^[0-9]{10,11}$/','message'=>'Please Enter Correct Phone Format (e.g: 0123456789) '],
            ['User_ContactNo', 'unique', 'targetClass' => '\common\models\user\Userdetails', 'message' => Yii::t('common','This Phone number has already been taken.')],

            [['User_id'], 'integer'],
            [['User_FirstName', 'User_LastName', 'User_PicPath' ], 'string', 'max' => 255],
            [['User_FirstName', 'User_LastName','User_ContactNo',],'required','on'=>['out']],
            
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            
            'User_id' => 'User ID',
            'User_FirstName' => 'User First Name',
            'User_LastName' => 'User Last Name',
            'User_PicPath' => 'User Pic Path',
            'User_ContactNo' => Yii::t('common','Contact No'),
        ];
    }
}
