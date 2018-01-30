<?php

namespace common\models\vouchers;

use Yii;

/**
 * This is the model class for table "vouchers_used".
 *
 * @property int $id
 * @property int $vid
 * @property int $uid
 * @property int $did
 * @property int $usedDate
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
            [['vid', 'uid', 'did', 'usedDate'], 'required'],
            [['vid', 'uid', 'did', 'usedDate'], 'integer'],
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
            'did' => 'Did',
            'usedDate' => 'Used Date',
        ];
    }
}
