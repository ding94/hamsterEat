<?php
namespace frontend\controllers;
use Yii;
use yii\web\Controller;
use yii\helpers\ArrayHelper;
use yii\data\Pagination;
use common\models\user\Userdetails;
use common\models\user\Useraddress;
use common\models\User;
use common\models\user\Changepassword;
use common\models\Upload;
use yii\web\UploadedFile;
use common\models\Account\Accountbalance;
use common\models\Account\AccountbalanceHistory;
use frontend\models\Accounttopup;
use common\models\Withdraw;
use common\models\Account\Memberpoint;
use frontend\controllers\CommonController;
use yii\filters\AccessControl;

class UserController extends CommonController
{
    public function behaviors()
    {
         return [
             'access' => [
                 'class' => AccessControl::className(),
                 'rules' => [
                    [
                        'actions' => ['user-profile','userdetails','useraddress','userbalance','changepassword','primary-address','newaddress','delete-address','edit-address'],
                        'allow' => true,
                        'roles' => ['@'],

                    ],
                    //['actions' => ['rating-data'],'allow' => true,'roles' => ['?'],],
                 ]
             ]
        ];
    }

    public function actionUserProfile()
    {
        $user = User::find()->where('user.id = :id' ,[':id' => Yii::$app->user->id])->joinWith(['address' => function($query){
            $query->orderBy(['level' =>SORT_DESC ]);
        },'userdetails','balance'])->one();

        $this->layout = 'user';
      
        return $this->render('userprofile',['user' => $user]);
       
    }

    public function actionUserdetails()
    {
     
        $upload = new Upload();
        $upload->scenario = 'ticket';
        $path = Yii::$app->request->baseUrl.'/imageLocation';
        
       // return $this->render('upload', ['detail' => $detail]);
        $link = CommonController:: createUrlLink(1);
        $detail = Userdetails::find()->where('User_id = :id'  , [':id' => Yii::$app->user->identity->id])->one();
        
             $picpath = $detail['User_PicPath']; 
            if($detail->load(Yii::$app->request->post()))
            {
                    $post = Yii::$app->request->post();
                    $model = UserDetails::find()->where('User_Username = :uname',[':uname' => Yii::$app->user->identity->username])->one(); 
                    
			        //$model->action = 1;
			        //$model->action_before=1;
    		        $upload->imageFile =  UploadedFile::getInstance($detail, 'User_PicPath');
                          if (!is_null($upload->imageFile))
                {
    		        $upload->imageFile->name = time().'.'.$upload->imageFile->extension;
    		       // $post['User_PicPath'] = 
                    $location = 'imageLocation/';
    		        $upload->upload($location);
			        
                    $model->User_PicPath =$path.'/'.$upload->imageFile->name;
                   
    		        //$model->save();
			      
                }
                else{
                    $detail->User_PicPath = $picpath;
                   
                     
                }
               
				     $isValid = $detail->validate()  && $model->validate();
                    if($isValid){
                        $detail->save();
                      
                        $model->save();
        
                    Yii::$app->session->setFlash('success', "Update completed");
                    return $this->redirect(['user/user-profile']);
                
                    }
                    else{
                        Yii::$app->session->setFlash('warning', "Fail Update");
                    }

			}
	
		//$this->view->title = 'Update Profile';
		$this->layout = 'user';
		return $this->render("userdetails",['detail' => $detail,'link' => $link]);
    }

	public function actionUserbalance()
 	{
        $searchModel = new AccountbalanceHistory();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams,5);
		$account = Accountbalance::find()->where('User_Username = :name',[':name' => Yii::$app->user->identity->username])->one();
//var_dump( $model);exit;       
	   $query = AccountbalanceHistory::find()->where('abid = :aid',[':aid' => $account->AB_ID]);
  
