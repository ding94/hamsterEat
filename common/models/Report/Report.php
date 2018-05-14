<?php

namespace common\models\Report;

use Yii;
use yii\data\ActiveDataProvider;
use common\models\User;

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
            [['Report_Category','Report_PersonReported'],'required'],
            [['Report_Reason'],'required','message'=>Yii::t('common','Reason').Yii::t('common',' cannot be blank.')],
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

    public function getUserid()
    {
        return $this->hasOne(User::classname(),['id' =>'uid']);
    }
}
