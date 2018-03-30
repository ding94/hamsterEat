<?php

namespace common\models\promotion;

use Yii;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "promotion_limit".
 *
 * @property int $id
 * @property int $pid promotion id
 * @property int $tid can be either id base on type
 * @property int $food_limit
 * @property int $created_at
 * @property int $updated_at
 *
 * @property PromotionDailyLimit[] $promotionDailyLimits
 * @property Promotion $p
 */
class PromotionLimit extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'promotion_limit';
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
            [['pid', 'tid', 'food_limit'], 'required'],
            [['pid', 'tid', 'food_limit', 'created_at', 'updated_at'], 'integer'],
            [['pid'], 'exist', 'skipOnError' => true, 'targetClass' => Promotion::className(), 'targetAttribute' => ['pid' => 'id']],
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
            'tid' => 'Tid',
            'food_limit' => 'Food Limit',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPromotionDailyLimits()
    {
        return $this->hasMany(PromotionDailyLimit::className(), ['id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getP()
    {
        return $this->hasOne(Promotion::className(), ['id' => 'pid']);
    }
}
