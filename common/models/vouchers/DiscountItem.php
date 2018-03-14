<?php

namespace common\models\vouchers;

use Yii;

/**
 * This is the model class for table "discount_item".
 *
 * @property integer $id
 * @property string $description
 * @property string $type
 */
class DiscountItem extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'discount_item';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['description'], 'string'],
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
