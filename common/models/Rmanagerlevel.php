<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "rmanagerlevel".
 *
 * @property integer $id
 * @property string $User_Username
 * @property string $Restaurant_ID
 * @property string $RmanagerLevel_Level
 * @property integer $Rmanager_DateTimeAdded
 */
class Rmanagerlevel extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'rmanagerlevel';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['User_Username', 'Restaurant_ID', 'RmanagerLevel_Level', 'Rmanager_DateTimeAdded'], 'required'],
            [['Rmanager_DateTimeAdded'], 'integer'],
            [['User_Username', 'Restaurant_ID', 'RmanagerLevel_Level'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'User_Username' => 'User  Username',
            'Restaurant_ID' => 'Restaurant  ID',
            'RmanagerLevel_Level' => 'Rmanager Level  Level',
            'Rmanager_DateTimeAdded' => 'Rmanager  Date Time Added',
        ];
    }

    public function getManager()
    {
        return $this->hasOne(Rmanager::className(),['User_Username' =>'User_Username']);
    }

    public function getRestaurant()
    {
        return $this->hasOne(Restaurant::className(),['Restaurant_Manager' => 'User_Username']);
    }
}
