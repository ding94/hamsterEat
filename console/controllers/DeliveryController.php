<?php namespace console\controllers; 

use Yii; 
use yii\console\Controller;
use common\models\Deliveryman;

class DeliveryController extends Controller
{
	public function actionDailyreset()
	{
		$query = Deliveryman::find();
		foreach($query->each() as $value)
		{
			if($value->DeliveryMan_Assignment != 0)
			{
				$value->DeliveryMan_Assignment = 0;
				if($value->save())
				{
					echo "Delivery Man: ". $value->User_id . " Reset to 0" . PHP_EOL;
				}
				else
				{
					echo "Delivery Man : ".$value->User_id . " Fail Reset to 0" . PHP_EOL;
				}
			}
		}
	}
}