<?php

namespace frontend\controllers;

use Yii;
use yii\web\Controller;
use yii\data\Pagination;
use yii\web\NotFoundHttpException;
use common\models\Order\Orders;
use common\models\Order\Orderitem;
use common\models\Order\StatusType;
use common\models\food\Food;
use common\models\Order\DeliveryAddress;
use common\models\Restaurant;
use kartik\mpdf\Pdf;
use yii\helpers\ArrayHelper;
use frontend\controllers\CommonController;
use yii\filters\AccessControl;

class OrderController extends CommonController
{
    public function behaviors()
    {
         return [
             'access' => [
                 'class' => AccessControl::className(),
                 'rules' => [
                    [
                        'actions' => ['my-orders','order-details','invoice-pdf','my-order-history'],
                        'allow' => true,
                        'roles' => ['@'],

                    ],     
                 ]
             ]
        ];
    }
//--This function loads all the user's orders
    public function actionMyOrders($status = "")
    {    
        $countOrder = $this->getTotalOrder();
        $query = Orders::find()->where('User_Username = :uname ', [':uname'=>Yii::$app->user->identity->username])->orderBy(['Delivery_ID'=>SORT_DESC]);
        $statusid = ArrayHelper::map(StatusType::find()->all(),'type','id');
        $label = ArrayHelper::map(StatusType::find()->all(),'id','label');

        if(!empty($status))
        {
            switch ($status) {
                case 6:
                    $query->andWhere(['or',['Orders_Status'=> 7],['Orders_Status'=> $status],])->orderBy(['Delivery_ID'=>SORT_DESC]);
                    break;

                case 8:
                    $query->andWhere(['or',['Orders_Status'=> 9],['Orders_Status'=> $status],])->orderBy(['Delivery_ID'=>SORT_DESC]);
                    break;
                
                default:
                    $query->andWhere('Orders_Status = :status',[':status' => $status])->orderBy(['Delivery_ID'=>SORT_DESC]);
                    break;
            }
            $status = StatusType::find()->where(['id'=>$status])->one();
            if(empty($status) || is_null($status))
            {
                Yii::$app->session->setFlash('error', 'Something Went Wrong!!.');
                return $this->redirect(['/order/my-orders']);
            }
            $status = $status->type;
        }

        $pagination = new Pagination(['totalCount'=>$query->count(),'pageSize'=>10]);
        $order = $query->offset($pagination->offset)
        ->limit($pagination->limit)
        ->all();
        $link = CommonController::createUrlLink(3);
        $this->layout = 'user';
        return $this->render('myorders', ['order'=>$order,'pagination' => $pagination,'countOrder'=>$countOrder,'link'=> $link ,'status' => empty($status) ? "All" : $status,'statusid'=>$statusid,'label'=>$label]);
    }

