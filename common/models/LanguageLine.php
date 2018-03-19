<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "language_line".
 *
 * @property int $id
 * @property string $name
 * @property string $iso_639-1
 * @property int $activation 0=deactive, 1=activated
 * @property int $excel_line
 */
class LanguageLine extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'language_line';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name', 'iso_639-1', 'activation', 'excel_column'], 'required'],
            [['name'], 'string'],
            [['activation', 'excel_column'], 'integer'],
            [['iso_639-1'], 'string', 'max' => 2],
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
            'iso_639-1' => 'Iso 639 1',
            'activation' => 'Activation',
            'excel_column' => 'Excel Column',
        ];
    }
}
