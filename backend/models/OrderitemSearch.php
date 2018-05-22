<?php
namespace backend\models;

use common\models\Order\Orders;
use common\models\Order\Orderitem;
use yii\data\ActiveDataProvider;
use common\models\Order\Orderitemstatuschange;
use common\models\Order\Ordersstatuschange;
use common\models\Order\Orderitemselection;
use common\models\problem\ProblemOrder;

Class OrderitemSearch extends Orders
{
	public $Order_ID;
    public $reasons;
    public $foodName;
    public $foodSelect;
    public $description;
    public $name;
    public $status;
    public $contactno;

	public function rules()
    {
        return [
            //[['userfullname','usercontact','food','order_selection'],'safe'],
            [['Order_ID','Delivery_ID','contactno','reasons','name','status'],'safe'],
        ];
    }

	public function search($params,$case)
	{
		switch ($case) {
			case 1:
				$query = ProblemOrder::find()->where('status=:s',[':s'=>'1']);
				$query->joinWith(['order_item']);
				
				break;

				case 2:
				$query = ProblemOrder::find()->where('status=:s',[':s'=>'0']);
				
				break;

			default:
				$query = ProblemOrder::find()->all();
				break;
		}

		$query->joinWith(['order']);
		$query->joinWith(['address']);

		$dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => ['defaultOrder' => ['Delivery_ID' => SORT_DESC]],
        ]);

       
	    $dataProvider->sort->attributes['name'] = [
	        'asc' => ['delivery_address.name' => SORT_ASC],
	        'desc' => ['delivery_address.name' => SORT_DESC],
	    ];
	    $dataProvider->sort->attributes['contactno'] = [
	        'asc' => ['delivery_address.contactno' => SORT_ASC],
	        'desc' => ['delivery_address.contactno' => SORT_DESC],
	    ];

	    $dataProvider->sort->attributes['reasons'] = [
	        'asc' => ['reason' => SORT_ASC],
	        'desc' => ['reason' => SORT_DESC],
	    ];

        $this->load($params);

        $query->andFilterWhere([
           'problem_order.Delivery_ID' => $this->Delivery_ID,
           'Orders_Status' => $this->status,
           'reason' => $this->reasons,
           	
        ]);
        $query->andFilterWhere(['like','problem_order.Order_ID',$this->Order_ID]);
        $query->andFilterWhere(['like','LOWER(name)', strtolower($this->name)]);
        $query->andFilterWhere(['like','contactno',$this->contactno]);

        return $dataProvider;
	}
}