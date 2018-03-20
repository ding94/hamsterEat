<?php 

namespace console\models;

use yii\base\BaseObject;
use common\models\food\Foodstatus;

class FoodLimit extends BaseObject implements \yii\queue\JobInterface
{
    public function execute($queue)
    {
    	$status = Foodstatus::find()->where('Status != -1')->all();
    	foreach($status as $value)
    	{
    		if($value->food_limit != $value->default_limit)
    		{
    			$value->food_limit = $value->default_limit;
    			if($value->save())
	    		{
	    			echo $value->Food_ID . " Change Success" . PHP_EOL;
	    		}
	    		else
	    		{
	    			echo $value->Food_ID . " Change Fail" . PHP_EOL;
	    			break;
	    		}
    		}
    	}
    	
    }
}
