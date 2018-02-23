<?php 

namespace backend\controllers;

use yii\web\Controller;
use yii\filters\AccessControl;
use yii\helpers\ArrayHelper;
use yii\base\Model;
use backend\models\auth\AdminAuthItem;
use backend\models\auth\AdminAuthItemChild;
use backend\models\Controllerlist;
use Yii;

Class AuthController extends CommonController
{

	public function actionIndex()
	{
		$searchModel = new AdminAuthItem();

		$dataProvider = $searchModel->search(Yii::$app->request->queryParams , 1);

		return $this->render('index',['model' => $dataProvider , 'searchModel' => $searchModel]);
	}

	public function actionPermission()
	{
		$searchModel = new AdminAuthItem();
		$dataProvider = $searchModel->search(Yii::$app->request->queryParams ,2);
		
		$list =ArrayHelper::map(Controllerlist::find()->all(),'id','name');
		return $this->render('permission' , ['model' => $dataProvider , 'searchModel' => $searchModel,'list'=>$list]);
	}

	public function actionView($id)
	{
		$model = new AdminAuthItemChild();

		$auth = \Yii::$app->authManager;

		$verify = $auth->getPermission($id);

		if($verify)
		{
			Yii::$app->session->setFlash('warning', 'Not In Role List');
			return $this->redirect(Yii::$app->request->referrer);
		}

		$available = $auth->getChildren($id);
		
		$notAvailable =  self::notAvailableSotring($available);
		
		$listAvailabe =  self::groupBycontrol($available);

		$listOfControl = ArrayHelper::map(ControllerList::find()->all(),'id','name');
		
		return $this->render('view' ,['model' => $model ,'listAvailabe' => $listAvailabe , 'listAll' => $notAvailable , 'controlList'=>$listOfControl,'id' => $id]);
	}

	public function actionUpdate($id)
	{
		$model = AdminAuthItem::findOne($id);

		$model->data = unserialize($model->data);
		if(Yii::$app->request->isPost)
		{
			$post = Yii::$app->request->post();
			
			$auth = \Yii::$app->authManager;
			$permission = $auth->getPermission($id);
			$permission->name = $post['AdminAuthItem']['name'];
			$permission->description = $post['AdminAuthItem']['description'];
			$permission->data = $post['AdminAuthItem']['data'];
			
			if($auth->update($id,$permission))
			{
				Yii::$app->session->setFlash('success', "Change Completed");
				return $this->redirect(['permission']);
			}
			else
			{
				Yii::$app->session->setFlash('warning', "Fail Change");
			}
		}
		$list =ArrayHelper::map(Controllerlist::find()->all(),'id','name');
		return $this->render('update',['model'=>$model,'list'=>$list]);
		
	}

	public function actionDelete($id)
	{
		$auth = \Yii::$app->authManager;

		$data = $auth->getPermission($id);

		if(empty($data))
		{
			$data = $auth->getRole($id);
		}

		if($auth->remove($data))
		{
			Yii::$app->session->setFlash('success', "Delete Completed");
		}
		else
		{
			Yii::$app->session->setFlash('warning', "Fail Delete");
		}
		
		return $this->redirect(['index']);
	}

	public function actionRemoveRole($id)
	{
		$data= Yii::$app->request->post('AdminAuthItemChild');
        
		$data = self::detectEmptyNull($data);

		if($data == 1)
		{
			return $this->redirect(['view' ,'id' => $id]);
		}
		
		$result = $this->modifyRole($id,$data,2);
		if($result == true)
		{
			Yii::$app->session->setFlash('success', "Delete Completed");
		}
		else
		{
			Yii::$app->session->setFlash('warning', "Fail Delete");
		}
		
	    return $this->redirect(['view' ,'id' => $id]);
	}

	public function actionAddRole($id)
	{
		$data= Yii::$app->request->post('AdminAuthItemChild');
       
		$data = self::detectEmptyNull($data);

		if($data == 1)
		{
			return $this->redirect(['view' ,'id' => $id]);
		}
		
		$result = $this->modifyRole($id,$data,1);
		
		if($result == true)
		{
			Yii::$app->session->setFlash('success', "Add Completed");
		}
		else
		{
			Yii::$app->session->setFlash('warning', "Fail Added");
		}
		
	    return $this->redirect(['view' ,'id' => $id]);
	}

	protected static function detectEmptyNull($item)
	{
		if(empty($item))
		{
		  
			Yii::$app->session->setFlash('warning', "Please Select One");
			return 1;
		}
		$data = array_filter($item['child']);	

		if(empty($data))
		{
		   
			Yii::$app->session->setFlash('warning', "Please Select One");
			return 1;
		}

		$allchild ="";

		foreach($data as $k=>$row)
		{
			foreach($row as $value)
			{
				$allchild[] = $value;
			}
		}


		return $allchild;
	}

	protected function modifyRole($id,$childData,$type)
	{
		$auth = \Yii::$app->authManager;
		$parent = $auth->getRole($id);
		
		$data ="";
		foreach ($childData as $key => $value) 
		{
			$child = $auth->getPermission($value);

			switch ($type) {
				case 1:
					if($auth->canAddChild($parent,$child))
					{
						$auth->addChild($parent,$child);
						$data = true;
					}
					else
					{
						$data = false;
					}
					break;
				case 2:
					$auth->removeChild($parent,$child);
					$data = true;
					break;
				default:
					$data = false;
					break;
			}
		}
		return $data;
	}

	public function actionAdd()
	{
		$list = [[ 'type' => 1 , 'name' => 'Role Name' ],[ 'type' => 2 , 'name' => 'Permission link']];
		$listOfType = ArrayHelper::map($list, 'type', 'name');

		$listOfControl = ArrayHelper::map(ControllerList::find()->all(),'id','name');

		$model = new AdminAuthItem();
		if($model->load(Yii::$app->request->post()))
		{
			$isValid = $model->validate();
			if($isValid)
			{
				$data = Yii::$app->request->post('AdminAuthItem');
			
				$message = self::roleOrPermission($data);
				if($message == true)
				{
					Yii::$app->session->setFlash('success', "Add completed");
	    			return $this->redirect(['index']);
				}
				else
				{
					Yii::$app->session->setFlash('warning', "Fail added");
	    			return $this->redirect(['index']);
				}
			}
		}
		return $this->render('add' ,['model' => $model ,'listOfType' => $listOfType ,'listOfControl' => $listOfControl]);
	}

	/*
	 * remove avaiable child in auth item
	 */
	public static function notAvailableSotring($available)
	{
		$auth = \Yii::$app->authManager;
		$notAvailable = $auth->getPermissions();
		
		$notAvailable = array_diff_key($notAvailable,$available);
		
		$data = self::groupBycontrol($notAvailable);
		
		return $data;
	}

	/*
	 * sort by controlList id
	*/
	public static function groupBycontrol($sorting)
	{
		$afterSort = array();
		$listOfControl = ArrayHelper::map(ControllerList::find()->all(),'id','id');
		foreach($sorting as $k=>$data)
		{
			if($data->data == $listOfControl[$data->data])
			{
				$afterSort[$data->data][$data->name] = $data->name;
			}
		}

		return $afterSort;
	}

	/*
	 * based on which permission 
	 */
	public static function roleOrPermission($data)
	{
		$auth = \Yii::$app->authManager;
	
		if((int)$data['type'] === 2)
		{
			$create = $auth->createPermission($data['name']);
			$create->description   = $data['description'];
			$create->data = $data['data'];
		}
		elseif((int)$data['type'] === 1)
		{
			$create = $auth->createRole($data['name']);
			$create->description = $data['description'];
		}
		else
		{
			return false;
		}

		if($auth->add($create))
		{
			return true;
		}
		
		return false;
	}
}