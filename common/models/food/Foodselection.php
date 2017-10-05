<?php

namespace common\models\food;

use Yii;

/**
 * This is the model class for table "foodselection".
 *
 * @property integer $ID
 * @property integer $Type_ID
 * @property string $Name
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
            [[ 'Name', 'Price', 'Nickname'], 'required'],
            [['Type_ID', 'Status', 'Food_ID'], 'integer'],
            [['Name', 'Nickname'], 'string'],
            [['Price'], 'number'],
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
            'Name' => 'Name',
            'Price' => 'Price',
            'Status' => 'Status',
            'Nickname' => 'Nickname',
            'Food_ID' => 'Food  ID',
        ];
    }

    public function getTypeprice()
    {
        return $this->Name.'&nbsp; (+ RM'.$this->Price.')';
    }
}
