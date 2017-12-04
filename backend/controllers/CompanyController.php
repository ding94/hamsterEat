<?php
namespace backend\controllers;

use Yii;
use yii\web\Controller;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use common\models\User;
use common\models\Company\Company;
use common\models\Company\CompanyEmployees;
use yii\web\UploadedFile;

class CompanyController extends Controller
{

	public function actionIndex()
    {
    	$searchModel = new Company();
       	$dataProvider = $searchModel->search(Yii::$app->request->queryParams,1);

        return $this->render('index',['dataProvider'=>$dataProvider, 'searchModel'=>$searchModel]);
    }

    public function actionRegister()
    {
    	$company = new Company();
    	$company->scenario = 'register';

        if (Yii::$app->request->post()) {
            $company->load(Yii::$app->request->post());
            $company['status'] = 1;
            $owner = User::find()->where('username=:u',[':u'=>$company['username']])->one();
            if (!empty($owner)) {
                $company['owner_id'] = $owner['id'];
            }
            else{
                Yii::$app->session->setFlash('error','Fail to found user!');
                return $this->render('register',['company'=>$company]);
            }
            if ($company->validate()) {
                Yii::$app->session->setFlash('success','Success!');
                $company->save();
            }
            return $this->redirect(['/company/index']);
        }
    	 
    	return $this->render('register',['company'=>$company]);
    }

    public function actionEdit($id)
    {
        $company = Company::find()->where('id=:id',[':id'=>$id])->one();
        $company['username'] = User::find()->where('id=:id',[':id'=>$company['owner_id']])->one()->username;
        $company->scenario = 'register';

        if (Yii::$app->request->post()) {
            $company->load(Yii::$app->request->post());
            $company['status'] = 1;
            $owner = User::find()->where('username=:u',[':u'=>$company['username']])->one();
            if (!empty($owner)) {
                $company['owner_id'] = $owner['id'];
            }
            else{
                Yii::$app->session->setFlash('error','Fail to found user!');
                return $this->redirect(['/company/index']);
            }
            if ($company->validate()) {
                Yii::$app->session->setFlash('success','Success!');
                $company->save();
            }
            return $this->redirect(['/company/index']);
        }

        return $this->renderAjax('register',['company'=>$company, 'company'=>$company]);
    }
}