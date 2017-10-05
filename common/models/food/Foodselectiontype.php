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
            [[ 'TypeName', 'Min', 'Max'], 'required'],
            [['ID','Food_ID', 'Min', 'Max'], 'integer'],
            [['TypeName'], 'string'],
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
            'TypeName' => 'Type Name',
            'Min' => 'Min',
            'Max' => 'Max',
        ];
    }

    public function getfoodSelection()
    {
        return $this->hasMany(Foodselection::className(),['Type_ID' => 'ID']);
        
    }
}
