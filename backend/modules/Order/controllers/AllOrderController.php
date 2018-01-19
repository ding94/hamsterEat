<?php

namespace backend\modules\Order\controllers;

use Yii;
use yii\web\Controller;
use yii\helpers\ArrayHelper;
use common\models\User;
use common\models\Order\StatusType;
use common\models\Order\Orders;
use backend\models\OrderSearch;

class AllOrderController extends Controller
{
	public function actionIndex()
	{
		$searchModel = new OrderSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams,4);

        $alluser = ArrayHelper::map(User::find()->where('status = 10')->all(),'username','username');
        $allstatus =ArrayHelper::map(StatusType::find()->all(),'id','type');
        $arrayData['user'] = $alluser;
        $arrayData['status'] = $allstatus;

        return $this->render('index',['model' => $dataProvider , 'searchModel' => $searchModel ,'arrayData'=>$arrayData]);
	}

	public function actionPrice($id)
	{
		$model = Orders::findOne($id);
		return $this->renderAjax('_price',['model'=>$model]);
	}
}