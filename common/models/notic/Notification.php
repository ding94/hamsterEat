<?php

namespace common\models\notic;

use Yii;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;
use common\models\Order\Orderitem;
use yii\helpers\Url;


/**
 * This is the model class for table "notification".
 *
 * @property int $id
 * @property int $uid user id
 * @property int $rid can be restaurant id or delivery id base on type
 * @property int $type notication_setting type
 * @property string $description
 * @property int $view 0-> not read 1 =>read
 * @property int $created_at
 * @property int $updated_at
 */
class Notification extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'notification';
    }

    public function behaviors()
    {
        return [
            TimestampBehavior::className(),
            'timestamp' => [
                'class' => 'yii\behaviors\TimestampBehavior',
                'attributes' => [
                    ActiveRecord::EVENT_BEFORE_INSERT => ['created_at','updated_at'],
                    ActiveRecord::EVENT_BEFORE_UPDATE => ['updated_at'],
                ],
            ],   
        ];
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['uid', 'tid', 'type', 'description'], 'required'],
            ['view','default','value'=>0],
            [['uid', 'tid', 'type', 'view'], 'integer'],
            [['description'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'uid' => 'Uid',
            'tid' => 'Tid',
            'type' => 'Type',
            'description' => 'Description',
            'view' => 'View',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }

    public function getName()
    {
        $keyword = self::getWord();
        if($keyword == 'foodname')
        {
            $item = Orderitem::find()->where('Order_ID = :id ',[':id'=>$this->tid])->joinWith(['food'])->one();
            $word = \Yii::t('food',$this->description,[$keyword=>$item->food->cookieName]);
        }
        else
        {
            $word = \Yii::t('food',$this->description,['id'=>$this->tid]);
        }
       return $word;
    }

    public function getUrl()
    {
        $keyword = self::getWord();
        $originUrl = NotificationType::findOne($this->type);
        $did = $this->tid;
        if($keyword == 'foodname')
        {
            $item = Orderitem::findOne($this->tid);
            $did = $item->Delivery_ID;
        }
      
        $url = Url::to([$originUrl->url,'did'=>$did]);
        return $url;
    }

    private function getWord()
    {
        $text = explode("{",$this->description);
        $keyword = explode("}",$text[1]);
        return $keyword[0];
    }
}
