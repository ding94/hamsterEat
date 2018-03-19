<?php

namespace common\models\user;

use Yii;

/**
 * This is the model class for table "user_language".
 *
 * @property int $uid
 * @property int $language
 */
class UserLanguage extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'user_language';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['uid', 'language'], 'required'],
            [['uid', 'language'], 'integer'],
            [['uid'], 'unique'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'uid' => 'Uid',
            'language' => 'Language',
        ];
    }
}
