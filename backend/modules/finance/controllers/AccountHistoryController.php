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
		$amount = $type == 0 ? substr($amount, 1) : $amount;
		$history->amount = $amount; 
		$history->type = $type;
		$history->description = $reason;
		$history->abid = $abid;
		$history->save();
	}
}