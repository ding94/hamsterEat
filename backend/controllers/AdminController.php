<?php

namespace backend\controllers;

use yii\web\Controller;
use backend\models\Admin;
use backend\models\AdminControl;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\helpers\ArrayHelper;
use yii\web\NotFoundHttpException;
use Yii;

Class AdminController extends CommonController
{
	public function actionIndex()
	{
		$searchModel = new AdminControl();
		$searchModel->scenario ='searchAdmin';
    	$dataProvider = $searchModel->search(Yii::$app->request->queryParams);

		return $this->render('index',['model' => $dataProvider , 'searchModel' => $searchModel]);
	}

	public function actionAdd()
	{
		$model = new AdminControl();
		$model->scenario = 'addAdmin';
		$model->adminTittle = "Add Admin";
		$model->passwordOff = '1';

		$list = self::getRole(1);

		if($model->load(Yii::$app->request->post())&& $model->add())
		{
			Yii::$app->session->setFlash('success', "Add completed");
    		return $this->redirect(['index']);
		}
		return $this->render('addEdit',['model' => $model,'list'=>$list]);
	}

	public function actionUpdate($id)
	{
		$model = self::findModel($id);
		$model->scenario = 'changeAdmin';
		$model->adminTittle = "Update Admin";
		$model->passwordOff = '0';

		$listData = self::getRole(1);
		$role = self::getRole(2,$id);
		
		if($model->load(Yii::$app->request->post()) && $model->save())
		{
			$post = Yii::$app->request->post('Admin');
			
	        $validate = self::permission($post['role'],$id);

	        if($validate == true)
	        {
	        	Yii::$app->session->setFlash('success', "Update completed");
	        }
	        else
	        {
	        	Yii::$app->session->setFlash('warning', "Fail Update");
	        }
		
    		return $this->redirect(['index']);
		}

		$list = array_merge($role,$listData);
		return $this->render('addEdit', ['model' => $model ,'list' => $list]);
	}

	public function actionDelete($id)
	{
		$model =self::findModel($id);
		$model->status = 0;
		if($model->update(false) !== false)
		{
			Yii::$app->session->setFlash('warning', "Delete completed");
		}
		else{
			Yii::$app->session->setFlash('warning', "Fail to delete");
		}
       return $this->redirect(Yii::$app->request->referrer);
	}

	public function actionActive($id)
	{
		$model = $this->findModel($id);
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

	public static function permission($role,$id)
	{
		$auth = \Yii::$app->authManager;
		$item = $auth->getRole($role);
		$item = $item ? : $auth->getPermission($role);
		$auth->revoke($item,$id);
		
		$authorRole = $auth->getRole($role);
        if($auth->assign($authorRole, $id))
        {
        	return true;
        }
        return false;

	}

	/*
	* get auth role
	* 1=> get all Role
	* 2=> get current id Role
	*/
	protected static function getRole($type,$id=0)
	{
		$auth = \Yii::$app->authManager;
		$data = "";
		switch ($type) {
			case 1:
				$data = $auth->getRoles();
				break;
			case 2:
				$data = $auth->getRolesByUser($id);
				break;
			default:
				break;
		}
	
		$list = ArrayHelper::map($data,'name','name');
        return $list;
	}

	/**
     * Finds the Admin model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param string $id
     * @return Admin the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Admin::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}