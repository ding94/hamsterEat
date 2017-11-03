<?php
namespace backend\controllers;

use Yii;
use yii\web\Controller;
use yii\filters\VerbFilter;
use yii\helpers\ArrayHelper;
use yii\filters\AccessControl;
use common\models\User;
use common\models\Vouchers;
use common\models\VouchersType;
use backend\models\Admin;
use common\models\UserVoucher;

class VouchersController extends Controller
{
	public function actionIndex()
	{
		$searchModel = new Vouchers();
       	$dataProvider = $searchModel->search(Yii::$app->request->queryParams,1);
       	
        return $this->render('index',['model'=>$dataProvider, 'searchModel'=>$searchModel]);
	}

	public function actionDelete($direct)
	{
		if (Yii::$app->request->post()) {
       		if (Yii::$app->request->post('selection')) {
       			$selection=Yii::$app->request->post('selection'); //拿取选择的checkbox + 他的 id
    			if (!empty($selection)) 
    			{
	    	 		foreach($selection as $id)
	    	 		{
	                       if (UserVoucher::find()->where('vid = :id', [':id' => $id])->one()) 
	                       {
	                           $del = UserVoucher::find()->where('vid = :id', [':id' => $id])->one();
	                           $del->delete();
	                       }
	                       
	           				$delete=Vouchers::findOne((int)$id);//make a typecasting //找一个删一个
		          		 	$delete->delete();
		          		 	Yii::$app->session->setFlash('success', "Deleted!");
        			}
	    	 	}
	    	 	elseif(empty($selection))
	    	 	{
	    	 		Yii::$app->session->setFlash('error', "No Voucher/Record was selected!");
	    	 	}
       		}
       	}
       	switch ($direct) 
       	{
       		case 1:
       			return $this->redirect(['/vouchers/index']);
       			break;
       		case 2:
       			return $this->redirect(['/uservoucher/index']);
       			break;
       		case 3:
       			return $this->redirect(['/vouchers/specific']);
       			break;
       		
       		default:
       			return $this->redirect(['/vouchers/index']);
       			break;
       	}
	}

	public function actionPage($page)
	{
		$searchModel = new Vouchers();
       	$dataProvider = $searchModel->search(Yii::$app->request->queryParams,$page);
       	return $this->render('index',['model'=>$dataProvider, 'searchModel'=>$searchModel]);

	}	

	public function actionAdd()
	{
		$model = new Vouchers;
		$model->scenario = 'initial';
		$model->startDate = date('Y-m-d');
		$type = ArrayHelper::map(VouchersType::find()->where(['or',['id'=>1],['id'=>4]])->all(),'id','type');
		$item = ArrayHelper::map(VouchersType::find()->where(['or',['id'=>7],['id'=>8],['id'=>9]])->all(),'id','description');

		if (Yii::$app->request->post()) {

			$model->load(Yii::$app->request->post());

			$valid = ValidController::VoucherCheckValid($model,1);
			if ($valid ==true) 
			{
				
				$model = self::actionCreate($model);
				
				$valid = ValidController::SaveValidCheck($model,1);
				if ($valid==true) 
				{
					return $this->redirect(['/vouchers/add']);
				}
			}
		}
       	return $this->render('addvoucher',['model' => $model,'type'=>$type,'item'=>$item]);
	}

	public function actionCreate($model)
	{
		$model->startDate = time($model->startDate);

		if (!empty($model->endDate)) {
			$model->endDate = time($model->endDate);
		}
		$model->usedTimes = 0;
		$model->inCharge = Yii::$app->user->identity->adminname;
		$model->scenario = 'save';

		return $model;
	}

	public function actionGenerate()
	{
		$model = new Vouchers;

		$model->scenario = 'generate';
		$model->digit = 16;
		$model->startDate = date('Y-m-d');
		$type = ArrayHelper::map(VouchersType::find()->where(['or',['id'=>1],['id'=>4]])->all(),'id','type');
		$item = ArrayHelper::map(VouchersType::find()->where(['or',['id'=>7],['id'=>8],['id'=>9]])->all(),'id','description');
		if (Yii::$app->request->post()) 
		{
			$model->load(Yii::$app->request->post());
			$valid = ValidController::VoucherCheckValid($model,2);
			if ($valid == true) {
				
				$valid = self::actionGencodes($model);
				if ($valid==true) {
					return $this->redirect(['/vouchers/generate']);
				}
			}
		}
		return $this->render('gencodes',['model'=>$model,'type'=>$type,'item'=>$item]);
	}

