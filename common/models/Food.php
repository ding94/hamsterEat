<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "food".
 *
 * @property integer $Food_ID
 * @property integer $Restaurant_ID
 * @property string $Food_Name
 * @property string $Food_Halal
 * @property string $Food_Type
 * @property double $Food_Price
 * @property string $Food_Desc
 * @property string $Food_FoodPicPath
 * @property integer $FoodRating
 * @property integer $Food_TotalBought
 * @property integer $Food_TotalRated
 */
class Food extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'food';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['Restaurant_ID', 'FoodRating', 'Food_TotalBought', 'Food_TotalRated'], 'integer'],
            [['Food_Price'], 'number'],
            [['Food_Name', 'Food_Halal', 'Food_Type', 'Food_Desc', 'Food_FoodPicPath'], 'string', 'max' => 255],
            [['Food_Type'],'safe']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'Food_ID' => 'Food  ID',
            'Restaurant_ID' => 'Restaurant  ID',
            'Food_Name' => 'Food  Name',
            'Food_Halal' => 'Food  Halal',
            'Food_Type' => 'Food  Type',
            'Food_Price' => 'Food  Price',
            'Food_Desc' => 'Food  Desc',
            'Food_FoodPicPath' => 'Food  Food Pic Path',
            'FoodRating' => 'Food Rating',
            'Food_TotalBought' => 'Food  Total Bought',
            'Food_TotalRated' => 'Food  Total Rated',
        ];
    }
}
