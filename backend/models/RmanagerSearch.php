<?php
namespace backend\models;

use common\models\Rmanager;
use yii\data\ActiveDataProvider;





class RmanagerSearch extends Rmanager
{	
	public function rules()
	{
		return[
			[['uid','User_Username','Rmanager_NRIC','Rmanager_Approval','Rmanager_DateTimeApplied'],'safe'],
		];
	}

	public function search($params)
	{
		$query = Rmanager::find();

		$dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort'  => ['defaultOrder' =>['uid' => SORT_DESC]]
        ]);

        $this->load($params);

        $query->andFilterWhere(['like','uid',$this->uid]);
        $query->andFilterWhere(['like','LOWER(User_Username)',strtolower($this->User_Username)]);
        $query->andFilterWhere(['like','Rmanager_NRIC',$this->Rmanager_NRIC]);
		$query->andFilterWhere(['Rmanager_Approval' => $this->Rmanager_Approval]);

        if(!empty($this->Rmanager_DateTimeApplied))
        {
        	$date = explode("to", $this->Rmanager_DateTimeApplied);
        	$first = strtotime($date[0]. ' 00:00:00');
        	$last = strtotime($date[1]. ' 23:59:59');
        	$query->andWhere(['between','Rmanager_DateTimeApplied',$first,$last]);
        	
        }
        return $dataProvider;
	}
}

