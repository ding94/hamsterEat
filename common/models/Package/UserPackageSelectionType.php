<?php

namespace common\models\Package;

use Yii;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;


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
    public $check;

    public static function tableName()
    {
        return 'user_package_selection_type';
    }

    public function behaviors()
    {
        return [
            TimestampBehavior::className(),
            'timestamp' => [
                'class' => 'yii\behaviors\TimestampBehavior',
                'attributes' => [
                    ActiveRecord::EVENT_BEFORE_INSERT => ['created_at','updated_at'],
                    ActiveRecord::EVENT_BEFORE_UPDATE => ['updated_at'],
                ],
            ], 
        ];
    }
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['packagedid', 'selectionitypeId'], 'required'],  
            [['packagedid', 'selectionitypeId', 'created_at', 'updated_at'], 'integer'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'check' => '',
            'packagedid' => 'Packagedid',
            'selectionitypeId' => 'Selectionitype ID',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }
}
