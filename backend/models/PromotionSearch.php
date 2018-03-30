<?php
namespace backend\models;

use yii\data\ActiveDataProvider;
use common\models\promotion\Promotion;

Class PromotionSearch extends Promotion
{
	public $first;
	public $last;


	public function rules()
    {
        return [
           [['type_promotion','type_discount','discount','food_limit','first','last'],'safe'],
        ];
    }
	public function search($params)
	{
		$query = Promotion::find()->joinWith(['typePromotion']);

		$dataProvider = new ActiveDataProvider([
			'query' => $query,	
		]);

		$this->load($params);
		
		$query->andFilterWhere([
           'type_promotion' => $this->type_promotion,
           'type_discount' => $this->type_discount,
           'discount' => $this->discount,
           'food_limit' => $this->food_limit,
        ]);

        if(!empty($this->first))
        {
        	$date = explode(" to ", $this->first);
        	
        	$query->andWhere(['between','start_date',$date[0],$date[1]]);
        }

        if(!empty($this->last))
        {
        	$date = explode(" to ", $this->last);
        	
        	$query->andWhere(['between','end_date',$date[0],$date[1]]);
        }

		return $dataProvider;
	}
}