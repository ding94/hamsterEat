<?php

namespace common\models;

use Yii;
use yii\db\ActiveRecord;
use yii\data\ActiveDataProvider;

/**
 * This is the model class for table "ticket".
 *
 * @property integer $Ticket_ID
 * @property string $User_Username
 * @property string $Ticket_Subject
 * @property string $Ticket_Content
 * @property string $Ticket_Category
 * @property integer $Ticket_DateTime
 * @property string $Ticket_Status
 * @property string $Ticket_PicPath
 */
class Ticket extends \yii\db\ActiveRecord
{ 
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'ticket';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
        [['User_Username','Ticket_Subject','Ticket_Content','Ticket_Category'], 'required'],
            [['Ticket_Status','Ticket_DateTime'], 'integer'],
            [['User_Username', 'Ticket_Subject', 'Ticket_Content', 'Ticket_Category',  'Ticket_PicPath'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'Ticket_ID' => 'Ticket  ID',
            'User_Username' => 'User  Username',
            'Ticket_Subject' => 'Subject Title',
            'Ticket_Content' => 'Content',
            'Ticket_Category' => 'Ticket  Category',
            'Ticket_DateTime' => 'Ticket  Date Time',
            'Ticket_Status' => 'Ticket  Status',
            'Ticket_PicPath' => 'Picture',
        ];
    }

    public function search($params,$action)
    {

        $query = self::find();
    
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);

        return $dataProvider;
    }
}
