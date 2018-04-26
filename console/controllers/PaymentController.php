<?php namespace console\controllers; 

use Yii; 
use yii\console\Controller; 
use common\models\PaymentGateWay\{PaymentGatewayHistory,PaymentBill};

class PaymentController extends Controller
{
	public function actionDeleteStatus()
	{
		$model = PaymentGatewayHistory::find()->where("status = 0")->all();

		foreach ($model as $key => $value) 
		{
			
			if($value->updated_at < strtotime("-30 minutes")) 
			{
				$value->status = 2;
				if($value->save())
				{
					PaymentBill::delete($value->bill_id);
					echo "Bill ID: ". $value->bill_id . " has Delete" . PHP_EOL;
				}
			}

		}
	}
}