<?php

namespace common\models\Rating;

use Yii;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;
/**
 * This is the model class for table "foodrating".
 *
 * @property integer $id
 * @property integer $delivery_id
 * @property integer $Food_ID
 * @property integer $FoodRating_Rating
 * @property integer $User_Id
 * @property integer $created_at
 * @property integer $updated_at
 */
class Foodrating extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'foodrating';
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
            [['delivery_id', 'User_Id'], 'required'],
            [['delivery_id', 'Food_ID', 'FoodRating_Rating', 'User_Id', 'created_at', 'updated_at'], 'integer'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'delivery_id' => 'Delivery ID',
            'Food_ID' => 'Food  ID',
            'FoodRating_Rating' => 'Food Rating  Rating',
            'User_Id' => 'User  ID',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }
}
