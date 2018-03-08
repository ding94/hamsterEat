<?php

namespace frontend\modules\Food\controllers;

use Yii;
use yii\web\Controller;
use yii\helpers\ArrayHelper;
use frontend\controllers\CommonController;
use common\models\food\{Foodtypejunction,Foodtype,Food,FoodName,Foodstatus};

/**
 * Default controller for the `food` module
 */
class DefaultController extends CommonController
{
    /**
     * Renders the index view for the module
     * @return string
     */
    public function actionCreateEditFood($rid,$id=0)
    {
        CommonController::restaurantPermission($rid);
        $food = Food::findOne($id);
        if(empty($food))
        {
        	$food = new Food;
    		$food->Restaurant_ID =$rid;
    		$name = new FoodName;
    		$junction = new Foodtypejunction;
        }
        else
        {
        	$name = $food->transName;
        	$junction = Foodtypejunction::find()->where('Food_ID = :fid',[':fid'=>$id])->one();
        	if(empty($junction))
        	{
        		$junction = new Foodtypejunction;
        	}
        }
        $new = $food->isNewRecord ? 0 :1;
    	$link = CommonController::getRestaurantEditUrl($id,$rid,$new);

    	$foodtype = ArrayHelper::map(Foodtype::find()->all(),'ID','Type_Desc');
    	

    	if($food->load(Yii::$app->request->post()) && $name->load(Yii::$app->request->post()))
    	{
    		$data = $this->save($food,$name,$junction);
    		if($data['valid'])
    		{
    			Yii::$app->session->setFlash('success',Yii::t('cart','Success!'));
    			if($food->isNewRecord)
    			{
    				return $this->redirect(['/Food/selection/create-edit','id'=>$data['id'],'rid'=>$rid]);
    			}
    			else
    			{
    				return $this->redirect(['create-edit-food','id'=>$data['id'],'rid'=>$rid]);
    			}
	    		
    		}
    		$junction->scenario ="default";
    		
    		Yii::$app->session->setFlash('error',Yii::t('cart','Something Went Wrong!'));
    	}

    	return $this->render('crfood',['food'=>$food,'name'=>$name,'foodtype'=>$foodtype,'junction'=>$junction,'link'=>$link]);
    	
    }

    protected static function save($food,$name,$junction)
    {
    	$arrayJ = TypeAndStatusController::detectJunction($junction);
    	if($arrayJ['message'] == 0)
    	{
    		return false;
    	}

    	
    	$junction = $arrayJ['value'];

    	$array = TypeAndStatusController::createStatus($food);

    	$food = $array['food'];
    	
    	if(!empty($array['status']))
    	{

    		$status = $array['status'];
    		$valid = $status->validate();
    	}
    	else
    	{
    		$valid = true;
    	}
    	
    	$name->language = "en";
    	
	    $valid = $junction->validate() && $food->validate() && $name->validate() && $valid;
	   	$data['id'] = 0;

	    if($valid)
	    {
	    	$valid = false;
	    	if($food->save())
	    	{
	    		$fid = $food->Food_ID;
	    		if(!empty($status))
	    		{
	    			$status->Food_ID = $fid;
	    		}
	    		
	    		$name->id = $fid;
	    		$junction->Food_ID = $fid;
	    		if($name->save() && $junction->save() && !empty($status)?$status->save() : true)
	    		{
	    			$data['id'] = $fid;
	    			$valid = true;
	    		}
	    		else
	    		{
	    			$food->delete();	
	    		}
	    	}
	    }
	    $data['valid'] = $valid;

	    return $data;
    }
}
