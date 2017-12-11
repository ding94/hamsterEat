<?php

namespace frontend\controllers;
use common\models\User;
use common\models\Withdraw;
use common\models\Account\Accountbalance;
use common\models\Bank;
use Yii;
use yii\helpers\ArrayHelper;
use frontend\controllers\CommonController;
use yii\filters\AccessControl;

class WithdrawController extends CommonController
{
	public function behaviors()
    {
         return [
             'access' => [
                 'class' => AccessControl::className(),
                 'rules' => [
                    [
                        'actions' => ['index',],
                        'allow' => true,
                        'roles' => ['@'],

                    ],
                    //['actions' => ['rating-data'],'allow' => true,'roles' => ['?'],],
                 ]
             ]
        ];
    }

    public function actionIndex()
    {
        $model = new Withdraw;
        $link = CommonController::createUrlLink(2);
    	//$upload = new Upload;
    	$balance = Accountbalance::find()->where('User_Username = :User_Username' ,[':User_Username' => Yii::$app->user->identity->username])->one();
		$bank = ArrayHelper::map(Bank::find()->all(),'Bank_ID','Bank_Name');
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
			if($model->withdraw_amount <= $balance->User_Balance -2)
			{	
				$balance ->User_Balance -= $model->withdraw_amount+2;
				$balance->type = 3;
				$balance->defaultAmount = ($model->withdraw_amount+2);
				//var_dump($balance ->User_Balance); exit;
				self::actionValidation($model,$balance);
				Yii::$app->session->setFlash('success', 'Upload Successful');
				return $this->redirect(['withdraw/index']);
			}
			elseif($model->withdraw_amount > $balance->User_Balance -2)
			{
				Yii::$app->session->setFlash('error', 'Withdraw amount exceed balance!');
			}
    	}
		$model->acc_name ="";
		$model->withdraw_amount ="";
		$model->to_bank ="";
		$this->layout = 'user';
    	return $this->render('index' ,['model' => $model,'balance'=>$balance,'bank' => $bank ,'link' => $link]);
    }
	
	public function actionValidation($model,$balance)
	{
		if ($model->validate() && $balance->validate())
		{
			//var_dump($model->validate());exit;
			$model->save();
			$balance->save();
		}
	}

}
