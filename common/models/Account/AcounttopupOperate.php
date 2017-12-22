<?php

namespace common\models\Account;

use Yii;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;
use frontend\models\Accounttopup;

/**
 * This is the model class for table "acounttopup_operate".
 *
 * @property integer $id
 * @property integer $tid
 * @property string $adminname
 * @property string $type
 * @property string $oldVal
 * @property string $newVal
 * @property integer $created_at
 * @property integer $updated_at
 */
class AcounttopupOperate extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'acounttopup_operate';
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
            [['tid', 'adminname', 'newVal'], 'required'],
            [['tid', 'created_at', 'updated_at'], 'integer'],
            [['adminname', 'type', 'oldVal', 'newVal'], 'string', 'max' => 20],
            [['tid'],'exist',
                'skipOnError' => true,
                'targetClass' => Accounttopup::className(),
                'targetAttribute' => ['tid' => 'Account_TransactionID']
            ]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'tid' => 'Tid',
            'adminname' => 'Adminname',
            'type' => 'Type',
            'oldVal' => 'Old Val',
            'newVal' => 'New Val',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }
}