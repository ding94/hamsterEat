<?php

namespace backend\modules\Promotion\controllers;

use Yii;
use yii\web\Controller;
use yii\helpers\ArrayHelper;
use backend\models\PromotionSearch;
use common\models\promotion\{Promotion,PromotionType,PromotionLimit};
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
    	$searchModel = new PromotionSearch();
       	$dataProvider = $searchModel->search(Yii::$app->request->queryParams);
       	$array = $this->genArrayData();

        return $this->render('index',['dataProvider'=>$dataProvider, 'searchModel'=>$searchModel,'array'=>$array]);
    }

    /*
    * create or edit promotion
    */
    public function actionGenerate()
    {
    	$model = new Promotion;
    	$array = $this->genArrayData();
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

    		Yii::$app->session->setFlash('warning', "Promotion  Fail Create");
    	}	
    	return $this->render('createEdit',['model'=>$model,'array'=>$array]);
    }

    public function actionDelete($id)
    {
    	$model = $this->findModel($id);
    	$today = date("Y-m-d");
    	if($today >= $model->start_date)
    	{
    		Yii::$app->session->setFlash('warning', "Promotion Cannot Delete");
    	}
    	else
    	{
    		$id = $model->id;
    		if(PromotionLimit::deleteAll('pid = :id',["id"=>$id]) && $model->delete())
    		{
    			Yii::$app->session->setFlash('sucess', "Promotion Delete Success");
    		}
    		else
    		{
    			Yii::$app->session->setFlash('warning', "Promotion Cannot Delete");
    		}
    	}
    	return $this->redirect(['index']);
    }

    protected static function findModel($id)
    {
    	if (($model = Promotion::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

    protected static function genArrayData()
    {
    	$array = array();
    	$array['type'] = ArrayHelper::map(PromotionType::find()->all(),'id','description');
    	$array['discount'] = [1=>'Discount %',2=>'Discount Amount',3=>'Discount Leave Amount'];
    	return $array;
    }
}
