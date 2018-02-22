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
            [['User_id','Ticket_Category'], 'required'],
            [['Ticket_Subject'],'required','message'=>Yii::t('ticket','Subject Title').Yii::t('common',' cannot be blank.')],
            [['Ticket_Content'],'required','message'=>Yii::t('ticket','Content').Yii::t('common',' cannot be blank.')],
            [['User_id','Ticket_Status','Ticket_DateTime'], 'integer'],
            [['Ticket_Subject', 'Ticket_Content', 'Ticket_Category',  'Ticket_PicPath'], 'string', 'max' => 255],
            [['ticket_status.description','Ticket_ID'], 'safe'],
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

        $query = self::find()->orderBy('Ticket_ID DESC');

        if (!empty($action)) {
            if ($action == 2) {
             $query = self::find()->andWhere('Ticket_Status <3')->orderBy('Ticket_ID DESC');
            }
            elseif ($action ==3 ) {
                $query = self::find()->andWhere('Ticket_Status =3')->orderBy('Ticket_ID DESC');
            }
        }
        

        $query->joinWith(['ticket_status']);
        $query->joinWith(['user']);
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);
        
        $this->load($params);

        $query->andFilterWhere(['like','Ticket_ID' , $this->Ticket_ID]);
        $query->andFilterWhere(['like','user.username' , $this->User_id]);
        $query->andFilterWhere(['like','Ticket_Category' , $this->Ticket_Category]);
        $query->andFilterWhere(['like','Ticket_Content' , $this->Ticket_Content]);
        $query->andFilterWhere(['like','description' , $this->getAttribute('ticket_status.description')]);

        return $dataProvider;
    }

    /*
    * use only for upload image
    */
    public function getImg()
    {
        $data = "";
        $ticket = Ticket::find()->where('fid = :id',[':id'=>$this->Food_ID])->all();
        foreach($images as $image)
        {
            $data[] =  Yii::getAlias('@web').'/'.Yii::$app->params['foodImg'].$image->img;
        }
        if(empty($data))
        {
            $data[] = Yii::$app->params['defaultFoodImg'];
        }
        return $data;
    }

    /*
    * use only for upload image
    */
    public function getCaptionImg()
    {
        $data = "";
        $images = FoodImg::find()->where('fid = :id',[':id'=>$this->Food_ID])->all();
        if(empty($images))
        {
            $data['data'][0]['caption'] = Yii::$app->params['defaultFoodImg'];
            $data['data'][0]['key'] = "0";
            $data['header'] = true;
        }
        else
        {
            foreach($images as $i=>$image)
            {
                $data['data'][$i]['caption'] =  $image->img;
                $data['data'][$i]['url'] = Url::to(['/food-img/delete','id'=>$image->id]);
                $data['data'][$i]['key'] = $image->id;
            }
            $data['header'] = false;
        }
        return $data;
    }

    public function getUser()
    {
        return $this->hasOne(User::className(),['id' => 'User_id']); 
    }
}
