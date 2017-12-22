<?php

namespace common\models\Order;

use Yii;

/**
 * This is the model class for table "status_type".
 *
 * @property integer $id
 * @property string $type
 * @property string $description
 * @property string $label
 */
class StatusType extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'status_type';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['type', 'description'], 'required'],
            [['type', 'description', 'label'], 'string'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'type' => 'Type',
            'description' => 'Description',
            'label' => 'Label',
        ];
    }

    public function getOrder_status()
    {
        return $this->hasOne(Orders::className(),['Orders_Status'=> 'id']);
    }
}
