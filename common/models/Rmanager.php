<?php

namespace common\models;

use Yii;
use yii\data\ActiveDataProvider;

/**
 * This is the model class for table "rmanager".
 *
 * @property integer $uid
 * @property string $User_Username
 * @property string $Rmanager_NRIC
 * @property integer $Rmanager_Approval
 * @property integer $Rmanager_DateTimeApplied
 * @property integer $Rmanager_DateTimeApproved
 */
class Rmanager extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'rmanager';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['uid','Rmanager_DateTimeApplied','User_Username','Rmanager_NRIC'], 'required'],
            [['uid', 'Rmanager_Approval', 'Rmanager_DateTimeApplied', 'Rmanager_DateTimeApproved'], 'integer'],
            [['User_Username'], 'string', 'max' => 255],
            [['Rmanager_NRIC'], 'string', 'max' => 12],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'uid' => 'Uid',
            'User_Username' => 'User  Username',
            'Rmanager_NRIC' => 'Rmanager  Nric',
            'Rmanager_Approval' => 'Rmanager  Approval',
            'Rmanager_DateTimeApplied' => 'Rmanager  Date Time Applied',
            'Rmanager_DateTimeApproved' => 'Rmanager  Date Time Approved',
        ];
    }

    public function search($params)
    {
        $query = self::find()->orderby('Rmanager_DateTimeApplied DESC');

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);

        return $dataProvider;

    }
}
