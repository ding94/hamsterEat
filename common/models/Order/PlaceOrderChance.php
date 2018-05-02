<?php

namespace common\models\Order;

use Yii;
use yii\db\ActiveRecord;
use yii\data\ActiveDataProvider;
/**
 * This is the model class for table "place_order_chance".
 *
 * @property int $id
 * @property int $uid
 * @property int $cancel_did
 * @property int $chances
 * @property int $start_time
 * @property int $end_time
 */
class PlaceOrderChance extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'place_order_chance';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['uid', 'chances', 'start_time', 'end_time'], 'required'],
            [['uid', 'cancel_did', 'chances'], 'integer'],
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
            'cancel_did' => 'Cancel Did',
            'chances' => 'Chances',
            'start_time' => 'Start Time',
            'end_time' => 'End Time',
        ];
    }

    public function search($params,$action)
    {
       switch ($action) {
           case 1:
               $query = self::find()->andWhere(['>=','chances',1])->andWhere(['<=','start_time',strtotime(date('Y-m-d'))])->andWhere(['>','end_time',strtotime(date('Y-m-d'))]);
               break;
           
           default:
               $query = self::find();
               break;
       }
        

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);

        return $dataProvider;
    }
}
