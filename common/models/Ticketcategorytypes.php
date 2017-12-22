<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "ticketcategorytypes".
 *
 * @property integer $Category_ID
 * @property string $Category_Name
 * @property string $Category_Desc
 */
class Ticketcategorytypes extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'ticketcategorytypes';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['Category_Name', 'Category_Desc'], 'required'],
            [['Category_Name', 'Category_Desc'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'Category_ID' => 'Category  ID',
            'Category_Name' => 'Category  Name',
            'Category_Desc' => 'Category  Desc',
        ];
    }
}
