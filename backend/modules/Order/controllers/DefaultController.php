<?php

namespace backend\modules\Order\controllers;

use Yii;
use yii\web\Controller;
use backend\models\OrderSearch;
use backend\models\ItemSearch;
use backend\controllers\CommonController;
use common\models\Account\Accountbalance;
use common\models\food\Food;
use common\models\Order\DeliveryAddress;
use common\models\Order\Orders;
use common\models\Order\Orderitem;
use common\models\Order\Orderitemselection;
use common\models\Order\Orderitemstatuschange;
use frontend\controllers\CartController;
/**
 * Default controller for the `Order` module
 */
class DefaultController extends CommonController
{

    /**
     * Renders the index view for the module
     * @return string
     */
    public function actionIndex()
    {
        $searchModel = new OrderSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams,1);

        return $this->render('index',['model' => $dataProvider , 'searchModel' => $searchModel]);
    }

    public function actionOrder()
    {
        $searchModel = new ItemSearch();
        $searchModel->OrderItem_Status = 2;
    	$dataProvider = $searchModel->search(Yii::$app->request->queryParams);

		return $this->render('orders',['model' => $dataProvider , 'searchModel' => $searchModel]);
    }

    public function actionEditorder($id)
    {
        $order = Orders::find()->where('Delivery_ID = :id',[':id'=>$id])->one();
        $delivery = DeliveryAddress::find()->where('delivery_id = :did',[':did'=>$id])->one();
        $order->scenario = 'edit';

        if (Yii::$app->request->post()) {
            $order->load(Yii::$app->request->post());
           
            if ($order->validate() && $order->Orders_Status == 2) {
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

    public function actionDeleteorder($oid)
    {
        // find orderitem
        $orderitem = Orderitem::find()->where('Order_ID=:oid',[':oid'=>$oid])->joinWith('order')->one();
        //check orderstatus b4 delete
        if ($orderitem['OrderItem_Status'] != 2 || empty($orderitem)) {
            Yii::$app->session->setFlash('error','Order was Prepared!');
            return $this->redirect(Yii::$app->request->referrer);
        }

        //refund function
        if ($orderitem['order']['Orders_PaymentMethod'] == 'Account Balance') {
            $valid = self::refund($orderitem);
            if ($valid == false) {
                Yii::$app->session->setFlash('error','Refund failed, therefore order delete fail.');
                return $this->redirect(Yii::$app->request->referrer);
            }
        }
        
        //check order item was it last item in delivery items
        $deliveryitem = Orderitem::find()->where('Delivery_ID=:did',[':did'=>$orderitem['Delivery_ID']])->count();
        if ($deliveryitem<=1) {
            $order = Orders::find()->where('Delivery_ID=:did',[':did'=>$orderitem['Delivery_ID']])->one();
            $order->delete();
        }
        //deletion
        $orderselection = Orderitemselection::find()->where('Order_ID=:oid',[':oid'=>$oid])->all();
        $orderstatus = Orderitemstatuschange::find()->where('Order_ID=:oid',[':oid'=>$oid])->one();
        if (!empty($orderselection)) {
            foreach ($orderselection as $k => $value) {
                $value->delete();
            }
        }
        $orderitem->delete();
        Yii::$app->session->setFlash('success','Deleted!');
        return $this->redirect(Yii::$app->request->referrer);
    }

    public function refund($orderitem)
    {
        $user = Accountbalance::find()->where('User_Username=:u',[':u'=>$orderitem['order']['User_Username']])->one();
        $ordertime = strtotime(Yii::$app->formatter->asDateTime($orderitem['order']['Orders_DateTimeMade']) . ' Asia/Kuala_Lumpur');
        $distime = strtotime($orderitem['order']['Orders_Date'].' 11:00:00 Asia/Kuala_Lumpur');
        if ($ordertime < $distime) {
            $orderitem['OrderItem_LineTotal'] = $orderitem['OrderItem_LineTotal'] - (CartController::actionRoundoff1decimal($orderitem['OrderItem_LineTotal']*0.15));
        }
        $user['User_Balance'] += $orderitem['OrderItem_LineTotal'];
        $user['AB_topup'] += $orderitem['OrderItem_LineTotal'];
        $user['AB_minus'] -= $orderitem['OrderItem_LineTotal'];

        $charge = self::refundcharge($orderitem['Order_ID']);
        if ($charge == false) {
            $user['User_Balance'] += 5;
        }
        if ($user->validate()) {
            $user->save();
            return true;
        }
        else
        {
            return false;
        }
    }

    public function refundcharge($oid)
    {
        $oitem = Orderitem::find()->where('Order_ID=:oid',[':oid'=>$oid])->joinWith('food')->one();
        //find all delivery items
        $ditems = Orderitem::find()->where('Delivery_ID=:d',[':d'=>$oitem['Delivery_ID']])->andWhere('Order_ID !='.$oid)->joinWith('food')->all();
        $check=false;
        $res = '';
        //set all restaurant ids
        foreach ($ditems as $k => $ditem) {
            $res[] = $ditem['food']['Restaurant_ID'];
        }
        if (!empty($res)) {
            //check duplicated restaurant. true = same restaurant exist
            foreach ($res as $e => $value) {
                if ($value == $oitem['food']['Restaurant_ID']) {
                    $check = true;
                }
            }
        }
        return $check;
    }

    public function actionCancelDelivery($did)
    {
        $order = Orders::find()->where('Delivery_ID=:d',[':d'=>$did])->one();
        if ($order['Orders_Status'] != 2 || empty($order)) {
            Yii::$app->session->setFlash('error','Delivery was Preparing!');
            return $this->redirect(Yii::$app->request->referrer);
        }

        $cancel = 8;
        if ($order['Orders_PaymentMethod'] == 'Account Balance') {
            $valid = self::fullrefund($did,$order);
            if ($valid == false) {
                Yii::$app->session->setFlash('error','Refund was Failed!');
                return $this->redirect(Yii::$app->request->referrer);
            }
            $cancel = 9;
        }

        $oitems = Orderitem::find()->where('Delivery_ID=:did',[':did'=>$did])->all();

        foreach ($oitems as $k => $oitem) {
            if ($oitem['OrderItem_Status'] =! 2) {
                Yii::$app->session->setFlash('error','Order ID '.$oitem['Order_ID'].' was Preparing!');
                return $this->redirect(Yii::$app->request->referrer);
            }
            else{
                $oitem['OrderItem_Status'] = $cancel;
            }
            if ($oitem->validate()) {
                $oitem->save();
            }
        }

        $order['Orders_Status'] = $cancel;
        $order->save(false);
        Yii::$app->session->setFlash('success','Delivery was Canceled!');
        return $this->redirect(Yii::$app->request->referrer);
    }

    public function fullrefund($did,$order)
    {
        $user = Accountbalance::find()->where('User_Username=:u',[':u'=>$order['User_Username']])->one();
        $user['User_Balance'] += $order['Orders_TotalPrice'];
        $user['AB_topup'] += $order['Orders_TotalPrice'];
        $user['AB_minus'] -= $order['Orders_TotalPrice'];
        if ($user->save()) {
            return true;
        }
        return false;
    }


}
