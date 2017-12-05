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

class CompanyController extends CommonController
{
	public function actionIndex()
	{
		$emplo = new CompanyEmployees;
		$this->layout = 'user';
		$company = Company::find()->where('owner_id=:id',[':id'=>Yii::$app->user->identity->id])->one();
		
		$users = CompanyEmployees::find()->where('cid=:id',[':id'=>$company['id']]);
		$countQuery = clone $users;
        $pagination = new Pagination(['totalCount'=>$countQuery->count(),'pageSize'=>10]);
        $users = $users->offset($pagination->offset)
        ->limit($pagination->limit)
      
        ->all();
		
		if (empty($company)) {
			Yii::$app->session->setFlash('error','Error!');
			return $this->redirect('/site/index');
		}
		if (Yii::$app->request->post()) {
			$emplo->load(Yii::$app->request->post());

			if (empty($emplo['uid'])) {
				Yii::$app->session->setFlash('error','Input was empty!');
				return $this->render('index',['emplo'=>$emplo,'pagination'=>$pagination,'users'=>$users]);
			}

			$emplo['cid'] = $company['id'];
			$emplo['status'] =1;

			if ($emplo->validate()) {
				$emplo->save();
				Yii::$app->session->setFlash('success','Success!');
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

}