<?php

namespace common\models\notic;

use Yii;

/**
 * This is the model class for table "notifcation_type".
 *
 * @property int $id
 * @property string $name
 * @property string $structure
 * @property string $description
 * @property string $url
 */
class NotifcationType extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'notifcation_type';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'name', 'structure', 'description', 'url'], 'required'],
            [['id'], 'integer'],
            [['structure', 'url'], 'string'],
            [['name', 'description'], 'string', 'max' => 50],
            [['id'], 'unique'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => 'Name',
            'structure' => 'Structure',
            'description' => 'Description',
            'url' => 'Url',
        ];
    }
}
