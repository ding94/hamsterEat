<?php

namespace frontend\modules\Payment\controllers;

use Yii;
use yii\filters\AccessControl;
use yii\helpers\Json;
use frontend\controllers\CommonController;
use common\models\Payment;
use common\models\PaymentGateWay\{PaymentCollection,PaymentBill,PaymentGatewayHistory};
use common\models\Order\DeliveryAddress;

class OnlineBankingController extends CommonController
{

	public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'actions' => ['billing','notify'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                    [
                    	'actions' =>['detect-payment'],
                    	'allow'=>true,
                    	'roles' => ['?','@'],
                    ]
                ],  
            ],
        ];
    }

	public function actionBilling($did)
	{
		$link = $this->getBilling($did);
	
		if(empty($link))
		{
			Yii::$app->session->setFlash('warning',Yii::t('food','Something Went Wrong. Please Try Again Later!'));
			return $this->redirect(['/payment/default/process','did'=>$did]);
		}
		 return $this->redirect($link);
	}

	public function actionNotify()
	{
		$get = Yii::$app->request->get();
        $billData =  PaymentBill::getBill($get['billplz']['id']);
        if($billData['value'] != 1)
        {
            Yii::$app->session->setFlash('warning',Yii::t('food','Something Went Wrong. Please Try Again Later!'));
            return $this->redirect(['site/index']);
        }
        $billapi = $billData['data'];
        $bill = PaymentGatewayHistory::find()->where('collect_id = :cid and bill_id = :bid',[':cid'=>$billapi['collection_id'],':bid'=>$billapi['id']])->joinWith(['p'])->one();
        $did = $bill->p->item_id;
        
        if($billapi['paid'] && $billapi['state'] == 'paid')
        {
            $bill->status = 1;
            $bill->save();
            $order = DefaultController::findOrder($did);
            if(!empty($order))
            {
                $order = DefaultController::updateOrderStatus($order,2);
                DefaultController::saveStatus($order);
            }
          
            return $this->redirect(['/cart/aftercheckout','did'=>$did]);
        }

        Yii::$app->session->setFlash('warning',Yii::t('food','Something Went Wrong. Please Try Again Later!'));
        return $this->redirect(['process-payment','did'=>$did]);
	}

	public function actionDetectPayment()
    {
        $array = array(
            'value' => 0,
            'link' =>"",
        );
        if(!Yii::$app->user->isGuest)
        {
            $session = Yii::$app->session;
            $payment = $session->get('payment');
            $controller = Yii::$app->controller->id;
            $action = Yii::$app->controller->action->id;
            $permissionName = $controller.'/'.$action; 
            if($permissionName != "payment/process-payment")
            {
                if(!empty($payment))
                {
                    $data = self::detectPaymentAvaiable($payment['bill_id'],$payment['collect_id']);
                    
                    return Json::encode($data);
                }
            }
        }
        return Json::encode($array);
    }

	protected static function getBilling($did)
	{
		$payment = Payment::find()->where('item_id = :did',[':did'=>$did])->one();
		
		if(empty($payment))
		{
			$link = self::createBill($did);
		}
		else
		{
			$link = self::findBill($payment->id);
		}
		return $link;
	}

	protected static function createBill($did,$collect_id ="",$payment_id="")
	{
		$address = DeliveryAddress::findOne($did);
        $order = DefaultController::findOrder($did);

        if(empty($address) || empty($order))
        {
           return "";
        }

		if(empty($collect_id) && empty($payment_id))
		{
			$arrayData = self::createPid($did,$order);

			if($arrayData['value'] != 1)
			{
				return "";
			}
			$collect_id = $arrayData['collect_id'];
			$payment_id = $arrayData['payment_id'];
		}

		
		$bill = PaymentBill::generateBill($collect_id,Yii::$app->user->identity->email,$address->name,$address->contactno,$order->Orders_TotalPrice,$payment_id);
	
        if($bill['value'] == 1)
        {
            return $bill['link'];
        }
            
		return "";
	}

	protected static function findBill($pid)
	{
		$history = PaymentGatewayHistory::find()->where("pid = :pid",[':pid'=>$pid])->joinWith(['p'])->one();

		$billData =  PaymentBill::getBill($history->bill_id);
		$link = $billData['data']['url'];

        if($history->status == 2 || $billData['value'] != 1 || $billData['data']['state'] =="hidden")
       	{
       		$link = self::createBill($history->p->item_id,$history->collect_id,$history->p->id);
       	}
       	return $link;   
	}

	protected static function createPid($did,$order)
	{
		$array = array(
			'value' => -1,
			'collect_id' => 0,
			'payment_id'=> 0,
		);
		$collect = PaymentCollection::generateCollection($did);
		if($collect['value'] != 1)
		{
			return $array;
		}

		$collect_id = $collect['id'];
		$payment = DefaultController::generatePayment('2',$order->Orders_TotalPrice,$order->Delivery_ID);

		if(!$payment->save())
		{
			return $array;
		}
		$array['value'] =1;
		$array['collect_id'] = $collect['id'];
		$array['payment_id'] = $payment->id;
		return $array;
	}

	public static function close()
    {
        $session = Yii::$app->session;
        $session->remove('payment');
    }

    protected static function detectPaymentAvaiable($billid,$collectid)
    {
        $array = array(
            'value' => 0,
            'link' =>"",
        );

        $bill = PaymentGatewayHistory::find()->where('collect_id = :cid and bill_id = :bid and uid = :uid',[':cid'=>$collectid,':bid'=>$billid,':uid'=>Yii::$app->user->identity->id])->joinWith(['p'])->one(); 
        $billData =  PaymentBill::getBill($billid);

        if(empty($bill) || $billData['value'] != 1)
        {
            return $array;
        }
        
        $billApi = $billData['data'];
        if($bill->status == 0)
        {
            if($billApi['paid'] && $billApi['state'] == 'paid')
            {
                $bill->status = 1;
                $bill->save();
                $array['value']=1;
                $array['link']='Your Payment Has Successful Paid';
            }
            else
            {
                $array['value'] = 2;
                $array['link'] = $billApi['url'];
            }
        }
       return $array;
    }

}