<?php

namespace backend\controllers;

use yii\web\Controller;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\helpers\ArrayHelper;
use Yii;
use backend\models\UserSearch;
use common\models\User;

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
		$model = self::findModel($id);
		$list = self::getRole($id);
		$model->scenario ="changeAdmin";
		if($model->load(Yii::$app->request->post()) && $model->save())
		{
			$post = Yii::$app->request->post('User');

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
		return $this->render('update',['model' => $model ,'list' => $list]);
	}

	protected static function permission($role,$id)
	{
		$auth = \Yii::$app->frontendAuthManager;
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

	protected function getRole($id)
	{
		$auth = \Yii::$app->frontendAuthManager;
		$data = array_merge($auth->getRolesByUser($id),$auth->getRoles());
		$list = ArrayHelper::map($data,'name','name');
		return $list;
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