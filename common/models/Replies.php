<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "replies".
 *
 * @property integer $Ticket_ID
 * @property string $Replies_ReplyContent
 * @property integer $Replies_DateTime
 * @property string $Replies_ReplyPerson
 * @property string $Replies_PicPath
 */
class Replies extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'replies';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['Replies_ReplyPerson'], 'required'],
            [['Replies_ReplyContent'],'required','message'=>Yii::t('ticket','Reply Content').Yii::t('common',' cannot be blank.')],
            [['Ticket_ID', 'Replies_ReplyPerson','Replies_DateTime'], 'integer'],
            [['Replies_ReplyContent',  'Replies_PicPath'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'Ticket_ID' => 'Ticket  ID',
            'Replies_ReplyContent' => 'Reply Content',
            'Replies_DateTime' => 'Replies Time',
            'Replies_ReplyPerson' => 'Reply Person',
            'Replies_PicPath' => 'Picture',
        ];
    }
}