	   $count = $query->count();
        $historypagination = new Pagination(['totalCount' => $count,'pageSize'=>10]);
        $historypage = $query->offset($historypagination->offset)->limit($historypagination->limit)->orderBy(['created_at'=> SORT_DESC])->all();
        $link = CommonController::createUrlLink(2);
 		$this->layout = 'user';
        $this->view->title = 'User Balance History';
		return $this->render('userbalance', ['model'=>$dataProvider,'historypage' => $historypage ,'historypagination' => $historypagination, 'searchModel' => $searchModel,'link'=>$link]);
 	}
    
    public function actionChangepassword()
 	{      
	    $model = new Changepassword;
	    $link = CommonController::createUrlLink(1);

	    if($model->load(Yii::$app->request->post()) ){
	 		if ($model->check()) {
	 			 Yii::$app->session->setFlash('success', 'Successfully changed password');
	 			    return $this->redirect(['user/changepassword']);
	 		}
	        
	        else {
	         	Yii::$app->session->setFlash('warning', 'changed password failed');
	        }
	      
	    }
	    $this->view->title = 'Change Password';
	 	$this->layout = 'user';
	    return $this->render('changepassword',['model'=>$model,'link' => $link]); 
 	}

    /*
    * update alll adress level to 0
    * and set the id address to 1
    */
    public function actionPrimaryAddress($id)
    {
        Useraddress::updateAll(['level' => 0],'uid = :uid',[':uid' => Yii::$app->user->identity->id]);
        $model = Useraddress::findOne($id);
        $model->level = 1;
        if($model->save())
        {
            Yii::$app->session->setFlash('success', 'Address changed to Primary');
        }
        else
        {
            Yii::$app->session->setFlash('success', 'Address changed Fail');
        }
        return $this->redirect(Yii::$app->request->referrer);
    }

    /*
    * create new address
    * total data from database more than 3 fail
    * if click mark as default update all other to level 0 
    * and update itselft to level 1
    */
    public function actionNewaddress()
    {
      
        $count = Useraddress::find()->where('uid = :uid',[':uid' => Yii::$app->user->identity->id])->count();
        if($count >= 3)
        {
             Yii::$app->session->setFlash('danger', ' Reach Max Limit 3');
              return $this->redirect(Yii::$app->request->referrer);
        }

        $model = new Useraddress;
        $user = Userdetails::find()->where('User_id=:id',[':id'=>Yii::$app->user->identity->id])->one();
        $model['recipient'] = $user['User_FirstName'].' '.$user['User_LastName'];
        $model['contactno'] = $user['User_ContactNo'];
        if($model->load(Yii::$app->request->post()))
        {
            
            $model->uid = Yii::$app->user->identity->id;
                
            if($model->save())
            {
                if($model->level == 1)
                {
                    Useraddress::updateAll(['level' => 0],'uid = :uid AND id != :id',[':uid' => Yii::$app->user->identity->id,':id'=> $model->id]);
                }
                     Yii::$app->session->setFlash('success', 'Successfully create new address');
            }
            else
            {
                ii::$app->session->setFlash('danger', ' Address Add Fail');
            }
            return $this->redirect(Yii::$app->request->referrer);
        }
        $this->layout = 'user';
        $this->view->title = 'Add New Address';
        return $this->renderAjax('address',['model'=>$model]);
    }

    public function actionEditAddress($id)
    {
        $model = Useraddress::findOne($id);
        if($model->load(Yii::$app->request->post()))
        {
            if($model->uid == Yii::$app->user->identity->id)
            {
                if($model->save())
                {
                    if($model->level == 1)
                    {
                        Useraddress::updateAll(['level' => 0],'uid = :uid AND id != :id',[':uid' => Yii::$app->user->identity->id,':id'=> $model->id]);
                    }
                    Yii::$app->session->setFlash('success', 'Successfully  update address');
                }
                else
                {
                    ii::$app->session->setFlash('danger', ' Address Update Fail');
                }
            }
            else
            {
                Yii::$app->session->setFlash('danger', 'You are not the right person');
            }
            return $this->redirect(Yii::$app->request->referrer);
        }
        $this->view->title = 'Edit Address';
        return $this->renderAjax('address',['model'=> $model]);
    }

    public function actionDeleteAddress($id)
    {
        $model= Useraddress::findOne($id);
        if(!empty($model))
        {
            $model->delete();
            Yii::$app->session->setFlash('success', 'Delete Successfully');
        }
        else
        {
            Yii::$app->session->setFlash('warning', 'Fail Delete ');
        }
        return $this->redirect(Yii::$app->request->referrer);
    }

}