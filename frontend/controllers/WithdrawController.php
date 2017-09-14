<?php

namespace frontend\controllers;
use common\models\User;
use common\models\Withdraw;
use common\models\Accountbalance;
use Yii;
use yii\helpers\ArrayHelper;

class WithdrawController extends \yii\web\Controller
{
    public function actionIndex()
    {
        $model = new Withdraw;
    	//$upload = new Upload;
    	$balance = Accountbalance::find()->where('User_Username = :User_Username' ,[':User_Username' => Yii::$app->user->identity->username])->one();
		
		//$items = ArrayHelper::map(BankDetails::find()->all(), 'bank_name', 'bank_name');
		//var_dump(Yii::$app->user->identity->id);exit;
    	if(Yii::$app->request->post())
    	{
    		$post = Yii::$app->request->post();
    		//$model->username = User::find()->where('id = :id',[':id' => Yii::$app->user->identity->id])->one()->username;
    		$model->uid = Yii::$app->user->identity->id;
			$model->action = 1;
		
    		$model->load($post);
			//var_dump($model->withdraw_amount > $balance->balance);exit;
			if($model->withdraw_amount <= $balance->AB_topup -2)
			{	
				$balance ->AB_topup -= ($model->withdraw_amount+2);
				//var_dump($balance->save()); exit;
				self::actionValidation($model,$balance);
				Yii::$app->session->setFlash('success', 'Upload Successful');
				return $this->redirect(['withdraw/index']);
			}
			elseif($model->withdraw_amount > $balance->AB_topup -2)
			{
				Yii::$app->session->setFlash('error', 'Withdraw amount exceed balance!');
			}
    	}
		$model->acc_name ="";
		$model->withdraw_amount ="";
		$model->to_bank ="";
		
    	return $this->render('index' ,['model' => $model,'balance'=>$balance]);
    }
	
	public function actionValidation($model,$balance)
	{
		if ($model->validate() && $balance->validate())
		{
			$model->save();
			$balance->save();
		}
	}

}
