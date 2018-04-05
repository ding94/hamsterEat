<?php

namespace common\models\food;
use frontend\controllers\CartController;
use frontend\modules\offer\controllers\PromotionController;
use Yii;

/**
 * This is the model class for table "foodselection".
 *
 * @property integer $ID
 * @property integer $Type_ID
 * @property string $Name
 * @property double $BeforeMarkedUp
 * @property double $Price
 * @property integer $Status
 * @property string $Nickname
 * @property integer $Food_ID
 */
class Foodselection extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */

    public static function tableName()
    {
        return 'foodselection';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['Nickname'], 'required','message'=>Yii::t('food','Nickname').Yii::t('common',' cannot be blank.')],
            [['BeforeMarkedUp'],'required','message'=>Yii::t('food','Before Marked Up').Yii::t('common',' cannot be blank.')],
            [['Type_ID', 'Status', 'Food_ID'], 'integer'],
            [['BeforeMarkedUp', 'Price'], 'number'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'ID' => 'ID',
            'Type_ID' => 'Type  ID',
            'BeforeMarkedUp' => 'Before Marked Up',
            'Price' => 'Price',
            'Status' => 'Status',
            'Nickname' => 'Nickname',
            'Food_ID' => 'Food  ID',
            'enName' => 'Name',
        ];
    }

    public function getTypeprice()
    {
        $price = $this->getDisPrice();
        $name = $this->getCookieName();
       
        return '<span class="foodselection-name">'.$name.'</span><span class="selection-price" data-price="'.CartController::actionRoundoff1decimal($price).'">RM'.CartController::actionRoundoff1decimal($price).'</span><span class="radio-custom-label"></span>';
    }

    public function getCheckboxtypeprice()
    { 
        $price = $this->getDisPrice();
        $name = $this->getCookieName();
        return '<span class="foodselection-name">'.$name.'</span><span class="selection-price" data-price="'.CartController::actionRoundoff1decimal($price).'">RM'.CartController::actionRoundoff1decimal($price).'</span><span class="checkbox-custom-label"></span>';
    }

    protected function getDisPrice()
    {
        $promotion = PromotionController::getPromotioinPrice($this->Price,$this->Food_ID,2);
       
        if(is_array($promotion))
        {
            return CartController::actionRoundoff1decimal($promotion['price']);
        }
        
        return $this->getEarlyPrice();
        
    }

    protected function getEarlyPrice()
    {
        if(time() < strtotime(date("Y/m/d 11:0:0")))
        {
            if ($this->Price != 0) {
                $discount = CartController::actionRoundoff1decimal($this->Price *0.15);
                $this->Price = $this->Price - $discount;
            }
        }
        return $this->Price;
    }

    public function getSelectedtpye()
    {
        return $this->hasOne(Foodselectiontype::className(),['ID'=>'Type_ID']);
    }

    public function getFood()
    {
        return $this->hasOne(Food::className(),['Food_ID'=>'Food_ID']);
    }

    public function getTransName()
    {
        return $this->hasOne(FoodSelectionName::className(),['id'=>'ID'])->andOnCondition(['language' => 'en']);
    }

    public function getAllName()
    {
        return $this->hasMany(FoodSelectionName::className(),['id'=>'ID']);
    }

    public function getCookieName()
    {
        $cookies = Yii::$app->request->cookies;
        $language = $cookies->getValue('language', 'value');

        $data = FoodSelectionName::find()->where("id = :id and language = :l",[':id'=>$this->ID,':l'=>$language])->one();
        if(empty($data))
        {
            return $this->getOriginName();
        }
        return $data->translation;
    }

    public function getOriginName()
    {
        $data = FoodSelectionName::find()->where("id = :id and language = 'en'",[':id'=>$this->ID])->one();
        return $data->translation;
    }
}
