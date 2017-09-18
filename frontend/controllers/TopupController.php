<?php

namespace frontend\controllers;
use frontend\models\Accounttopup;
use common\models\User;
use common\models\Upload;
use yii\helpers\ArrayHelper;
use Yii;
use yii\web\UploadedFile;

class TopupController extends \yii\web\Controller
{
    public function actionIndex()
    {
    	$model = new Accounttopup;
    	$upload = new Upload;
        $upload->scenario = 'ticket';
    	$path = Yii::$app->params['imageLocation'];
		// $items = ArrayHelper::map(BankDetails::find()->all(), 'bank_name', 'bank_name');
    	if(Yii::$app->request->post())
    	{
    		$post = Yii::$app->request->post();
    		$model->User_Username = User::find()->where('id = :id',[':id' => Yii::$app->user->identity->id])->one()->username;

			$model->Account_Action = 1;
			$model->Account_ActionBefore=1;
    		$upload->imageFile =  UploadedFile::getInstance($upload, 'imageFile');
    		$upload->imageFile->name = time().'.'.$upload->imageFile->extension;
			
    		$post['Accounttopup']['Account_ReceiptPicPath'] = $path.'/'.$upload->imageFile->name;
    		$upload->upload('imageLocation/');
			//var_dump($upload->imageFile);exit;
    		$model->load($post);
    		$model->save(false);
			Yii::$app->session->setFlash('success', 'Upload Successful');
    	}
		//$model->amount ="";
		//$model->description ="";
		//$this->layout = 'user';
		    	return $this->render('index' ,['model' => $model ,'upload' => $upload]);
		//Yii::app()->end();
    }
	
}
