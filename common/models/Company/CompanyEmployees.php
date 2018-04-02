<?php

namespace common\models\Company;

use Yii;
use yii\db\ActiveRecord;
use yii\data\ActiveDataProvider;
use yii\behaviors\TimestampBehavior;
use common\models\User;

/**
 * This is the model class for table "company_employees".
 *
 * @property integer $id
 * @property integer $cid
 * @property integer $uid
 * @property integer $status
 * @property string $created_at
 */
class CompanyEmployees extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'company_employees';
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
            [['cid', 'uid'], 'required'],
            [['cid', 'uid', 'status', 'created_at', 'updated_at'], 'integer'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'cid' => 'Cid',
            'uid' => 'Uid',
            'status' => 'Status',
            'created_at' => 'Created At',
            'updated_at'=>'Updated At',
        ];
    }

    public function getUser()
    {
        return $this->hasOne(User::className(),['id' => 'uid']);
    }

    public function getCompany()
    {
        return $this->hasOne(Company::className(),['id' => 'cid']);
    }
}
