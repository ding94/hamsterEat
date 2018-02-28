<?php

namespace common\models\food;

use Yii;

/**
 * This is the model class for table "foodselectiontype".
 *
 * @property integer $ID
 * @property integer $Food_ID
 * @property string $TypeName
 * @property integer $Min
 * @property integer $Max
 */
class Foodselectiontype extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */

    public static function tableName()
    {
        return 'foodselectiontype';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['Min'], 'required','message'=>Yii::t('food','Min Choice').Yii::t('common',' cannot be blank.')],
            [['Max'],'required','message'=>Yii::t('food','Max Choice').Yii::t('common',' cannot be blank.')],
            [['ID','Food_ID', 'Min', 'Max'], 'integer'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'ID' => 'ID',
            'Food_ID' => 'Food  ID',
            'Min' => 'Min Choice',
            'Max' => 'Max Choice',
            'enName' => 'Name',
        ];
    }



    public function getFoodSelection()
    {
        return $this->hasMany(Foodselection::className(),['Type_ID' => 'ID']);
    }

    public function getTransName()
    {
        return $this->hasOne(FoodSelectiontypeName::className(),['id'=>'ID'])->andOnCondition(['language' => 'en']);
    }

    public function getAllName()
    {
        return $this->hasMany(FoodSelectiontypeName::className(),['id'=>'ID']);
    }

    public function getCookieName()
    {
        $cookies = Yii::$app->request->cookies;
        $language = $cookies->getValue('language', 'value');
       
        $data = FoodSelectiontypeName::find()->where("id = :id and language = :l",[':id'=>$this->ID,':l'=>$language])->one();
        if(empty($data))
        {
            $data = FoodSelectiontypeName::find()->where("id = :id and language = 'en'",[':id'=>$this->ID])->one();
        }
        return $data->translation;
    }

    public function getOriginName()
    {
        $data = FoodSelectiontypeName::find()->where("id = :id and language = 'en'",[':id'=>$this->ID])->one();
        return $data->translation;
    }
}
