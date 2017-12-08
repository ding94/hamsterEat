<?php
namespace backend\controllers;

use Yii;
use yii\web\Controller;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use yii\helpers\ArrayHelper;
use common\models\Area;
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
        $postcode = ArrayHelper::map(Area::find()->all(),'Area_ID','Area_Postcode');

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

                $employee = new CompanyEmployees;
                $employee['cid'] = $company['id'];
                $employee['uid'] = $company['owner_id'];
                $employee['status'] =1;
                $employee->save();
            }
            return $this->redirect(['/company/index']);
        }
    	 
    	return $this->render('register',['company'=>$company,'postcode'=>$postcode]);
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

    public function actionOperate($id)
    {
        $company = Company::find()->where('id=:id',[':id'=>$id])->one();
        if ($company['status']==1) {
           $company['status'] = 0;
        }
        else{
           $company['status'] = 1;
        }
        
        if ($company->validate()) {
            $company->save();
            Yii::$app->session->setFlash('success','Success!');
            return $this->redirect(['/company/index']);
        }
    }
}