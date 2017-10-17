<?php

namespace common\models\Package;

use Yii;

/**
 * This is the model class for table "user_package_subscription".
 *
 * @property integer $pid
 * @property string $end_period
 * @property string $next_payment
 */
class UserPackageSubscription extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'user_package_subscription';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['pid', 'end_period', 'next_payment'], 'required'],
            [['pid'], 'integer'],
            [['end_period', 'next_payment'], 'safe'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'pid' => 'Pid',
            'end_period' => 'End Period',
            'next_payment' => 'Next Payment',
        ];
    }
}
