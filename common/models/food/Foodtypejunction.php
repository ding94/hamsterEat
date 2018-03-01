<?php

namespace common\models\food;

use Yii;
use common\models\Restaurant;

/**
 * This is the model class for table "foodtypejunction".
 *
 * @property integer $ID
 * @property integer $Food_ID
 * @property integer $Type_ID
 */
class Foodtypejunction extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'foodtypejunction';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['Food_ID', 'Type_ID'], 'required'],
            [['Food_ID', 'Type_ID'], 'integer'],
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
            'Type_ID' => 'Type  ID',
        ];
    }

    public function getRestaurantID()
    {
        $ID = Food::find()->where("Food_ID = :Food_ID",[':Food_ID'=>$this->Food_ID])->one();
        
        return $ID->Restaurant_ID;
    }

    public function getTypeName()
    {
        $name = FoodType::find()->where('ID = :ID',[':ID' => $this->Type_ID])->one();
        return $name->Type_Desc;
    }

    public function afterSave($insert,$changedAttributes)
    {
        $fid = $this->Food_ID;
        $rid = $this->getRestaurantID();
        $log = new FoodChangeLog;
        $log->fid = $fid;
        $log->rid = $rid;
        $log->created_at = new \yii\db\Expression('NOW()');
        $log->description = 'Food Type changed to ' . $this->getTypeName();
        $log->save();
    }
}
