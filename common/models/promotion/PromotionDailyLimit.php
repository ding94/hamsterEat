<?php

namespace common\models\promotion;

use Yii;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "promotion_daily_limit".
 *
 * @property int $id
 * @property string $date
 * @property int $food_limit
 * @property int $created_at
 * @property int $updated_at
 *
 * @property PromotionLimit $id0
 */
class PromotionDailyLimit extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'promotion_daily_limit';
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
            [['id', 'date', 'food_limit', 'created_at', 'updated_at'], 'required'],
            [['id', 'food_limit', 'created_at', 'updated_at'], 'integer'],
            [['date'], 'string', 'max' => 25],
            [['id'], 'exist', 'skipOnError' => true, 'targetClass' => PromotionLimit::className(), 'targetAttribute' => ['id' => 'id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'date' => 'Date',
            'food_limit' => 'Food Limit',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getId0()
    {
        return $this->hasOne(PromotionLimit::className(), ['id' => 'id']);
    }
}
