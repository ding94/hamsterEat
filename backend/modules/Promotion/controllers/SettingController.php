<?php

namespace backend\modules\Promotion\controllers;

use Yii;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
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
    public function actionGenerate($id=0)
    {
        if($id==0)
        {
            $model = new Promotion;
        }
        else
        {
            $model = $this->findModel($id);
        }
    	
    	$array = $this->genArrayData();
    	$true = $model->isNewRecord;
    	
    	if($model->load(Yii::$app->request->post()))
    	{
    		$data = $this->save($model);
    		Yii::$app->session->setFlash($data['type'],$data['message']);
    		if($data['valid'] != 0)
    		{
                if($true)
                {
                   return $this->redirect(['/promotion/limit/type-generate','id'=>$data['id']]); 
                }
    			return $this->redirect(['index']);
    		}
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

    /*
    * validate and detect for promotion
    */
    protected static function save($model)
    {
    	$data = array();
    	$data['message'] = "";
    	$data['type'] = "warning";
    	$data['valid'] = 0;

        $new = $model->isNewRecord;
        $current = date("Y-m-d");

    	$post = Yii::$app->request->post();
        
        if(!$new && ($current >= $model->start_date))
        {
            $data['message'] = "Cannot Edit When Promotion Start";
            return $data;
        }
         
    	if(!empty($post['Promotion']['date']))
    	{
    		$date = explode(' - ',$post['Promotion']['date']);
    		$model->start_date = $date[0];
    		$model->end_date = $date[1];
           
    	}
       
    	if($current >= $model->start_date)
    	{
    		$data['message'] = "Please Select Date after ".$current;
    		return $data;
    	}
    
    	if($model->save())
    	{
    		$data['message'] = $new ? "Success Create" : "Success Update";
    		$data['type'] = "success";
    		$data['id'] = $model->id;
    		
    		$data['valid'] = 1;
    		
    	}
    	else
    	{
    		$data['message'] = $new ? "Fail to Create" : "Fail to Update";
    	}
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

    protected static function genArrayData()
    {
    	$array = array();
    	$array['type'] = ArrayHelper::map(PromotionType::find()->all(),'id','description');
        $array['discount'] = [1=>'Discount %',2=>'Discount Amount',3=>'Discount Leave Amount'];
    	$array['selection'] = [1=>'On',0=>'Off'];
    	return $array;
    }
}
