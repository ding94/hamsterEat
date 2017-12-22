<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "referral".
 *
 * @property integer $id
 * @property string $new_user
 * @property string $referral
 */
class Referral extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'referral';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['new_user', 'referral'], 'required'],
            [['new_user', 'referral'], 'string', 'max' => 100],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'new_user' => 'New User',
            'referral' => 'Referral',
        ];
    }
}
