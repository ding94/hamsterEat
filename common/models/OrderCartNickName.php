<?php

namespace common\models;

use Yii;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "orderCartNickName".
 *
 * @property int $id
 * @property string $type 1=>cart, 2=>delivery
 * @property int $tid base on type can cart id or did
 * @property string $nickname
 * @property int $created_at
 * @property int $updated_at
 */
class OrderCartNickName extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'orderCartNickName';
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
            [['type', 'tid','nickname'], 'required'],
            [['type'], 'string'],
            [['tid', 'created_at', 'updated_at'], 'integer'],
            
            [['nickname'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'type' => 'Type',
            'tid' => 'Tid',
            'nickname' => \Yii::t("common","Nickname"),
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }
}
