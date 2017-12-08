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
use common\models\Object;

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
        $postcode = ArrayHelper::map(Area::find()->all(),'Area_Postcode','Area_Postcode');

        if (Yii::$app->request->post()) {
            $company->load(Yii::$app->request->post());
            $company['status'] = 1;
            
            $owner = User::find()->where('username=:u',[':u'=>$company['username']])->one();//get owner data by username

            if (!empty($owner)) {
                //if the username was an owner of another company, set flase
                if (CompanyEmployees::find()->where('uid=:uid',[':uid'=>$owner['id']])->one()) {
                    Yii::$app->session->setFlash('error','User was other company owner!');
                    return $this->render('register',['company'=>$company,'postcode'=>$postcode]);
                }
                //else setup owner id
                $company['owner_id'] = $owner['id'];
            }
            else{
                //if username connot be found
                Yii::$app->session->setFlash('error','Fail to found user!');
                return $this->render('register',['company'=>$company,'postcode'=>$postcode]);
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

    public function actionGetArea()
    {
        if (isset($_POST['depdrop_parents'])) {
            $parents = $_POST['depdrop_parents'];
            if ($parents != null) {
                $cat_id = $parents[0];
                $out = self::getAreaList($cat_id); 
                echo json_encode(['output'=>$out, 'selected'=>'']);
                return;
            }
        }
        echo json_encode(['output'=>'', 'selected'=>'']);
    }

    public static function getAreaList($postcode)
    {
        $area = Area::find()->where(['like','Area_Postcode' , $postcode])->select(['Area_ID', 'Area_Area'])->all();
        $areaArray = [];
        foreach ($area as $area) {
            $object = new Object();
            $object->id = $area['Area_Area'];
            $object->name = $area['Area_Area'];

            $areaArray[] = $object;
        }
        return $areaArray;
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