<?php

namespace common\models\user;

use Yii;
use yii\db\ActiveRecord;
use yii\data\ActiveDataProvider;
use common\models\VouchersType;
use common\models\User;

class Usersearch extends \yii\db\ActiveRecord
{
	public function rules()
    {
        return [ ];
    }

    public function attributeLabels()
    {
        return [
            'id' => 'User ID',
            

        ];
    }

     public function search($params)
    {
        $query = User::find();
       
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

 
        $this->load($params);

        
        return $dataProvider;
    }
}
