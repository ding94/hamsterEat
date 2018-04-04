<?php

namespace common\models\promotion;

use Yii;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "promotion_user_used".
 *
 * @property int $id base on promotion id
 * @property int $uid base on user id
 * @property int $created_at
 * @property int $updated_at
 */
class PromotionUserUsed extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'promotion_user_used';
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
            [['id', 'uid'], 'required'],
            [['id', 'uid', 'created_at', 'updated_at','did'], 'integer'],
            [['id', 'uid'], 'unique', 'targetAttribute' => ['id', 'uid']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'uid' => 'Uid',
            'did' => 'Did',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }
}
