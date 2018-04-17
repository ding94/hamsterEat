<?php

namespace backend\modules\Order\controllers;

use Yii;
use yii\web\Controller;
use yii\helpers\ArrayHelper;
use common\models\User;
use common\models\Order\StatusType;
use common\models\Order\Orders;
use common\models\Order\Orderitemstatuschange;
use common\models\Order\Ordersstatuschange;
use common\models\Order\DeliveryAddress;
use backend\controllers\CommonController;
use backend\models\OrderSearch;
use common\models\Restaurant;
use kartik\mpdf\Pdf;
use yii\web\NotFoundHttpException;
use common\models\Order\Orderitem;
use common\models\food\Food;
use backend\models\ItemSearch;

class AllOrderController extends CommonController
{
	public function actionIndex($did=0)
	{
		$searchModel = new OrderSearch();
		if($did !=0)
		{
			$searchModel->Delivery_ID = $did;
		}
		
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams,3);
        $alluser = ArrayHelper::map(User::find()->where('status = 10')->all(),'username','username');

        $allstatus =ArrayHelper::map(StatusType::find()->all(),'id','type');
        $arrayData['user'] = $alluser;
        $arrayData['status'] = $allstatus;

        return $this->render('index',['model' => $dataProvider , 'searchModel' => $searchModel ,'arrayData'=>$arrayData]);
	}

	//--This loads the order history as an invoice in pdf form
    public function actionInvoicePdf($did)
    {	

        $order = Orders::find()->where('Delivery_ID = :did', [':did'=>$did])->one();
        if(empty($order))
        {
            Yii::$app->session->setFlash('error', Yii::t('cart','Something Went Wrong!'));
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

	public function actionOrdertime($id)
	{
		$model = Ordersstatuschange::findOne($id);
		return $this->renderAjax('_ordertime',['model'=>$model]);
	}

	public function actionAddress($id)
	{
		$model = DeliveryAddress::findOne($id);
		return $this->renderAjax('_address',['model'=>$model]);
	}

	public function actionItem($id=0)
	{
		$searchModel = new ItemSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams,$id);
        $allstatus =ArrayHelper::map(StatusType::find()->all(),'id','type');
        return $this->render('item',['model' => $dataProvider , 'searchModel' => $searchModel,'allstatus'=>$allstatus,'id'=>$id]);
	}

	public function actionItemtime($id)
	{
		$model = Orderitemstatuschange::findOne($id);
		return $this->renderAjax('_itemtime',['model'=>$model]);
	}

	public function actionPrice($id)
	{
		$model = Orders::findOne($id);
		return $this->renderAjax('_price',['model'=>$model]);
	}
}