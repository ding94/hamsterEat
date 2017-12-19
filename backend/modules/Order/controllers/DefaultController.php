<?php

namespace backend\modules\Order\controllers;

use Yii;
use yii\web\Controller;
use common\models\Order\Orders;
use backend\models\OrderSearch;
use common\models\Order\Orderitem;
use common\models\food\Food;
use common\models\Order\DeliveryAddress;
/**
 * Default controller for the `Order` module
 */
class DefaultController extends Controller
{

    /**
     * Renders the index view for the module
     * @return string
     */
    public function actionIndex()
    {
        $searchModel = new OrderSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams,1);
        $days = $this->getMonth(12,2017);
        
        return $this->render('index',['model' => $dataProvider , 'searchModel' => $searchModel,'days'=>$days]);
    }

    public function actionDelivery()
    {
        $searchModel = new OrderSearch();
    	$dataProvider = $searchModel->search(Yii::$app->request->queryParams,1);
    	
		return $this->render('delivery',['model' => $dataProvider , 'searchModel' => $searchModel]);
    }

    public function actionEditorder($id)
    {
        $order = Orders::find()->where('Delivery_ID = :id',[':id'=>$id])->one();
        $delivery = DeliveryAddress::find()->where('delivery_id = :did',[':did'=>$id])->one();
        $order->scenario = 'edit';

        if (Yii::$app->request->post()) {
            $order->load(Yii::$app->request->post());
           
            if ($order->validate() && $order->Orders_Status == "Pending") {
                $order->save();
                Yii::$app->session->setFlash('success','Edited!');
            }
            else
            {
                Yii::$app->session->setFlash('error','Failed!');
            }
        }
        //var_dump($order);exit;
        return $this->render('editorder',['order'=>$order,'delivery'=>$delivery]);
    }

    public function actionShowdetails($id)
    {
        $orderitem = Orderitem::find()->where('Delivery_ID =:id',[':id'=>$id])->all();

        if (!empty($orderitem)) {
            
        }
        return $this->render('showdetails',['orderitem'=>$orderitem]);
    }

    protected static function getMonth($month,$year)
    {
        $start_date = "01-".$month."-".$year;
        $start_time = strtotime($start_date);

        $end_time = strtotime("+1 month", $start_time);

        for($i=$start_time; $i<$end_time; $i+=86400)
        {
           $list['title'][] = date('Y-m-d', $i);
           $list['data'][] = 0;
           
        }
        return $list;
    }
}
