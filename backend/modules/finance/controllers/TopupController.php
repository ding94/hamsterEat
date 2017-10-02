<?php

namespace app\modules\finance\controllers;
use frontend\models\Accounttopup;
use common\models\Accountbalance;
use common\models\User;
use common\models\AccounttopupOperate;
use common\models\AccounttopupStatus;
use backend\modules\finance\controllers\AccounttopupstatusController;
use backend\modules\finance\controllers\AccounttopupoperateController;
use yii\data\ActiveDataProvider;
use yii\helpers\ArrayHelper;

use Yii;

class TopupController extends \yii\web\Controller
{
    public function actionIndex()
    {
       $searchModel = new Accounttopup();
       $dataProvider = $searchModel->search(Yii::$app->request->queryParams,0);
		$list = ArrayHelper::map(AccounttopupStatus::find()->all() ,'title' ,'title');

        return $this->render('index',['model' => $dataProvider , 'searchModel' => $searchModel , 'list'=>$list]);
    }

    public function actionUpdate($id)
    {
		//var_dump($id);exit;
		$model = $this->findModel($id);
		if ($model->Account_Action == 1)
		{
			$balance = self::saveBalance($model);
			 
			self::updateAllTopup($id,3);
			$model->Account_Action = 3;
			$model->Account_InCharge = Yii::$app->user->identity->adminname;
			//var_dump($balance); exit; 
			if($model->update(false) !== false)
			{
				$balance->save();
			//	self::updateAllTopup();
				Yii::$app->session->setFlash('success', "Update success");
			}
			else{
				Yii::$app->session->setFlash('error', "Fail to Update");
			}
		}
		elseif ($model->Account_Action !=1){
			Yii::$app->session->setFlash('error', "Action Cancelled!");
		}
        return $this->redirect(['index']);
	}
	
	protected static function saveBalance($model)
	{
		$uid = User::find()->where('username = :name',[':name'=>$model->User_Username])->one()->username;
		
		$balance =Accountbalance::find()->where('User_Username = :name',[':name'=>$uid])->one();
		if(empty($balance)){
			$balance = new Accountbalance();
			$balance->User_Username = $uid;
		}
		//var_dump($uid);exit;
		$balance ->AB_topup += $model->Account_TopUpAmount;
		$balance ->User_Balance += $model->Account_TopUpAmount;

		return $balance;
	}

    public function actionUndos($id)
	{
		$model = Accounttopup::find()->where('Account_TransactionID = :id',[':id' => $id])->one(); 
		
		//var_dump($model->load(Yii::$app->request->post())); exit;
		if ($model->Account_Action == 3)
		{
			self::updateAllTopup($id,1);
			$balance = self::deductBalance($model);
			$balance->save();
			
			//var_dump($balance->validate(); exit;
			if($model->update() !== false)
			{
				//var_dump($model);exit;
				
				$model->Account_Action =$model->Account_ActionBefore;
				$model->save();
				Yii::$app->session->setFlash('success', "Undo success");
	    		 return $this->redirect(['index']);
			}
			else{
				Yii::$app->session->setFlash('warning', "Fail to undo");
			}
				
			return $this->redirect(['direct']);
		}
	}

	public function actionCancel($id)
	{
		// Cancel function incomplete
		$model = Accounttopup::find()->where('Account_TransactionID = :id',[':id' => $id])->one(); 
		//var_dump($model->load(Yii::$app->request->post())); exit;
		if ($model->Account_Action == 1 || $model->Account_Action == 2){
			
			if($model->load(Yii::$app->request->post()))
			{
				//var_dump($model->update()); exit;
			self::updateAllTopup($id,4);
			$model->Account_Action =4;
			$model->Account_InCharge = Yii::$app->user->identity->adminname;
			$model->save();
			
			Yii::$app->session->setFlash('success', "Cancel success");
    		 return $this->redirect(['index']);
			}
			    		
		return $this->render('update', ['model' => $model]);

		}
		elseif ($model->Account_Action ==3 || $model->Account_Action ==4){
		
		Yii::$app->session->setFlash('error', "Action cancelled!");
		}
		
		
		return $this->redirect(['direct']);
	}

	protected static function updateAllTopup($id,$status)
	{
		$data = self::updOfflineTopupStatus($id,$status);
		$operate = AccounttopupoperateController::createOperate($id,$status,1);

		if(is_null($data) || is_null($operate))
    	{
    		return false;
    	}
       
    	$isValid = $data->validate() && $operate->validate();
	
    	if($isValid)
    	{
    		$data->save();
    		$operate->save();
			return true;
			
    	}
    	else
    	{
    		return false;
    	}
    	return false;
			
	}

	protected static function updOfflineTopupStatus($id,$status)
    {
    	$data = Accounttopup::findOne($id);

    	if(is_null($data))
    	{
    		return $data;
    	}
        $statusDesription = AccounttopupstatusController::getStatusType($status,1);
        $data->Account_Action =  $statusDesription;
    	return $data;
    }

    protected static function deductBalance($model)
	{
		$uid = User::find()->where('username = :name',[':name'=>$model->User_Username])->one()->username;
		$balance =Accountbalance::find()->where('User_Username = :name',[':name'=>$uid])->one();
		$balance ->AB_topup -= $model->Account_TopUpAmount;
		$balance ->User_Balance -= $model->Account_TopUpAmount;
		
		return $balance;
	}

	protected function findModel($id)
    {
        if (($model = Accounttopup::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

    public function actionUndo($id)
	{
		$model = Accounttopup::find()->where('Account_TransactionID = :id',[':id' => $id])->one(); 
		//$model->rejectReason= "";
		//var_dump($model->load(Yii::$app->request->post())); exit;
		if ($model->Account_Action == 4)
		{
			
			if($model->update(false) !== false)
			{
				self::updateAllTopup($id,1);
				//var_dump($model);exit;
				$model->Account_Action =$model->Account_ActionBefore;
				$model->save();
				Yii::$app->session->setFlash('success', "Undo success");
	    		 return $this->redirect(['index']);
			}
			else{
				Yii::$app->session->setFlash('warning', "Fail to undo");
			}
				
			return $this->redirect(['direct']);
		}
	}

	public function actionEdit($id)
	{
		// Cancel function incomplete
		$model = Accounttopup::find()->where('Account_TransactionID = :id',[':id' => $id])->one(); 
		//var_dump($model->load(Yii::$app->request->post())); exit;
		if ($model->Account_Action == 1 ){
			
			if($model->load(Yii::$app->request->post()))
			{
			
			//var_dump($model->update()); exit;
			//$model->action =4;
			$model->Account_InCharge = Yii::$app->user->identity->adminname;
			$model->save();
			
			Yii::$app->session->setFlash('success', "Update success");
    		 return $this->redirect(['index']);
			}
			    		
		return $this->render('update', ['model' => $model]);

		}
		elseif ($model->action !=1){
		
		Yii::$app->session->setFlash('error', "Action failed!");
		}
		
		
		return $this->redirect(['direct']);
	}

	public function actionDirect()
    {
	  
	   $searchModel = new Accounttopup();
       $dataProvider = $searchModel->search(Yii::$app->request->queryParams,Yii::$app->request->post('Account_Action'));
		$list = ArrayHelper::map(AccounttopupStatus::find()->all() ,'title' ,'title');
		//var_dump($list);exit;
       return $this->render('index',['model' => $dataProvider , 'searchModel' => $searchModel,'list'=>$list]);
    }
}