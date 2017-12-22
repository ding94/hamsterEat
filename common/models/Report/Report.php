<?php

namespace common\models\Report;

use Yii;
use yii\data\ActiveDataProvider;

/**
 * This is the model class for table "report".
 *
 * @property integer $Report_ID
 * @property string $User_Username
 * @property string $Report_Category
 * @property string $Report_Reason
 * @property string $Report_PersonReported
 * @property integer $Report_DateTime
 */
class Report extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'report';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['Report_Category','Report_Reason','Report_PersonReported'],'required'],
            [['User_Username', 'Report_Category', 'Report_Reason', 'Report_PersonReported'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'Report_ID' => 'Report  ID',
            'User_Username' => 'User  Username',
            'Report_Category' => 'Category',
            'Report_Reason' => 'Reason',
            'Report_PersonReported' => 'Reported User',
            'Report_DateTime' => 'Report  Date Time',
        ];
    }

    public function search($params)
    {
        $query = self::find();

        $dataProvider = new ActiveDataProvider(['query' => $query,
        ]);
        $this->load($params);

        // $query->andFilterWhere([
        //     'title' => $this->getAttribute('accounttopup_status.title'),
        // ]);

        // $query->andFilterWhere(['like','acc_name' ,  $this->acc_name])
        //         ->andFilterWhere(['like','withdraw_amount' ,  $this->withdraw_amount])
        //         ->andFilterWhere(['like','to_bank' ,  $this->to_bank])
        //        // ->andFilterWhere(['like','bank_name' ,  $this->bank_name])
        //         ->andFilterWhere(['like',Bank::tableName().'.Bank_Name' , $this->getAttribute('bank.Bank_Name')])
        //         ->andFilterWhere(['like','inCharge' ,  $this->inCharge])
        //         ->andFilterWhere(['like','reason' ,  $this->reason]);
        return $dataProvider;
    }
}
