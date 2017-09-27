<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "vouchers_type".
 *
 * @property integer $id
 * @property string $description
 * @property string $type
 */
class VouchersType extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'vouchers_type';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['description'], 'required'],
            [['description', 'type'], 'string'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'description' => 'Description',
            'type' => 'Type',
        ];
    }
}
