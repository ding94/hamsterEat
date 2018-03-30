<?php

namespace backend\modules\Promotion\controllers;

use Yii;
use yii\web\Controller;
use yii\helpers\ArrayHelper;
use common\models\food\Food;
use common\models\Company\Company;
use common\models\Model;
use common\models\promotion\{Promotion,PromotionLimit};

class LimitController extends Controller
{
	public function actionTypeGenerate($id)
	{
		$promotion = $this->findModel($id);
		
		$data =array();
		switch ($promotion->type_promotion) {
			case 2:
				$data = Food::find()->all();
				break;
			case 4:
				$data = Company::find()->all();
				break;
			default:
				# code...
				break;
		}

		$model = $this->mutipleModel($data);

		if(Yii::$app->request->post())
		{
			if($this->save($model,$promotion->id,$promotion->food_limit))
			{
				return $this->redirect(['/promotion/setting/index']);
			}
		}
		return $this->render('typegenerate',['promotion'=>$promotion,'model'=>$model,'data'=>$data]);
	}

	protected static function mutipleModel($data)
	{
		$model = array();
		foreach($data as $i=> $value)
		{
			$model[$i] = new PromotionLimit;
		}
		return $model;
	}

	protected static function save($model,$id,$foodlimit)
	{
		Model::loadMultiple($model,Yii::$app->request->post());
		
		$limit = self::arrayData($model,2);
		
		if($limit > $foodlimit)
		{
			Yii::$app->session->setFlash('warning', "The Limit is out of the promotion limit");
			return false;
		}
		
		$valid = Model::validateMultiple($model);
		if($valid)
		{
			$valid = self::arrayData($model,1,$id);
			if($valid)
			{
				Yii::$app->session->setFlash('success', "Promotion Create");
				return true;
			}
		}

		Yii::$app->session->setFlash('warning', "Something went Wrong");
		return $valid;
	}

	protected static function arrayData($model,$type,$id=0)
	{
		$limit = 0;
		foreach($model as $data)
		{
			if($type == 2)
			{
				$limit += $data->food_limit;
			}
			else
			{
				if(!$data->save())
				{

					PromotionLimit::deleteAll('pid = :pid',[':pid'=>$id]);
					return false;
					break;
				}
			}
		}

		return $type == 2 ? $limit : true;
	}

	protected static function findModel($id)
	{	
        if (($model = Promotion::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
	}
}