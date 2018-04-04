<?php

namespace backend\modules\Promotion\controllers;

use Yii;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
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

		$model = $this->mutipleModel($data,$id);
		
		if(Yii::$app->request->post())
		{
			if($this->save($model,$promotion->id))
			{
				return $this->redirect(['/promotion/setting/index']);
			}
		}
		return $this->render('typegenerate',['promotion'=>$promotion,'model'=>$model,'data'=>$data]);
	}

	protected static function mutipleModel($data,$id)
	{
		$model = array();
		foreach($data as $i=> $value)
		{
			$limit = PromotionLimit::find()->where('pid = :pid and tid = :tid',[':pid'=>$id,':tid'=>$value->id])->one();
			$promotion = empty($limit) ? new PromotionLimit : $limit;
			$model[$i] = $promotion;
		}
		return $model;
	}

	protected static function save($model,$id)
	{

		Model::loadMultiple($model,Yii::$app->request->post());
		
		$data = self::arrayData($model);
		$model = $data['model'];
		$limit = $data['limit'];
		
		if(empty($model))
		{
			Yii::$app->session->setFlash('warning', "At Least One Item Must More Then Zero");
			return false;
		}
		
		$valid = Model::validateMultiple($model);

		if($valid)
		{
			foreach($model as $value)
			{
				if(!$value->save())
				{
					PromotionLimit::deleteAll('pid = :pid',[':pid'=>$id]);
					$valid = false;
					break;
				}
				else
				{
					$valid = true;
				}
			}
			if($valid)
			{
				Yii::$app->session->setFlash('success', "Promotion Create");
				return true;
			}
		}

		Yii::$app->session->setFlash('warning', "Something went Wrong");
		return $valid;
	}

	protected static function arrayData($model)
	{
		$data =array();
		$limit = 0;
		foreach($model as $i=>$value)
		{
			if($value->isNewRecord && $value->food_limit == 0)
			{
				unset($model[$i]);
			}
			else
			{
				$limit += $value->food_limit;
			}
		}
		$data['model'] = $model;
		$data['limit'] = $limit;
		return $data;
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