	public function actionGencodes($post)
	{
		$chars ="ABCDEFGHIJKLMNOPQRSTUVWXYZ1234567890";//code 包含字母
		$count = 0;

		for ($j=1; $j <= $post->amount ; $j++) 
        { 
        	$model = new Vouchers;
        	$model->scenario = 'save';
        	$model->discount = $post->discount;
        	$model->discount_type = $post->discount_type;
        	$model->discount_item = $post->discount_item;
        	$model->usedTimes = 0;
        	$model->inCharge = Yii::$app->user->identity->adminname;
      		$model->startDate = strtotime($post->startDate);
    		if (!empty($post->endDate))
    		{
				$model->endDate = strtotime($post->endDate);
			}

           	for($i=0;$i<$post->digit; $i++)
           	{
       			$model->code .= $chars[rand(0,strlen($chars)-1)];
    		}
    		if (Vouchers::find()->where('code = :c', [':c' => $model->code])->one()==true) 
    		{
    			$j=$j-1;
    			$count +=1;
    			if ($count >10) 
    			{
    				Yii::$app->session->setFlash('error','All generated code duplicated!');
    				return $this->redirect(Yii::$app->request->referrer);
    			}
    		}

    		else
    		{
    			$valid = ValidController::SaveValidCheck($model,1);
    		}
        }
	        return $valid;
	}

	public function actionMore($id)
	{
		$voucher = Vouchers::find()->where('id = :id',[':id'=>$id])->one();
		if ($voucher['discount_type'] != 1) {
			if ($voucher['discount_type'] != 4) {
				Yii::$app->session->setFlash('error','This coupon was assigned to an user or expired!');
	    		return $this->redirect(['/vouchers/index']);
			}
		}
		$conflic = Vouchers::find()->where('code = :c',[':c'=>$voucher['code']])->all();
		$used = 0;
		foreach ($conflic as $k => $value) {
			if ($value['discount_item'] == 7) {
				$used += 1;
			}
			if ($value['discount_item'] == 8) {
				$used += 1;
			}
		}
		if ($used == 2) {
			Yii::$app->session->setFlash('warning','Both discount item was usd for this coupon!');
	    	return $this->redirect(['/vouchers/index']);
		}
		$type = ArrayHelper::map(VouchersType::find()->where(['or',['id'=>1],['id'=>4]])->all(),'id','type');
		$item = ArrayHelper::map(VouchersType::find()->where(['or',['id'=>7],['id'=>8]])->all(),'id','description');
		$items = ArrayHelper::remove($item, $voucher['discount_item']);
		$model = new Vouchers;

		if (Yii::$app->request->post()) {
			$model->load(Yii::$app->request->post());
			$valid = ValidController::VoucherCheckValid($model,2);

			if ($valid == false) {
				return $this->render('morediscount',['model'=>$model,'item'=>$item,'voucher'=>$voucher,'type'=>$type]);
			}

			$model->code = $voucher->code;
			$model->usedTimes = $voucher->usedTimes;
			$model->inCharge = Yii::$app->user->identity->adminname;
			$model->startDate = $voucher->startDate;
			$model->endDate = $voucher->endDate;
			$valid = ValidController::SaveValidCheck($model,1);
			
			if ($valid) {
    			return $this->redirect(['/vouchers/index']);
			}
		}
		return $this->render('morediscount',['model'=>$model,'item'=>$item,'voucher'=>$voucher,'type'=>$type]);
	}

	public function actionSpecific()
	{
		$searchModel = new Vouchers();
       	$dataProvider = $searchModel->search(Yii::$app->request->queryParams,5);

       	return $this->render('specific',['model'=>$dataProvider, 'searchModel'=>$searchModel]);
	}

	public function actionMorespec($id)
	{
		$voucher = Vouchers::find()->where('id = :id',[':id'=>$id])->one();
		if ($voucher['discount_type'] != 100) {
			if ($voucher['discount_type'] != 101) {
				Yii::$app->session->setFlash('error','This voucher was not specialized for employees!');
	    		return $this->redirect(['/vouchers/specific']);
			}
		}
		$conflic = Vouchers::find()->where('code = :c',[':c'=>$voucher['code']])->all();
		$used = 0;
		foreach ($conflic as $k => $value) {
			if ($value['discount_item'] == 7) {
				$used += 1;
			}
			if ($value['discount_item'] == 8) {
				$used += 1;
			}
		}
		if ($used == 2) {
			Yii::$app->session->setFlash('warning','Both discount item was usd for this voucher!');
	    	return $this->redirect(['/vouchers/specific']);
		}
		$type = ArrayHelper::map(VouchersType::find()->where(['or',['id'=>100],['id'=>101]])->all(),'id','type');
		$item = ArrayHelper::map(VouchersType::find()->where(['or',['id'=>7],['id'=>8]])->all(),'id','description');
		$items = ArrayHelper::remove($item, $voucher['discount_item']);
		$model = new Vouchers;

		if (Yii::$app->request->post()) {
			$model->load(Yii::$app->request->post());
			$valid = ValidController::VoucherCheckValid($model,2);

			if ($valid == false) {
				return $this->render('morespec',['model'=>$model,'item'=>$item,'voucher'=>$voucher,'type'=>$type]);
			}

			$model->code = $voucher->code;
			$model->usedTimes = $voucher->usedTimes;
			$model->inCharge = Yii::$app->user->identity->adminname;
			$model->startDate = $voucher->startDate;
			$model->endDate = $voucher->endDate;
			$valid = ValidController::SaveValidCheck($model,1);
			
			if ($valid) {
    			return $this->redirect(['/vouchers/specific']);
			}
		}
		return $this->render('morediscount',['model'=>$model,'item'=>$item,'voucher'=>$voucher,'type'=>$type]);
	}

}	