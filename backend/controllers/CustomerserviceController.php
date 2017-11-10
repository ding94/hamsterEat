<?php
namespace backend\controllers;

use Yii;
use yii\web\Controller;
use yii\filters\VerbFilter;
use yii\helpers\ArrayHelper;
use yii\filters\AccessControl;
use common\models\User;
use common\models\Orders;
use common\models\Orderitem;
use common\models\problem\ProblemOrder;
use backend\models\OrderitemSearch;

class CustomerserviceController extends Controller
{
	public function actionPausedorder()
	{
		$searchModel = new OrderitemSearch();
       	$dataProvider = $searchModel->search(Yii::$app->request->queryParams,1);
       	
        return $this->render('pausedorder',['searchModel'=>$searchModel,'dataProvider'=>$dataProvider,'page'=>'problem']);
	}

	public function actionComproblem()
	{
		$searchModel = new OrderitemSearch();
       	$dataProvider = $searchModel->search(Yii::$app->request->queryParams,2);
       	
        return $this->render('pausedorder',['searchModel'=>$searchModel,'dataProvider'=>$dataProvider,'page'=>'solved']);
	}

	public function actionSolved($id)
	{
		$problem = ProblemOrder::find()->where('Order_ID=:id',[':id'=>$id])->one();
		$problem['status'] = 0;
		if ($problem->validate()) {
			$problem->save();
			Yii::$app->session->setFlash('success','Action complete.');
		}
		else
		{
			Yii::$app->session->setFlash('error','Action.');
		}
		return $this->redirect(['/customerservice/pausedorder']);
	}

	public function actionDetail($id)
	{
		$orderitem=Orderitem::find()->where('Order_ID=:id',[':id'=>$id])->one();
		$order = Orders::find()->where('DeliverY_ID=:did',[':did'=>$orderitem['Delivery_ID']])->one();

		return $this->renderAjax('detail',['orderitem'=>$orderitem,'order'=>$order]);
	}
}