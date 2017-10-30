<?php

namespace backend\controllers;

use common\models\Upload;
use common\models\Banner;
use Yii;
use yii\web\UploadedFile;
use yii\helpers\Url;
use yii\web\Controller;

Class BannerController extends Controller
{
	public function actionIndex()
	{
		$searchModel = new Banner();
       	$dataProvider = $searchModel->search(Yii::$app->request->queryParams);
		return $this->render('index',['model' => $dataProvider]);
	}

	public function actionAddbanner()
	{
		$model = new Banner();
		$upload = new Upload();
		$path = 'SysImg';
		if(Yii::$app->request->post())
		{
			$post = Yii::$app->request->post();
			$upload->imageFile =  UploadedFile::getInstance($upload, 'imageFile');
    		$upload->imageFile->name = time().'.'.$upload->imageFile->extension;

    		$model->name = $path.'/'.$upload->imageFile->name;
    		if($upload->validate())
    		{
    			$imageName = Url::to('@frontend/web').'/'.$path.'/'.$upload->imageFile->name;
    			$upload->imageFile->saveAs($imageName);
    			$model->load($post);
    			$model->save();
    			Yii::$app->session->setFlash('success', 'Upload Successful');
    		} else{
    			Yii::$app->session->setFlash('danger', 'Upload Fail');
    			return $this->redirect(['index']);
    		}
		}	
		return $this->render('addbanner',['upload' => $upload, 'model' => $model]);
	}

	public function actionDelete($id)
	{
		$model = Banner::find()->where('bannerid = :bannerid',[':bannerid'=>$id])->one();
		if ($model) {
			$model->delete();
		}
		return $this->redirect(['index']);
	}
}