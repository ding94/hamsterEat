<?php

namespace common\models\Order;

use Yii;

/**
 * This is the model class for table "delivery_recipient_type".
 *
 * @property integer $id
 * @property string $description
 */
class DeliveryRecipientType extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'delivery_recipient_type';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['description'], 'required'],
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
        ];
    }
}
