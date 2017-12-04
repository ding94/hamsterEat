<?php

namespace common\models\Company;

use Yii;

/**
 * This is the model class for table "company_employees".
 *
 * @property integer $id
 * @property integer $cid
 * @property integer $uid
 * @property integer $status
 * @property string $created_at
 */
class CompanyEmployees extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'company_employees';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['cid', 'uid'], 'required'],
            [['cid', 'uid', 'status'], 'integer'],
            [['created_at'], 'safe'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'cid' => 'Cid',
            'uid' => 'Uid',
            'status' => 'Status',
            'created_at' => 'Created At',
        ];
    }
}
