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

	public function rules()
    {
        return [
            //[['userfullname','usercontact','food','order_selection'],'safe'],
            [['Order_ID','Delivery_ID','reasons'],'safe'],
        ];
    }

	public function search($params,$case)
	{
		switch ($case) {
			case 1:
				$query = ProblemOrder::find()->where('status=:s',[':s'=>'1'])->andWhere('datetime > '.strtotime(date('Y-m-d')))->orderby('Order_ID ASC');
				$query->joinWith(['order_item']);
				$query->joinWith(['order']);
				break;

				case 2:
				$query = ProblemOrder::find()->where('status=:s',[':s'=>'0'])->orderby('Order_ID ASC');
				$query->joinWith(['order_item']);
				$query->joinWith(['order']);
				break;

			default:
				$query = ProblemOrder::find()->all();
				break;
		}
		
		$dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $dataProvider->sort->attributes['Order_ID'] = [
	        'asc' => ['Order_ID' => SORT_ASC],
	        'desc' => ['Order_ID' => SORT_DESC],
	    ];

	    $dataProvider->sort->attributes['Delivery_ID'] = [
	        'asc' => ['orderitem.Delivery_ID' => SORT_ASC],
	        'desc' => ['orderitem.Delivery_ID' => SORT_DESC],
	    ];

	    $dataProvider->sort->attributes['reasons'] = [
	        'asc' => ['reason' => SORT_ASC],
	        'desc' => ['reason' => SORT_DESC],
	    ];

        $this->load($params);

        $query->andFilterWhere(['like','Order_ID',$this->Order_ID]);
        $query->andFilterWhere(['like','problem_order.Delivery_ID',$this->Delivery_ID]);
        $query->andFilterWhere(['like','reason',$this->reasons]);

        return $dataProvider;
	}
}