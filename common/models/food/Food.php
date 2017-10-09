<?php

namespace common\models\food;

use Yii;

/**
 * This is the model class for table "food".
 *
 * @property integer $Food_ID
 * @property integer $Restaurant_ID
 * @property double $Rating
 * @property integer $Sales
 * @property string $Name
 * @property double $Price
 * @property double $BeforeMarkedUp
 * @property string $Description
 * @property string $Ingredient
 * @property string $Nickname
 * @property string $PicPath
 * @property integer $created_at
 * @property integer $updated_at
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
            [['Restaurant_ID', 'Name', 'Price', 'Description', 'Ingredient', 'Nickname', 'PicPath', 'created_at', 'updated_at'], 'required'],
            [['Restaurant_ID', 'Sales', 'created_at', 'updated_at'], 'integer'],
            [['Rating', 'Price', 'BeforeMarkedUp'], 'number'],
            [['Name', 'Description', 'Ingredient', 'Nickname', 'PicPath'], 'string'],
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
            'Rating' => 'Rating',
            'Sales' => 'Sales',
            'Name' => 'Name',
            'Price' => 'Price',
            'BeforeMarkedUp' => 'Before Marked Up',
            'Description' => 'Description',
            'Ingredient' => 'Ingredient',
            'Nickname' => 'Nickname',
            'PicPath' => 'Pic Path',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }

    public function getFoodType()
    {
        return $this->hasMany(Foodtype::className(), ['ID'=>'Type_ID'])->viaTable('foodtypejunction', ['Food_ID'=>'Food_ID']);
    }

    public function getFoodStatus()
    {
        return $this->hasOne(Foodstatus::className(), ['Food_ID'=>'Food_ID']);
    }

    public function getFoodselectiontypes()
    {
        return $this->hasMany(Foodselectiontype::className(),['Food_ID' => 'Food_ID']);
        
    }

    public function getRoundprice()
    {
        return CartController::actionRoundoff1decimal($this->Price);
    }
}
