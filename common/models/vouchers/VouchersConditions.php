<?php

namespace common\models\vouchers;

use Yii;

/**
 * This is the model class for table "vouchers_conditions".
 *
 * @property integer $id
 * @property string $description
 */
class VouchersConditions extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'vouchers_conditions';
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
