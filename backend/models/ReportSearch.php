<?php

namespace backend\models;

use Yii;
use yii\data\ActiveDataProvider;
use common\models\Report\Report;


class ReportSearch extends Report
{	
	public $uid;

	public function rules()
	{	
		return[
			[['Report_ID','Report_Category','uid','Report_DateTime','Report_PersonReported'],'safe'],
		];
	}


	public function search($params)
    {	
        $query = Report::find()->orderBy('Report_ID DESC');
        $query->joinWith(['userid']);

        $dataProvider = new ActiveDataProvider(['query' => $query]);

        $dataProvider->sort->attributes['Order_ID'] = [
            'asc' => ['Report_ID' => SORT_ASC],
            'desc' => ['Report_ID' => SORT_DESC],
        ];

        $this->load($params);
      
        $query->andFilterWhere(['like','Report_ID' , $this->Report_ID]);
        $query->andFilterWhere(['like','user.username', $this->uid]);
        $query->andFilterWhere(['like','Report_PersonReported' , $this->Report_PersonReported]);
        $query->andFilterWhere(['like','Report_Category' , $this->Report_Category]);
        if(!empty($this->Report_DateTime))
        {
        	$date = explode("to", $this->Report_DateTime);
        	
        	$first = strtotime($date[0]. ' 00:00:00');
        
			
	        $last =strtotime($date[1]. ' 23:59:59');
	        	
        	
        	$query->andFilterWhere(['between','Report_DateTime',$first,$last]);
        }
     	
     
        return $dataProvider;
    }
}