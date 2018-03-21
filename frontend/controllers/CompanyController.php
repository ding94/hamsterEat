<?php
namespace frontend\controllers;

use Yii;
use yii\web\Controller;
use common\models\Company\Company;
use common\models\Company\CompanyEmployees;
use common\models\User;
use yii\web\Response;
use yii\db\Query;
use yii\helpers\ArrayHelper;
use yii\data\Pagination;
use yii\filters\AccessControl;

class CompanyController extends CommonController
{
	public function behaviors()
    {
         return [
             'access' => [
                 'class' => AccessControl::className(),
                 //'only' => ['logout', 'signup','index'],
                 'rules' => [
                    [
                        'actions' => ['index','removeemployee','userlist'],
                        'allow' => true,
                        'roles' => ['@'],

                    ],
                    //['actions' => [],'allow' => true,'roles' => ['?'],],
                    
                 ]
             ]
        ];
    }

	public function actionIndex()
	{
		$emplo = new CompanyEmployees;
		
		$company = Company::find()->where('owner_id=:id',[':id'=>Yii::$app->user->identity->id])->one();
		
		$users = CompanyEmployees::find()->where('cid=:id',[':id'=>$company['id']])->andWhere(['!=','uid',Yii::$app->user->identity->id]);
		$countQuery = clone $users;
        $pagination = new Pagination(['totalCount'=>$countQuery->count(),'pageSize'=>10]);
        $users = $users->offset($pagination->offset)->limit($pagination->limit)->all();
		
		if (empty($company)) {
			Yii::$app->session->setFlash('error',Yii::t('common','Error!'));
			return $this->redirect('/site/index');
		}
		if (Yii::$app->request->post()) {
		
			$emplo->load(Yii::$app->request->post());

			if (empty($emplo['uid'])) {
				Yii::$app->session->setFlash('error',Yii::t('company','Input was empty!'));
				
				return $this->redirect(['/company/index']);
			}
			if (CompanyEmployees::find()->where('uid=:uid',[':uid'=>$emplo['uid']])->one()) {
				Yii::$app->session->setFlash('warning',Yii::t('company','Repeated employee!'));
				return $this->redirect(['/company/index']);
			}

			$emplo['cid'] = $company['id'];
			$emplo['status'] =1;

			if ($emplo->validate()) {
				$emplo->save();
				Yii::$app->session->setFlash('success',Yii::t('cart','Success!'));
				return $this->redirect(['/company/index']);
			}
		}
		return $this->render('index',['emplo'=>$emplo,'company'=>$company,'pagination'=>$pagination,'users'=>$users]);
	}

	public function actionUserlist($q = null, $id = null) 
	{
	    \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
	    $out = ['results' => ['id' => '', 'text' => '']];
	    if (!is_null($q)) {
	        $query = new Query;
	        $query= User::find()->select('id , username')->where('username=:u',[':u'=>$q])->andWhere(['!=','id',Yii::$app->user->identity->id])->all();
	        $out['results'] = array_values($query);
	    }
	    elseif ($id > 0) {
	        $out['results'] = ['id' => $id, 'text' => User::find($id)->username];
	    }
	    return $out;
	}

	public function actionRemoveemployee($id)
	{
		$employee = CompanyEmployees::find()->where('id=:id',[':id'=>$id])->one();
		$employer = CompanyEmployees::find()->where('uid=:uid',[':uid'=>Yii::$app->user->identity->id])->one();
		$owner = Company::find()->where('owner_id=:oid',[':oid'=>Yii::$app->user->identity->id])->one();

		if (empty($owner) || $employee['uid'] == $employer['uid'] || $employee['cid'] != $employer['cid']) {
			Yii::$app->session->setFlash('danger',Yii::t('common','You are not allow to perfrom this action!'));
			return $this->redirect(['/company/index']);
		}
		else{
			$employee->delete();
			Yii::$app->session->setFlash('warning',Yii::t('company','Deleted!'));
			return $this->redirect(['/company/index']);
		}
		
	}

}