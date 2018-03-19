<?php

namespace common\models\food;

use Yii;

/**
 * This is the model class for table "food_change_log".
 *
 * @property int $id
 * @property int $fid
 * @property int $rid
 * @property string $description
 * @property string $created_at
 */
class FoodChangeLog extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'food_change_log';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['fid', 'rid', 'description', 'created_at'], 'required'],
            [['fid', 'rid'], 'integer'],
            [['description'], 'string'],
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
            'fid' => 'Fid',
            'rid' => 'Rid',
            'description' => 'Description',
            'created_at' => 'Created At',
        ];
    }
}
