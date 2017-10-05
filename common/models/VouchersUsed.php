<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "vouchers_used".
 *
 * @property integer $id
 * @property integer $vid
 * @property integer $uid
 * @property integer $usedDate
 */
class VouchersUsed extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'vouchers_used';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['vid', 'uid', 'usedDate'], 'required'],
            [['vid', 'uid', 'usedDate'], 'integer'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'vid' => 'Vid',
            'uid' => 'Uid',
            'usedDate' => 'Used Date',
        ];
    }
}
