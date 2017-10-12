<?php

namespace common\models\Account;

use Yii;

/**
 * This is the model class for table "memberpoint".
 *
 * @property integer $id
 * @property integer $uid
 * @property integer $point
 * @property integer $positive
 * @property integer $negative
 */
class Memberpoint extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'memberpoint';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['uid', 'point', 'positive', 'negative'], 'required'],
            [['uid', 'point', 'positive', 'negative'], 'integer'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'uid' => 'Uid',
            'point' => 'Point',
            'positive' => 'Positive',
            'negative' => 'Negative',
        ];
    }
}
