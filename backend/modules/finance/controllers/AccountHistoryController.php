<?php

namespace app\modules\finance\controllers;

use Yii;
use yii\web\Controller;
use common\models\Account\AccountbalanceHistory;

class AccountHistoryController extends Controller
{
	public static function createHistory($reason,$type,$amount,$abid)
	{
		$history = new AccountbalanceHistory;
		$history->amount = substr($amount, 1); 
		$history->type = $type;
		$history->description = $reason;
		$history->abid = $abid;
		$history->save();
	}
}