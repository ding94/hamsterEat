<?php
namespace backend\controllers;

use Yii;
use yii\web\Controller;
use yii\filters\VerbFilter;
use yii\helpers\ArrayHelper;
use yii\filters\AccessControl;
use common\models\user\Usersearch;
use common\models\Vouchers;
use common\models\VouchersType;
use common\models\User;
use backend\models\Admin;
use common\models\UserVoucher;

class UservoucherController extends Controller
{
	public function actionIndex()
	{
		$searchModel = new Usersearch();
       	$dataProvider = $searchModel->search(Yii::$app->request->queryParams,2);
       	$uservoucher = new UserVoucher;
       	$uservoucher->endDate = date('Y-m-d',strtotime('+30 day'));
       	$voucher = new Vouchers;
       	$voucher->scenario = 'generate';
		$voucher->digit = 16;
       	$type = ArrayHelper::map(VouchersType::find()->where(['or',['id'=>1],['id'=>4]])->all(),'id','type');
		$item = ArrayHelper::map(VouchersType::find()->where(['or',['id'=>7],['id'=>8],['id'=>9]])->all(),'id','description');

		if (Yii::$app->request->post()) {
			if (Yii::$app->request->post('selection')) {
       			return $this->render('mulgive',['users'=>Yii::$app->request->post('selection'),'uservoucher'=>$uservoucher,'voucher'=>$voucher,'type'=>$type,'item'=>$item]);
       		}
       		elseif (Yii::$app->request->post('selection')==false) {
       			Yii::$app->session->setFlash('error', "No user was selected.");
       		}
		}
       	
		return $this->render('index',['model'=>$dataProvider,'searchModel'=>$searchModel]);
	}

	public function actionShowuser($id)
	{
		$name = User::find()->where('id =:id',[':id'=>$id])->one()->username;
		if (Yii::$app->request->post()) {
			if (Yii::$app->request->post('selection')) {
				$select = Yii::$app->request->post('selection');
				foreach ($select as $k => $selection) {
					$uvoucher = UserVoucher::find()->where('vid=:id AND uid = :uid',[':id'=>$selection,':uid'=>$id])->one();
					$voucher = Vouchers::find()->where('id=:id',[':id'=>$selection])->one();
					if (!empty($uvoucher)) {
						$uvoucher->delete();
						$voucher->delete();
					}
				}
				Yii::$app->session->setFlash('success', "Deleted!");
				return $this->redirect(['uservoucher/index']);
       		}
		}

		$searchModel = new Vouchers;
       	$dataProvider = $searchModel->usersearch(Yii::$app->request->queryParams,$id,2);
		return $this->render('showuser',['name'=>$name,'model'=>$dataProvider,'searchModel'=>$searchModel,'id'=>$id]);
	}

	public function actionEditvoucher($id)
	{
		$voucher = Vouchers::find()->where('id=:id',[':id'=>$id])->one();
		if ($voucher['endDate'] <= strtotime(time()) || $voucher['discount_type'] == 3 || $voucher['discount_type'] == 6) {
				Yii::$app->session->setFlash('warning', "Coupon was used or expired!");
				return $this->redirect(Yii::$app->request->Referrer);
			}
		$voucher['endDate'] = date('Y-m-d h:i:s', $voucher['endDate']);
		$type = ArrayHelper::map(VouchersType::find()->where(['or',['id'=>1],['id'=>4]])->all(),'id','type');
		$item = ArrayHelper::map(VouchersType::find()->where(['or',['id'=>7],['id'=>8],['id'=>9]])->all(),'id','description');

		if (Yii::$app->request->post()) {
			$voucher->load(Yii::$app->request->post());
			$voucher['endDate'] = strtotime($voucher['endDate']);
			if ($voucher->validate()) {
				$voucher->save();
				Yii::$app->session->setFlash('success', "Edited!");
				return $this->redirect(['uservoucher/index']);
			}
		}
		return $this->render('editvoucher',['voucher'=>$voucher,'type'=>$type,'item'=>$item]);
	}

