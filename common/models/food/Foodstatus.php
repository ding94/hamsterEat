<?php

namespace common\models\food;

use Yii;

/**
 * This is the model class for table "foodstatus".
 *
 * @property integer $ID
 * @property integer $Food_ID
 * @property integer $Status
 * @property integer $StartTime
 * @property integer $StopTime
 */
class Foodstatus extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'foodstatus';
    }

    public function afterSave($insert, $changedAttributes)
    {
        if($this->Status == 0 || $this->Status == 1)
        {
            Foodselection::updateAll(['Status' => $this->Status], "Food_ID = :fid",[':fid'=>$this->Food_ID]);
        }
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['Food_ID', 'Status'], 'required'],
            [['Food_ID', 'Status', 'StartTime', 'StopTime'], 'integer'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'ID' => 'ID',
            'Food_ID' => 'Food  ID',
            'Status' => 'Status',
            'StartTime' => 'Start Time',
            'StopTime' => 'Stop Time',
        ];
    }

    public function search($params)
    {
        $query = self::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);

        return $dataProvider;

    }

    public function getSelection()
    {
        return $this->hasMany(Foodselection::className(),['Food_ID'=> 'Food_ID']);
    }

    public function getName()
    {
        $data = Food::findOne($this->Food_ID);
        return $data->Name;
    }

}
