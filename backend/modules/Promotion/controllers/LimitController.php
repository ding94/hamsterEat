<?php

namespace backend\modules\Promotion\controllers;

use Yii;
use yii\web\Controller;
use yii\base\Model
use yii\helpers\ArrayHelper;
use common\models\food\Food;
use common\models\Company\Company;
use common\models\promotion\{Promotion,PromotionLimit};

class LimitController extends Controller
{
	public function actionTypeGenerate($id)
	{
		$promotion = $this->findModel($id);
		$model = new PromotionLimit;
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
		if(Yii::$app->request->post())
		{
			if($this->save())
			{
				
			}
		}
		return $this->render('typegenerate',['promotion'=>$promotion,'model'=>$model,'data'=>$data]);
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