<?php

namespace common\models\Package;

use Yii;

/**
 * This is the model class for table "user_subscribe_type".
 *
 * @property integer $id
 * @property string $description
 * @property string $sub_period
 * @property string $next_payment
 * @property integer $times
 */
class UserSubscribeType extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'user_subscribe_type';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['description', 'sub_period', 'next_payment', 'times'], 'required'],
            [['description'], 'string'],
            [['times'], 'integer'],
            [['sub_period', 'next_payment'], 'string', 'max' => 25],
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
            'sub_period' => 'Sub Period',
            'next_payment' => 'Next Payment',
            'times' => 'Times',
        ];
    }
}
