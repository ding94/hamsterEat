<?php

namespace common\models;

use Yii;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "accounttopup_status".
 *
 * @property integer $id
 * @property string $title
 */
class AccounttopupStatus extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'accounttopup_status';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id','title'], 'required'],
            [['title'], 'string'],
            [['id'],'integer']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'title' => 'Title',
        ];
    }

    public function getAccounttopup_status()
    {
        return $this->hasOne(Accounttopup::className(),['Account_Action' => 'id']); 
    }
}
