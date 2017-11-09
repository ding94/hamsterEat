<?php
namespace backend\models;

use common\models\Orders;
use common\models\Orderitem;
use yii\data\ActiveDataProvider;
use common\models\Orderitemstatuschange;
use common\models\Ordersstatuschange;
use common\models\Orderitemselection;

Class OrderitemSearch extends Orders
{
	public $Order_ID;
	public $food;
	public $food_selection;
	public $userfullname;
	public $usercontact;
	public function rules()
    {
        return [
            [['Order_ID','userfullname','usercontact','food','order_selection'],'safe'],
        ];
    }

	public function search($params,$case)
	{
		switch ($case) {
			case 1:
				$query = Orderitem::find()->where('Orders_Status=:s',[':s'=>'Problematic'])->andWhere('Orders_DateTimeMade > '.strtotime(date('Y-m-d')));
				$query->joinWith(['order']);
				$query->joinWith(['food']);
				break;
			default:
				# code...
				break;
		}
		
		$dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $dataProvider->sort->attributes['userfullname'] = [
	        'asc' => ['User_fullname' => SORT_ASC],
	        'desc' => ['User_fullname' => SORT_DESC],
	    ];

	    $dataProvider->sort->attributes['usercontact'] = [
	        'asc' => ['User_contactno' => SORT_ASC],
	        'desc' => ['User_contactno' => SORT_DESC],
	    ];

	    $dataProvider->sort->attributes['food'] = [
	        'asc' => ['Name' => SORT_ASC],
	        'desc' => ['Name' => SORT_DESC],
	    ];

        $this->load($params);

        $query->andFilterWhere(['like','Order_ID',$this->Order_ID]);
        $query->andFilterWhere(['like','User_fullname',$this->userfullname]);
        $query->andFilterWhere(['like','User_contactno',$this->usercontact]);
        $query->andFilterWhere(['like','Name',$this->food]);

        return $dataProvider;
	}
}