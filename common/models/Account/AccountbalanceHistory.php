<?php

namespace common\models\Account;

use Yii;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;
use yii\data\ActiveDataProvider;

/**
 * This is the model class for table "accountbalance_history".
 *
 * @property integer $id
 * @property integer $abid
 * @property integer $type
 * @property string $description
 * @property double $amount
 * @property integer $created_at
 * @property integer $updated_at
 */
class AccountbalanceHistory extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'accountbalance_history';
    }

  

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['abid', 'type', 'description', 'amount','system_type'], 'required'],
            [['abid', 'type'], 'integer'],
            [['description','system_type'], 'string'],
			[['created_at','updated_at'],'safe'],
            [['amount'], 'number'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'abid' => 'Abid',
            'type' => 'Type',
            'system_type' => 'Type',
            'description' => 'Description',
            'amount' => 'Amount',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }
	
	  public function search($params)
    {
            
			//  $query = self::find()->where('uid = :uid' ,[':uid' => Yii::$app->user->identity->id]); //自己就是table,找一找资料
		$account = Accountbalance::find()->where('User_Username = :name',[':name' => Yii::$app->user->identity->username])->one();
		$query = self::find()->where('abid = :aid',[':aid' => $account->AB_ID]);
       
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);
        $query->andFilterWhere(['like','created_at' ,  $this->created_at])
                ->andFilterWhere(['like','description' ,  $this->description])
                ->andFilterWhere(['like','type' ,  $this->type])
                ->andFilterWhere(['like','amount' ,  $this->amount]);
			    
        return $dataProvider;
    }
}
