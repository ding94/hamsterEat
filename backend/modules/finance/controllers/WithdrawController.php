<?php

namespace app\modules\finance\controllers;
use common\models\Withdraw;
use common\models\Account\Accountbalance;
use common\models\Bank;
use common\models\User;
use yii\data\ActiveDataProvider;
use backend\controllers\CommonController;
use common\models\Account\AccounttopupStatus;
use yii\helpers\ArrayHelper;
use Yii;


class WithdrawController extends CommonController
{
    public function actionIndex()
    {
        $searchModel = new Withdraw();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams,0);
		$list = ArrayHelper::map(AccounttopupStatus::find()->all() ,'title' ,'title');
		$name=ArrayHelper::map(Bank::find()->all() ,'Bank_ID' ,'Bank_Name');
        return $this->render('index',['model' => $dataProvider , 'searchModel' => $searchModel, 'list'=>$list,'name'=>$name ]);
    }

	public function actionApprove($id)
	{
		//$model = $this->findModel($id);
		$model = Withdraw::find()->where('id = :id',[':id' => $id])->one(); 
		
		if ($model->action == 1)
		{
			$balance = self::deductBalance($model);
			
			$model->action = 3;
			$model->inCharge = Yii::$app->user->identity->adminname;
			//$model->inCharge = Yii::$app->user->identity->id;
		//	var_dump($model->inCharge); exit; 
			$valid = $balance->validate() && $model->validate();
			
			if($valid)
			{
				$model->save();
				$balance->save();
				Yii::$app->session->setFlash('success', "Approve success");
			}
			else{
				Yii::$app->session->setFlash('error', "Fail to approve");
			}
		}
		elseif ($model->action !=1){
			Yii::$app->session->setFlash('error', "Action Cancelled!");
		}
        return $this->redirect(['index']);
	}
	
	protected static function deductBalance($model)
	{
		$uid = Withdraw::find()->where('uid = :name',[':name'=>$model->uid])->one()->uid;
		$username = User::find()->where('id = :id',[':id'=>$uid])->one()->username;
		
		$balance =Accountbalance::find()->where('User_Username = :User_Username',[':User_Username'=>$username])->one();
		$balance ->AB_minus += $model->withdraw_amount+2;
		// $balance ->User_Balance -= $model->withdraw_amount+2;
		
		return $balance;
	}
	public function actionCancel($id)
	{
		$model = Withdraw::find()->where('id = :id',[':id' => $id])->one(); 
		  //$model->scenario = 'negative';
		//var_dump($model->load(Yii::$app->request->post())); exit;
		if ($model->action == 1)
		{
						
			if($model->load(Yii::$app->request->post()))
			{
				$balance = self::addBalance($model);
				$model->action =4;
				$model->inCharge = Yii::$app->user->identity->adminname;
					//$model->inCharge = Yii::$app->user->identity->id;
					//var_dump($model->validate());var_dump($model);exit;
				$model->save();
				$balance->save();
				Yii::$app->session->setFlash('success', "Approve success");
				return $this->redirect(['index']);
			}
			return $this->render('update', ['model' => $model]);
		}
			else{
				Yii::$app->session->setFlash('error', "Fail to approve");
			}
			
				 return $this->redirect(['index']);
		}		
			
		
	protected static function addBalance($model)
	{
		$uid = Withdraw::find()->where('uid = :name',[':name'=>$model->uid])->one()->uid;
		$username = User::find()->where('id = :id',[':id'=>$uid])->one()->username;
		
		$balance =Accountbalance::find()->where('User_Username = :User_Username',[':User_Username'=>$username])->one();
		// $balance ->AB_minus -= $model->withdraw_amount;
		$balance ->User_Balance += $model->withdraw_amount+2;
		//var_dump($balance ->User_Balance);exit;
		$balance->type = 4;
		$balance->defaultAmount = $model->withdraw_amount;
		return $balance;
	}
			    		
		

}
		
	