	public function actionMultigive()
	{

		$post = Yii::$app->request->post();
		$users = Yii::$app->request->post('result');
		$count = count($users);
		$end = strtotime(Yii::$app->request->post('UserVoucher')['endDate']);
		$chars ="ABCDEFGHIJKLMNOPQRSTUVWXYZ1234567890";//code 包含字母
		
		foreach ($users as $k => $userid) 
		{
			$code ="";
			for($i=0;$i<$post['Vouchers']['digit']; $i++)
           	{
       			$code .= $chars[rand(0,strlen($chars)-1)];
    		}

			$voucher = new Vouchers;
			$voucher->load($post);
			$voucher->discount_type +=1;
			$voucher->code = $code;
			$voucher->usedTimes = 0;
			$voucher->inCharge = Yii::$app->user->identity->adminname;
			$voucher->startDate = time(date('Y-m-d'));
			$voucher->endDate = $end;
			if ($voucher->validate()) {
				$voucher->save();

				$uservoucher = new UserVoucher;
				$uservoucher->uid = $userid;
				$uservoucher->vid = $voucher->id;
				$uservoucher->code = $code;
				$uservoucher->endDate = $end;
				if ($uservoucher->validate()) {
					$uservoucher->save();
				}
				else{
					Yii::$app->session->setFlash('error', "Coupon create failed.");
					return $this->redirect(['uservoucher/index']);
				}
			}
			else{
				Yii::$app->session->setFlash('error', "Coupon create failed.");
				return $this->redirect(['uservoucher/index']);
			}
		}
		Yii::$app->session->setFlash('success', "Coupons created and given to user.");
		return $this->redirect(['uservoucher/index']);
	}

	public function actionAddvoucher($id)
	{
		$name = User::find()->where('id = :id',[':id'=>$id])->one()->username;
		$model = new UserVoucher;
		$model->scenario = 'initial';
		$model->endDate = date('Y-m-d',strtotime('+30 day'));
		$voucher = new Vouchers;
		$type = ArrayHelper::map(VouchersType::find()->where(['or',['id'=>1],['id'=>4]])->all(),'id','type');
		$item = ArrayHelper::map(VouchersType::find()->where(['or',['id'=>7],['id'=>8],['id'=>9]])->all(),'id','description');
		$searchModel = new Vouchers();
       	$dataProvider = $searchModel->search(Yii::$app->request->queryParams,2);

       	if (Yii::$app->request->post()) {

       		$model->load(Yii::$app->request->post());
       		$model->uid = $id;
       		$voucher->load(Yii::$app->request->post());
       		$valid = ValidController::UserVoucherCheckValid($model,$voucher,1);
			if ($valid == true ) 
			{
				$check = Vouchers::find()->where('code = :c',[':c' => $model['code']])->all();
				if (empty($check)) 
       			{
       				$valid = self::actionCreateVoucher($model,$voucher,$id,1);
					if ($valid == true ) {
						Yii::$app->session->setFlash('success', "Voucher created and given to user.");
						return $this->redirect(['/uservoucher/addvoucher','id'=>$id]);
					}
				}
				elseif (!empty($check)) 
				{
					$valid = self::actionAssign($model,$check,$id);
					if ($valid ==true) {
						Yii::$app->session->setFlash('success', "Voucher created and given to user.");
						return $this->redirect(['/uservoucher/addvoucher','id'=>$id]);
					}
				}
			}
       	}

		return $this->render('givevoucher',['name'=>$name,'model'=>$model,'voucher'=>$voucher,'type'=>$type,'item'=>$item,'dataProvider'=>$dataProvider,'searchModel'=>$searchModel]);

	}

	protected function actionCreateVoucher($model,$voucher,$id)
	{
		$voucher->code = $model->code;
		$voucher->discount_type = $voucher->discount_type + 1;
		$voucher->usedTimes = 0;
		$voucher->inCharge = Yii::$app->user->identity->adminname;
		$voucher->startDate = time();
		$voucher->endDate = strtotime($model->endDate);

		$valid = ValidController::SaveValidCheck($voucher,2);
		if ($valid == true) {
			$model->scenario = 'save';
			$model->endDate = strtotime($model->endDate);
			$model->vid =$voucher->id;
			$valid = ValidController::SaveValidCheck($model,2);
			if ($valid == true) {
				return true;
			}
		}
		elseif ($valid == false)
		{
			$voucher->delete();
		}
		return false;
	}

	protected function actionAssign($model,$check,$id)
	{

		foreach ($check as $k => $value) {
			$value->discount_type = $value->discount_type + 1;
			$value->startDate = time();
			$value->endDate = strtotime($model->endDate);
			$valid = ValidController::SaveValidCheck($value,2);
		}
		
		if ($valid ==true)
		{
			$model->scenario = 'save';
			$model->uid = $id;
			$model->vid =$value->id;
			$model->endDate = strtotime($model->endDate);
			$valid = ValidController::SaveValidCheck($model,2);
			if ($valid == true) 
			{
				return true;
			}
		}
		else
		{
			return false;
		}
		

	}
}