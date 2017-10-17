<?php

namespace common\models\Package;

use Yii;

/**
 * This is the model class for table "user_package_selection_type".
 *
 * @property integer $id
 * @property integer $packagedid
 * @property integer $selectionitypeId
 * @property integer $quantity
 * @property integer $created_at
 * @property integer $updated_at
 */
class UserPackageSelectionType extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'user_package_selection_type';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['packagedid', 'selectionitypeId', 'quantity', 'created_at', 'updated_at'], 'required'],
            [['packagedid', 'selectionitypeId', 'quantity', 'created_at', 'updated_at'], 'integer'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'packagedid' => 'Packagedid',
            'selectionitypeId' => 'Selectionitype ID',
            'quantity' => 'Quantity',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }
}
