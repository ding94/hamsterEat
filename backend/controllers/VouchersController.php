<?php
namespace backend\controllers;

use Yii;
use yii\web\Controller;
use yii\filters\VerbFilter;
use yii\helpers\ArrayHelper;
use yii\filters\AccessControl;
use common\models\User;
use common\models\vouchers\{Vouchers,VouchersStatus,VouchersConditions,VouchersSetCondition,DiscountType,DiscountItem,UserVoucher};
use backend\models\Admin;

class VouchersController extends CommonController
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
	    	 			$voucher = Vouchers::find()->where('id=:id',[':id'=>$id])->one();
    					$vouchers = Vouchers::find()->where('code=:c',[':c'=>$voucher['code']])->all();
    					foreach ($vouchers as $k => $vou) {
    						$uservou = UserVoucher::find()->where('vid = :id', [':id' => $vou['id']])->one();
    						if ($uservou)
	                       	{
	                           	$del = $uservou;
	                           	$del->delete();
	                       	}

	           				$delete=Vouchers::findOne((int)$vou['id']);//make a typecasting //找一个删一个
		          		 	$delete->delete();
		          		 	Yii::$app->session->setFlash('success', "Deleted!");
    					}
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

	//function for adding normal vouchers
	public function actionAdd()
	{
		$model = new Vouchers;
		$model->scenario = 'initial';
		$model->startDate = date('Y-m-d');
		$type = ArrayHelper::map(DiscountType::find()->all(),'id','description');
		$item = ArrayHelper::map(DiscountItem::find()->all(),'id','description');

		if (Yii::$app->request->post()) {

			$model->load(Yii::$app->request->post());

			$valid = ValidController::VoucherCheckValid($model,1);
			if ($valid ==true) 
			{
				
				$model = self::actionCreate($model);
				$model['status'] = 1;
				$valid = ValidController::SaveValidCheck($model,1);
				if ($valid==true) 
				{
					return $this->redirect(['/vouchers/add']);
				}
			}
		}
       	return $this->render('addvoucher',['model' => $model,'type'=>$type,'item'=>$item]);
	}


	//function for addding employee's vouchers
	public function actionAddspec()
	{
		$model = new Vouchers;
		$model->scenario = 'initial';
		$model->startDate = date('Y-m-d');
		$setcon = new VouchersSetCondition();
		$type = ArrayHelper::map(DiscountType::find()->all(),'id','description');
		$item = ArrayHelper::map(DiscountItem::find()->all(),'id','description');
		$con = ArrayHelper::map(VouchersConditions::find()->all(),'id','description');

		if (Yii::$app->request->post()) {
			$setcon->load(Yii::$app->request->post());
			$model->load(Yii::$app->request->post());
			if ($setcon['condition_id'] == 2) {
				if (empty($setcon['amount'])) {
					Yii::$app->session->setFlash('warning','Please fill in amount for condition');
					return $this->redirect(Yii::$app->request->referrer);
				}
			}
			$valid = ValidController::VoucherCheckValid($model,4);
			if ($valid ==true) 
			{
				$model = self::actionCreate($model);
				$model['status'] = 5;
				if ($model->validate()) 
				{
					$model->save();
					if (!empty($setcon['condition_id'])) {
						$setcon['vid'] = $model['id'];
						$setcon['code'] = $model['code'];
						$setcon->save();
					}
					Yii::$app->session->setFlash('success','Voucher Created!');
					return $this->redirect(['/vouchers/specific']);
				}
			}
		}
       	return $this->render('addspecvoucher',['model' => $model,'type'=>$type,'item'=>$item,'con'=>$con,'setcon'=>$setcon]);
	}

	//create a voucher
	public function actionCreate($model)
	{
		$model->startDate = time($model->startDate);

		if (!empty($model->endDate)) {
			$model->endDate = time($model->endDate);
		}
		$model->usedTimes = 0;
		$model->status = 1;
		$model->inCharge = Yii::$app->user->identity->adminname;
		$model->scenario = 'save';

		return $model;
	}

	//create amount of vouchers
	public function actionGenerate()
	{
		$model = new Vouchers;

		$model->scenario = 'generate';
		$model->digit = 16;
		$model->startDate = date('Y-m-d');
		$type = ArrayHelper::map(DiscountType::find()->all(),'id','description');
		$item = ArrayHelper::map(DiscountItem::find()->all(),'id','description');
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

	//voucher code generation
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

	//adding second functions to a voucher
	public function actionMore($id)
	{
		$voucher = Vouchers::find()->where('id = :id',[':id'=>$id])->one();
		if ($voucher['status'] != 1) {
				Yii::$app->session->setFlash('error','This coupon was assigned to an user or expired!');
	    		return $this->redirect(['/vouchers/index']);
		}
		$conflic = Vouchers::find()->where('code = :c',[':c'=>$voucher['code']])->all();
		$used = 0;
		foreach ($conflic as $k => $value) {
			if ($value['discount_item'] == 1) {
				$used += 1;
			}
			if ($value['discount_item'] == 2) {
				$used += 1;
			}
		}
		if ($used == 2) {
			Yii::$app->session->setFlash('warning','Both discount item was usd for this coupon!');
	    	return $this->redirect(['/vouchers/index']);
		}
		$type = ArrayHelper::map(DiscountType::find()->all(),'id','description');
		$item = ArrayHelper::map(DiscountItem::find()->all(),'id','description');
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
			$model['status'] = 1;
			$valid = ValidController::SaveValidCheck($model,1);
			
			if ($valid) {
    			return $this->redirect(['/vouchers/index']);
			}
		}
		return $this->render('morediscount',['model'=>$model,'item'=>$item,'voucher'=>$voucher,'type'=>$type]);
	}

	//show employee's vouchers
	public function actionSpecific()
	{
		$searchModel = new Vouchers();
       	$dataProvider = $searchModel->search(Yii::$app->request->queryParams,5);

       	return $this->render('specific',['model'=>$dataProvider, 'searchModel'=>$searchModel]);
	}

	//adding vouchers function for employee voucher
	public function actionMorespec($id)
	{
		$voucher = Vouchers::find()->where('id = :id',[':id'=>$id])->one();
		if ($voucher['status'] != 5) {
				Yii::$app->session->setFlash('error','This voucher was not specialized for employees!');
	    		return $this->redirect(['/vouchers/specific']);
		}

		$conflic = Vouchers::find()->where('code = :c',[':c'=>$voucher['code']])->all();
		$used = 0;
		foreach ($conflic as $k => $value) {
			if ($value['discount_item'] == 1) {
				$used += 1;
			}
			if ($value['discount_item'] == 2) {
				$used += 1;
			}
		}
		if ($used == 2) {
			Yii::$app->session->setFlash('warning','Both discount item was usd for this voucher!');
	    	return $this->redirect(['/vouchers/specific']);
		}
		$type = ArrayHelper::map(DiscountType::find()->all(),'id','description');
		$item = ArrayHelper::map(DiscountItem::find()->all(),'id','description');
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
			$model->status = 5;
			$valid = ValidController::SaveValidCheck($model,1);
			
			if ($valid) {
    			return $this->redirect(['/vouchers/specific']);
			}
		}
		return $this->render('morediscount',['model'=>$model,'item'=>$item,'voucher'=>$voucher,'type'=>$type]);
	}

}	