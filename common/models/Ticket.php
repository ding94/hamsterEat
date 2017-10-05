<?php

namespace common\models;

use Yii;
use yii\db\ActiveRecord;
use yii\data\ActiveDataProvider;
use common\models\Replies;
use common\models\TicketStatus;
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

    public function getAdminreply()
    {
        return $this->hasMany(Replies::className(),['Ticket_ID' => 'Ticket_ID'])->andOnCondition(['Replies_ReplyBy' => 2]);; 
    }

    public function attributes()
    {
        return array_merge(parent::attributes(),['ticket_status.description']);
    }

    public function getTicket_status()
    {
        return $this->hasOne(TicketStatus::className(),['id' => 'Ticket_Status']); 
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['User_id','Ticket_Subject','Ticket_Content','Ticket_Category'], 'required'],
            [['User_id','Ticket_Status','Ticket_DateTime'], 'integer'],
            [['Ticket_Subject', 'Ticket_Content', 'Ticket_Category',  'Ticket_PicPath'], 'string', 'max' => 255],
            [['ticket_status.description'], 'safe'],
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

        if (!empty($action)) {
            if ($action == 2) {
             $query = self::find()->andWhere('Ticket_Status <3')->orderBy('Ticket_ID DESC');
            }
            elseif ($action ==3 ) {
                $query = self::find()->andWhere('Ticket_Status =3')->orderBy('Ticket_ID DESC');
            }
        }
        

        $query->joinWith(['ticket_status']);
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);

        $query->andFilterWhere(['like','description' , $this->getAttribute('ticket_status.description')]);



        return $dataProvider;
    }
}
