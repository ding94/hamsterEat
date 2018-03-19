<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "controllers_link".
 *
 * @property int $id
 * @property string $name
 * @property string $link
 * @property string $controller
 */
class ControllersLink extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'controllers_link';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name', 'link', 'controller'], 'required'],
            [['name', 'link', 'controller'], 'string'],
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
            'link' => 'Link',
            'controller' => 'Controller',
        ];
    }
}