    /*
    * count all order status order
    * if empty let it empty
    * Completed and Rating Done Count as One
    */
    public static function getTotalOrder()
    {
        $countOrder['Not Paid']['total'] = 0;
        $countOrder['Pending']['total'] = 0;
        $countOrder['Canceled']['total'] = 0;   
        $countOrder['Preparing']['total'] = 0;   
        $countOrder['Pick Up in Process']['total'] = 0;   
        $countOrder['On The Way']['total'] = 0;   
        $countOrder['Completed']['total'] = 0;  
        $query = Orders::find()->where('User_Username = :uname', [':uname'=>Yii::$app->user->identity->username])->all();
        foreach($query as $data)
        {
            switch ($data['Orders_Status']) {

                case 6:
                    $countOrder['Completed']['total'] += 1;
                    break;

                case 7:
                    $countOrder['Completed']['total'] += 1;
                    break;

                case 8:
                    $countOrder['Canceled']['total'] += 1;
                    break;

                case 9:
                    $countOrder['Canceled']['total'] += 1;
                    break;
                
                default:
               
                    $status = StatusType::find()->where('id=:id',[':id'=>$data['Orders_Status']])->one()->type;
                    $countOrder[$status]['total'] += 1;
                    break;
            }
          
        }

        foreach($countOrder as $i=> $data)
        {
            $countOrder[$i]['total'] = $data['total'] == 0 ? "" : $data['total'];
        }
       
        return $countOrder;
    }

//--This function loads the specific user's order details
    public function actionOrderDetails($did)
    {
        $order = Orders::find()->where("orders.Delivery_ID = :id and User_Username = :u",[':id'=>$did,':u'=>Yii::$app->user->identity->username])->joinWith(['address'])->one();
        if(empty($order))
        {
            Yii::$app->session->setFlash('error', 'Something Went Wrong!!.');
            return $this->redirect(['/order/my-orders']);
        }
        $orderitems = Orderitem::find()->where('Delivery_ID = :did and      OrderItem_Status != 8 and OrderItem_Status != 9', [':did'=>$did])->all();
        $label = StatusType::find()->asArray()->all();
        $label = ArrayHelper::map($label,'id','label');
        date_default_timezone_set("Asia/Kuala_Lumpur");
       
        $order['Orders_Subtotal'] = number_format($order['Orders_Subtotal'],2);
        $order['Orders_DeliveryCharge'] = number_format($order['Orders_DeliveryCharge'],2);
        $order['Orders_TotalPrice'] = number_format($order['Orders_TotalPrice'],2);
        $order['Orders_DiscountTotalAmount'] = number_format($order['Orders_DiscountTotalAmount'],2);
        $order['Orders_DiscountEarlyAmount'] = number_format($order['Orders_DiscountEarlyAmount'],2);

        $this->layout = 'user';
        return $this->render('orderdetails', ['order'=>$order, 'orderitems'=>$orderitems, 'did'=>$did, 'label'=>$label]);
    }

//--This loads the order history as an invoice in pdf form
    public function actionInvoicePdf($did)
    {
        $order = Orders::find()->where('Delivery_ID = :did and User_Username = :name', [':did'=>$did,':name'=> Yii::$app->user->identity->username])->one();
        if(empty($order))
        {
            Yii::$app->session->setFlash('error', 'Something Went Wrong!!.');
            return $this->redirect(['/order/my-orders']);
        }
        $orderitem = Orderitem::find()->where('Delivery_ID = :did and OrderItem_Status != 8 and OrderItem_Status != 9', [':did'=>$did])->all();
        $address = DeliveryAddress::find()->where('delivery_id=:did',[':did'=>$did])->one();
        
        $pdf = new Pdf([
            'mode' => Pdf::MODE_UTF8,
            'content' => $this->renderPartial('orderhistorydetails',['order'=>$order, 'orderitem' => $orderitem ,'address'=>$address,'did'=>$did]),
            'options' => [
                'title' => 'Invoice',
                'subject' => 'Sample Subject',
            ],
            'methods' => [
                'SetHeader' => ['Generated By HAMSTEREAT'],
                'SetFooter' => ['|Page{PAGENO}|'],
            ]
            ]);
        return $pdf->render();
    }

    public function actionOrderlist($rid,$status = "",$mode = 1)
    {
        $restaurant = Restaurant::find()->where('Restaurant_ID = :rid', [':rid'=>$rid])->one();
        // food data with condition of today's orders and other table
        $allData = [];
        $data= Orderitem::find()->where('Restaurant_ID = :id',['id'=>$rid])->joinWith(['item_status'=>function($query){
            $query->where(['>=','Change_PendingDateTime',strtotime(date('Y-m-d'))]);},
            'food','order_selection'=>function($query){ $query->orderby('Selection_ID ASC');} ])->all();

        foreach($data as $item)
        {
            $allData[$item['food']['Food_ID']][] = $item;
        }
        
        return $this->render('orderlistpdf', ['rid'=>$rid,'allData'=>$allData,'restaurant'=>$restaurant]);
    }
   
    public static function findOrder($id)
    {
        if (($model = Orders::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

    public static function findOrderitem($id,$type)
    {
        $validate = true;
        $model = OrderItem::findOne($id);
       
        if ($model == null) {
            throw new NotFoundHttpException('The requested page does not exist.');
        }

        switch ($type) {
            case 3:
                if($model->OrderItem_Status != 2){
                    $validate = false;
                } 
                break;
            case 4:
                if($model->OrderItem_Status != 3){
                  
                    $validate = false;
                } 
                break;
            case 10:
                if($model->OrderItem_Status != 4){
                  
                    $validate = false;
                } 
                break;
            default:
                # code...
                break;
        }
        
        if(!$validate)
        {
            throw new NotFoundHttpException('The requested was Wrong.');
        }
     
        return $model;       
    }
}