<?php

namespace frontend\models;

use yii\data\ActiveDataProvider;
use yii\db\ActiveRecord;
use Yii;
use common\models\Account\AccounttopupStatus;

/**
 * This is the model class for table "accounttopup".
 *
 * @property integer $Account_TransactionID
 * @property string $User_Username
 * @property string $Account_ChosenBank
 * @property double $Account_TopUpAmount
 * @property integer $Account_TransactionDate
 * @property integer $Account_TransactionNumber
 * @property string $Account_ReceiptPicPath
 * @property integer $Account_SubmitDateTime
 */
class Accounttopup extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'accounttopup';
    }

    public function attributes()
    {
        return array_merge(parent::attributes(),['accounttopup_status.id','accounttopup_status.title']);
    }
    
    public function getAccounttopup_status()
    {
        return $this->hasOne(AccounttopupStatus::className(),['id' => 'Account_Action']); 
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['Account_TopUpAmount'], 'number'],
            [['Account_TransactionDate', 'Account_TransactionNumber', 'Account_SubmitDateTime','Account_Action','Account_ActionBefore'], 'integer'],
            [['User_Username', 'Account_ChosenBank', 'Account_ReceiptPicPath','Account_InCharge'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'Account_TransactionID' => 'Account  Transaction ID',
            'User_Username' => 'Username',
            'Account_ChosenBank' => 'Account  Chosen Bank',
            'Account_TopUpAmount' => 'Account  Top Up Amount',
            'Account_TransactionDate' => 'Account  Transaction Date',
            'Account_TransactionNumber' => 'Account  Transaction Number',
            'Account_ReceiptPicPath' => 'Account  Receipt Pic Path',
            'Account_SubmitDateTime' => 'Account  Submit Date Time',
        ];
    }

    public function search($params,$action)
    {
        
        if ($action == 0){
              $query = self::find(); //自己就是table,找一找资料
        }
        elseif ($action >=1){
            $query= self::find()->where('Account_Action = :act',[':act' =>$action]);
            
            //$query = OfflineTopupStatus::find()->where(['offlinetopupstatus.description' => $action]);

        }
        $query->joinWith(['accounttopup_status' ]);
        //$query->joinWith(['company']);

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);
        
           $query->andFilterWhere([
                'title' => $this->getAttribute('accounttopup_status.title'),
            ]);
            
          $query->andFilterWhere(['like','User_Username' ,  $this->User_Username])
                ->andFilterWhere(['like','Account_TopUpAmount' ,  $this->Account_TopUpAmount])
                ->andFilterWhere(['like','Account_ChosenBank' ,  $this->Account_ChosenBank])
                ->andFilterWhere(['like','Account_InCharge' ,  $this->Account_InCharge]);
        //var_dump($query);
        //$query->andFilterWhere(['like','cmpyName' , $this->company]);// 用来查找资料, (['方式','对应资料地方','资料来源'])

        //使用'or'寻找两边column资料
        //$query->andFilterWhere(['or',['like','Fname' , $this->Fname], ['like','Lname' , $this->Fname],]);
 
        return $dataProvider;
    }
}
