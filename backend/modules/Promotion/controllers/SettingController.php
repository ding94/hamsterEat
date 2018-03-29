<?php

namespace backend\modules\Promotion\controllers;

use Yii;
use yii\web\Controller;
use yii\helpers\ArrayHelper;
use common\models\promotion\{Promotion,PromotionType};
use common\models\Company\Company;

/**
 * Setting controller for the `promotion` module
 */
class SettingController extends Controller
{
    /**
     * Renders the index view for the module
     * @return string
     */
    public function actionIndex()
    {
        return $this->render('index');
    }

    /*
    * create or edit promotion
    */
    public function actionGenerate()
    {
    	$model = new Promotion;

    	$array['type'] = ArrayHelper::map(PromotionType::find()->all(),'id','description');
    	$array['discount'] = [1=>'Discount %',2=>'Discount Amount',3=>'Discount Leave Amount'];
    	$true = $model->isNewRecord;
    	
    	if($model->load(Yii::$app->request->post()))
    	{
    		if($model->save())
    		{
    			Yii::$app->session->setFlash('success', "Promotion  success Create");
    			if($true && $model->PromotionType != 1)
	    		{
	    			return $this->redirect(['/promotion/limit/type-generate','id'=>$model->id]);
	    		}
	    		return $this->redirect(['index']);
    		}

    		Yii::$app->session->setFlash('danger', "Promotion  Fail Create");
    	}	
    	return $this->render('createEdit',['model'=>$model,'array'=>$array]);
    }
}
