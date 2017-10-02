<?php

namespace app\modules\finance\controllers;

use yii\web\Controller;
use common\models\Account\Accountbalance;
use common\models\User;

/**
 * Default controller for the `finance` module
 */
class DefaultController extends Controller
{
    /**
     * Renders the index view for the module
     * @return string
     */
    public function actionIndex()
    {
        return $this->render('index');
    }

    public static function getAccountBalance($id,$type,$balance)
    {
    	$username = User::find()->where("id = :id",[':id' => $id])->one()->username;
    	//var_dump($username);exit;
    	$data =Accountbalance::find()->where('User_Username = :name',[':name'=>$username])->one();
    	$data->User_Balance += $balance;

    	if($type == 0)
    	{
    		$data->AB_topup += $balance;
    	}
    	else
    	{
    		$data->AB_minus -= $balance;
    	}
    	return $data;
    }
}
