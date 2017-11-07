<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "feedback".
 *
 * @property integer $Feedback_ID
 * @property string $User_Username
 * @property integer $Feedback_Category
 * @property string $Feedback_Message
 * @property string $Feedback_PicPath
 * @property integer $Feedback_DateTime
 * @property string $Feedback_Link
 */
class Feedback extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'feedback';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['User_Username', 'Feedback_Category', 'Feedback_Message', 'Feedback_DateTime', 'Feedback_Link'], 'required'],
            [['Feedback_Category', 'Feedback_DateTime'], 'integer'],
            [['Feedback_Message'], 'string'],
            [['User_Username', 'Feedback_PicPath', 'Feedback_Link'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'Feedback_ID' => 'Feedback  ID',
            'User_Username' => 'User  Username',
            'Feedback_Category' => 'Feedback  Category',
            'Feedback_Message' => 'Feedback  Message',
            'Feedback_PicPath' => 'Feedback  Pic Path',
            'Feedback_DateTime' => 'Feedback  Date Time',
            'Feedback_Link' => 'Feedback  Link',
        ];
    }
}
