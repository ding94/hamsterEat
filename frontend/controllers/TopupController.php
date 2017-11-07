<?php

namespace frontend\controllers;
use frontend\models\Accounttopup;
use common\models\Bank;
use common\models\User;
use common\models\Upload;
use yii\helpers\ArrayHelper;
use Yii;
use yii\web\UploadedFile;
use frontend\controllers\CommonController;
use yii\filters\AccessControl;

class TopupController extends CommonController
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
    	$model = new Accounttopup;
    	$upload = new Upload;
       // $bank = ArrayHelper::map(Bank::find()->all(),'Bank_ID','Bank_Name');
	  // $bank = ArrayHelper::map(Bank::find()->all(),'Bank_ID','Bank_Name','Bank_AccNo','Bank_PicPath','redirectUrl');
        $bank = Bank::find()->where('status = :status',[':status' => 10])->all();
        $banklist = ArrayHelper::map($bank,'Bank_ID','Bank_Name');

		//var_dump($bank);exit;
        $upload->scenario = 'ticket';
    	$path = Yii::$app->params['imageLocation'];

		// $items = ArrayHelper::map(BankDetails::find()->all(), 'bank_name', 'bank_name');
    	if(Yii::$app->request->post())
    	{
    		$post = Yii::$app->request->post();
			$model->load($post);
            
    		$model->User_Username = User::find()->where('id = :id',[':id' => Yii::$app->user->identity->id])->one()->username;
            $model->Account_TransactionDate = time();
			$model->Account_Action = 1;
			$model->Account_ActionBefore=1;
    		$upload->imageFile =  UploadedFile::getInstance($upload, 'imageFile');
    		$upload->imageFile->name = time().'.'.$upload->imageFile->extension;
		
    		$model['Account_ReceiptPicPath'] = $path.'/'.$upload->imageFile->name;
			//$model['Account_ChosenBank'] = $post['Bank_ID'];
    		$upload->upload('imageLocation/');
			//var_dump($upload->imageFile);exit;
    	
			// var_dump($model->validate());exit;
			 if ($model->validate()){
        		$model->save();
    			Yii::$app->session->setFlash('success', 'Top Up Successful Please Wait for approve');
                return $this->redirect(['user/userbalance']);
			}
			else{
				Yii::$app->session->setFlash('error', 'Upload Failed');
			}
    	}
		$model->Account_TopUpAmount ="";
		//$model->description ="";
		$this->layout = 'user';
		    	return $this->render('index' ,['model' => $model ,'upload' => $upload ,'bank' => $bank ,'banklist' => $banklist]);
		//Yii::app()->end();
    }
	
}
