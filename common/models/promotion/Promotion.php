<?php

namespace common\models\promotion;

use Yii;

/**
 * This is the model class for table "promotion".
 *
 * @property int $id
 * @property int $type_promotion 1=> all, 2=> restaurant,3=>food 4=>compamny
 * @property string $type_discount 1=> discount % 2=>discount amount 3=>amount leave
 * @property int $discount
 * @property int $food_limit per day food promotion
 * @property string $start_date start date
 * @property string $end_date end date
 *
 * @property PromotionType $typePromotion
 * @property PromotionLimit[] $promotionLimits
 */
class Promotion extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public $date;
    
    public static function tableName()
    {
        return 'promotion';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['type_promotion', 'type_discount', 'discount', 'food_limit', 'start_date', 'end_date'], 'required'],
            [['type_promotion', 'discount', 'food_limit'], 'integer'],
            [['type_discount'], 'string'],
            [['start_date', 'end_date'], 'string', 'max' => 25],
            [['type_promotion'], 'exist', 'skipOnError' => true, 'targetClass' => PromotionType::className(), 'targetAttribute' => ['type_promotion' => 'id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'type_promotion' => 'Type Promotion',
            'type_discount' => 'Type Discount',
            'discount' => 'Discount',
            'food_limit' => 'Food Limit',
            'start_date' => 'Start Date',
            'end_date' => 'End Date',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTypePromotion()
    {
        return $this->hasOne(PromotionType::className(), ['id' => 'type_promotion']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPromotionLimits()
    {
        return $this->hasMany(PromotionLimit::className(), ['pid' => 'id']);
    }
}
