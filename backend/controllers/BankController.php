<?php

namespace backend\controllers;
use common\models\Upload;
use common\models\Bank;
use Yii;
use yii\data\ActiveDataProvider;
use yii\web\UploadedFile;
use yii\helpers\Url;

class BankController extends CommonController
{
    public function actionIndex()
    {
        $searchModel = new Bank();
       	$dataProvider = $searchModel->search(Yii::$app->request->queryParams);
		return $this->render('index',['model' => $dataProvider]);
    }
		public function actionAddbank()
	{
		$model = new Bank();
		$upload = new Upload();
		//$path = Yii::$app->params['imageLocation'];
		$path = Yii::$app->request->baseUrl.'/imageLocation';
		if(Yii::$app->request->post())
		{
			$post = Yii::$app->request->post();
			$upload->imageFile =  UploadedFile::getInstance($upload, 'imageFile');
    		$upload->imageFile->name = time().'.'.$upload->imageFile->extension;
    		$model->Bank_PicPath = $path.'/'.$upload->imageFile->name;

    		if($upload->validate())
    		{
				$imageName = $upload->imageFile->name;
				$imagePath = Url::to('@frontend/web/imageLocation/bank/').$imageName;
    			$upload->imageFile->saveAs($imagePath);
    			$model->load($post);
				$model->Bank_PicPath = $imageName;
				//var_dump($model);exit;
    			$model->save();
    			Yii::$app->session->setFlash('success', 'Upload Successful');
    		} else{
    			Yii::$app->session->setFlash('danger', 'Upload Fail');
    			return $this->redirect(['index']);
    		}
		}$model->Bank_Name ="";
		$model->Bank_AccNo ="";
		$model->redirectUrl ="";
		return $this->render('addbank',['upload' => $upload, 'model' => $model]);
	}
	public function actionUpdate($id)
    {
		 $model = Bank::find()->where('Bank_ID = :Bank_ID',[':Bank_ID'=>$id])->one();
			if($model->load(Yii::$app->request->post()))
			{
				
				$model->save();
			//	self::updateAllTopup();
				Yii::$app->session->setFlash('success', "Update success");
				return $this->redirect(['index']);
			}
			else{
				//Yii::$app->session->setFlash('error', "Fail to Update");
				return $this->render('update', ['model' => $model]);
			}
			return $this->redirect(['index']); 
		}
		
	public function actionActive($id)
	{
		$model = Bank::find()->where('Bank_ID = :Bank_ID',[':Bank_ID'=>$id])->one();
		$model->status = 10;
		if($model->update(false) !== false)
		{
			Yii::$app->session->setFlash('success', "Activate Sucessfully");
		}
		else{
			Yii::$app->session->setFlash('warning', "Fail to Activate");
		}
        return $this->redirect(['index']);

	}
	public function actionDeactivate($id)
	{
		$model = Bank::find()->where('Bank_ID = :Bank_ID',[':Bank_ID'=>$id])->one();
		$model->status = 0;
		if($model->update(false) !== false)
		{
			Yii::$app->session->setFlash('warning', "Deactivate completed");
		}
		else{
			Yii::$app->session->setFlash('warning', "Fail to deactivate");
		}
       return $this->redirect(Yii::$app->request->referrer);
	}
	public function actionDelete($id)
	{
		$model = Bank::find()->where('Bank_ID = :Bank_ID',[':Bank_ID'=>$id])->one();
		if ($model) {
			$model->delete();
		}
		return $this->redirect(['index']);
	}
}
