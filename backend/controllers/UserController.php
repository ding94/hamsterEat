<?php

namespace backend\controllers;

use yii\web\Controller;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\helpers\ArrayHelper;
use Yii;
use backend\models\UserSearch;
use common\models\User;
use common\models\Deliveryman;
use common\models\Rmanager;

Class UserController extends Controller
{
	public function actionIndex()
	{
		$searchModel = new UserSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
 
        return $this->render('index',['model' => $dataProvider , 'searchModel' => $searchModel]);

	}

	public function actionDetail($id)
	{
		$model = User::find()->where('id = :id',[':id'=>$id])->joinWith(['balance','userdetails'])->one();
		return $this->renderAjax('detail',['model'=>$model]);
	}

	public function actionActive($id)
	{
		$model = self::findModel($id);
		$model->status = 10;
		if($model->update(false) !== false)
		{
			Yii::$app->session->setFlash('success', "Active completed");
		}
		else{
			Yii::$app->session->setFlash('warning', "Fail to Active");
		}
        return $this->redirect(Yii::$app->request->referrer);
	}

	public function actionDelete($id)
	{
		$model = self::findModel($id);
		$model->status = 0;
		if($model->update(false) !== false)
		{
			Yii::$app->session->setFlash('success', "Active completed");
		}
		else{
			Yii::$app->session->setFlash('warning', "Fail to Active");
		}
        return $this->redirect(Yii::$app->request->referrer);
	}

	public function actionUpdate($id)
	{
		$model = User::find()->where('id = :id',[':id'=>$id])->one();
		$deliveryMan = DeliveryMan::findOne($id);
		$deliveryMan = empty($deliveryMan) ? new Deliveryman : $deliveryMan;
		$manager = Rmanager::findOne($id);
		$manager = empty($manager) ? new Rmanager : $manager;

		$list = self::getRole($id);
		$model->scenario ="changeAdmin";
		if(Yii::$app->request->post())
		{
			$post = Yii::$app->request->post();
			$model->load(Yii::$app->request->post());
			$post = Yii::$app->request->post();
			$validate = true;
		
			$dmOrRm = self::deliveryOrRestaurant($deliveryMan,$manager);
			
			if($dmOrRm['value'] == 0)
			{
				return $this->redirect(Yii::$app->request->referrer);
			}

			$data = $dmOrRm['data'];

			if($post['type'] == 1)
			{	
				$validate = self::permission($post['User']['role'],$id);
			}
			
			$validate  = $validate && $model->validate();

			if($validate == true)
	        {
	        	$model->save();
	        	$data->save();
	        	
	        	Yii::$app->session->setFlash('success', "Update completed");
	        	return $this->redirect(['index']);
	        }
		}
		return $this->render('update',['model' => $model,'deliveryMan' => $deliveryMan ,'manager'=>$manager,'list' => $list]);
	}

	protected static function permission($role,$id)
	{
		$auth = \Yii::$app->frontendAuthManager;
		$item = $auth->getRole($role);

		$userRole=  $auth->getRolesByUser($id);

		$authorRole = $auth->getRole($role);
		
		if(empty($userRole))
		{
			if($auth->assign($authorRole, $id))
			{
				return true;
			}
		}
		else
		{
			Yii::$app->session->setFlash('warning', "Cannot Change Role Base!");
			return false;
		}
		return false;
	}

	protected function getRole($id)
	{
		$auth = \Yii::$app->frontendAuthManager;
		$list['value'] = 1;
		if(empty($auth->getRolesByUser($id)))
		{
			$list['value'] = 0;
		}
		
		$data = array_merge($auth->getRolesByUser($id),$auth->getRoles());

		unset($data['Manager']);
		unset($data['Owner']);
		unset($data['Operator']);
		$list['data'] = ArrayHelper::map($data,'name','name');

		if(!empty($auth->getRolesByUser($id)))
		{
			$list['value'] = key($list['data']);
		}
		
		return $list;
	}

	protected static function deliveryOrRestaurant($deliveryman,$manager)
	{
		$data['value'] = 0;
		$data['data'] ="";
		$post = Yii::$app->request->post();
		$role = $post['User']['role'];
		
		switch ($role) {
			case 'restaurant manager':
				$manager->load(Yii::$app->request->post());
				
				if($manager->isNewRecord)
				{
					$manager->Rmanager_DateTimeApplied = time();
					$manager->Rmanager_DateTimeApproved = time();
					
				}
				$value = $manager;
				$data['value'] = 2;
				# code...
				break;
			case 'rider':
				$deliveryman->load(Yii::$app->request->post());
				if($deliveryman->isNewRecord)
				{
					$deliveryman->DeliveryMan_DateTimeApplied = time();
					$deliveryman->DeliveryMan_DateTimeApproved = time();
					$deliveryman->DeliveryMan_AreaGroup = 1;
				}
				$value = $deliveryman;
				$data['value'] = 3	;
				break;
			default:
				# code...
				break;
		}
		
		if(!$value->validate())
		{
			$data['value'] = 0;
			$message ="";
			foreach($value->getErrors() as $erros)
			{
				foreach($erros as $error)
				{
					$message .= $error."<br>";
				}
			}
			Yii::$app->session->setFlash('warning', $message);
		}
		else
		{
			$data['data'] = $value;
		}
		return $data;
		
	}

	protected function findModel($id)
    {
        if (($model = User::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}