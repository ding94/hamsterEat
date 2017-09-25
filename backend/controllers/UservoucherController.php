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

		return $this->render('index',['model'=>$dataProvider,'searchModel'=>$searchModel]);
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
       		

       		if (empty(Vouchers::find()->where('code = :c',[':c' => $model['code']])->one())) 
       		{
       			$valid = self::actionCreateVoucher($model,$voucher,$id,1);
				if ($valid == true ) {
					Yii::$app->session->setFlash('success', "Voucher created and given to user.");
					return $this->redirect(['/uservoucher/addvoucher','id'=>$id]);
				}
			}
			elseif (!empty(Vouchers::find()->where('code = :c',[':c' => $model['code']])->one())) 
			{
				$valid = self::actionAssign($model,$voucher,$id);
				if ($valid ==true) {
					Yii::$app->session->setFlash('success', "Voucher created and given to user.");
					return $this->redirect(['/uservoucher/addvoucher','id'=>$id]);
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
		$voucher->endDate = time($model->endDate);
		$valid = ValidController::SaveValidCheck($voucher,2);
		if ($valid == true) {
			$model->scenario = 'save';
			$model->endDate = time($model->endDate);
			$model->vid =$voucher->id;
			$valid = ValidController::SaveValidCheck($model,2);
			if ($valid == true) {
				return true;
			}
		}
		return false;
	}

	protected function actionAssign($model,$voucher,$id)
	{
		$voucher = Vouchers::find()->where('code = :c', [':c'=>$model->code])->one();
		$voucher->discount_type = $voucher->discount_type + 1;
		$valid = ValidController::SaveValidCheck($voucher,2);
		if ($valid ==true)
		{
			$model->scenario = 'save';
			$model->uid = $id;
			$model->vid =$voucher->id;
			$model->endDate = time($model->endDate);
			$valid = ValidController::SaveValidCheck($model,2);
			if ($valid == true) 
			{
				return true;
			}
		}
		

	}
}