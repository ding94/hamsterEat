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
        if(is_null($this->User_FirstName) && is_null($this->User_LastName))
        {
            return "not set";
        }
        return $this->User_FirstName . ' ' . $this->User_LastName;
    }
    
    public function rules()
    {
        return [
            [['User_id'], 'required'],
            [['User_ContactNo','User_id'], 'integer'],
            [[ 'User_FirstName', 'User_LastName', 'User_PicPath' ], 'string', 'max' => 255],
            
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'User_id' => 'User  ID',
            'User_FirstName' => 'User  First Name',
            'User_LastName' => 'User  Last Name',
            'User_PicPath' => 'User  Pic Path',
            'User_ContactNo' => 'User  Contact No',
        ];
    }
}
