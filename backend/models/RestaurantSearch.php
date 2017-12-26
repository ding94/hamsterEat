<?php
namespace backend\models;

use common\models\Restaurant;
use yii\data\ActiveDataProvider;

class RestaurantSearch extends Restaurant
{
	public $area;
	public $approve;

	public function rules()
	{
		return[
			[['Restaurant_ID','Restaurant_Manager','Restaurant_Name','Restaurant_Status','approve','area','Restaurant_Area','Restaurant_LicenseNo','Restaurant_Rating','Restaurant_DateTimeCreated'],'safe'],
		];
	}

	public function search($params,$case = 1)
	{
		$query = Restaurant::find();

		if ($case == 2) {
			$query->orderby('Restaurant_DateTimeCreated DESC');
		}

		$query->joinWith(['area','manager']);

		$dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

		$dataProvider->sort->attributes['area'] = [
	        'asc' => ['area.Area_State' => SORT_ASC],
	        'desc' => ['area.Area_State' => SORT_DESC],
	    ];

	    $dataProvider->sort->attributes['approve'] = [
	        'asc' => ['rmanager.Rmanager_Approval' => SORT_ASC],
	        'desc' => ['rmanager.Rmanager_Approval' => SORT_DESC],
	    ];

        $this->load($params);

        $query->andFilterWhere([
            'Restaurant_ID' => $this->Restaurant_ID,
            'Restaurant_Status' => $this->Restaurant_Status,
            'area.Area_State' => $this->area,
            'rmanager.Rmanager_Approval' => $this->approve,
        ]);

        $query->andFilterWhere(['like','Restaurant_ID' ,$this->Restaurant_ID]);
        $query->andFilterWhere(['like','Restaurant_Name' ,$this->Restaurant_Name]);
        $query->andFilterWhere(['like','Restaurant_Manager' ,$this->Restaurant_Manager]);
        $query->andFilterWhere(['like','Restaurant_Area' ,$this->Restaurant_Area]);
        $query->andFilterWhere(['like','Restaurant_Status' ,$this->Restaurant_Status]);
        $query->andFilterWhere(['like','Restaurant_LicenseNo' ,$this->Restaurant_LicenseNo]);
        $query->andFilterWhere(['like','Restaurant_Rating' ,$this->Restaurant_Rating]);
        $query->andFilterWhere(['like','FROM_UNIXTIME(Restaurant_DateTimeCreated, "%Y-%m-%d")' ,$this->Restaurant_DateTimeCreated]);

        return $dataProvider;
	}
}

