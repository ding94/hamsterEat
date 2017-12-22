<?php

namespace backend\models\auth;

use Yii;

/**
 * This is the model class for table "admin_auth_rule".
 *
 * @property string $name
 * @property resource $data
 * @property integer $created_at
 * @property integer $updated_at
 *
 * @property AdminAuthItem[] $adminAuthItems
 */
class AdminAuthRule extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'admin_auth_rule';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name'], 'required'],
            [['data'], 'string'],
            [['created_at', 'updated_at'], 'integer'],
            [['name'], 'string', 'max' => 64],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'name' => 'Name',
            'data' => 'Data',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAdminAuthItems()
    {
        return $this->hasMany(AdminAuthItem::className(), ['rule_name' => 'name']);
    }
}
