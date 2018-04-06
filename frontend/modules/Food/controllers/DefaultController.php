<?php

namespace frontend\modules\Food\controllers;

use Yii;
use yii\web\Controller;
use yii\helpers\ArrayHelper;
use yii\data\Pagination;
use frontend\controllers\{CommonController,ValidController};
use common\models\Restaurant;
use frontend\modules\offer\controllers\PromotionController;
use common\models\Rating\Foodrating;
use common\models\Cart\{Cart,CartSelection};
use common\models\food\{Foodtypejunction,Foodtype,Food,FoodName,Foodstatus,Foodselectiontype};

/**
 * Default controller for the `food` module
 */
class DefaultController extends CommonController
{
    public function actionMenu($rid)
    {
    	$linkData = CommonController::restaurantPermission($rid);
        $link = CommonController::getRestaurantUrl($linkData,$rid);
        $query = Food::find()->where('Restaurant_ID=:rid',[':rid'=>$rid]);
        $countQuery = clone $query;
        $pagination = new Pagination(['totalCount' => $countQuery->count()]);
   
        $menu = $query->offset($pagination->offset)
        ->limit($pagination->limit)
        ->all();
      
        $restaurant = Restaurant::find()->where('Restaurant_ID = :id', [':id'=>$rid])->one();
        
        return $this->render('menu',['menu'=>$menu, 'restaurant'=>$restaurant, 'pagination'=>$pagination,'link'=>$link]);
    }

    public function actionViewComments($id)
    {
        $comments = Foodrating::find()->where('Food_ID = :id', [':id'=>$id])->all();

        $food= Food::find()->where('Food_ID=:id',[':id'=>$id])->one();
        $foodname=$food['cookiename'];
        
        return $this->render('comment', ['fid'=>$id, 'comments'=>$comments,'foodname'=>$foodname]);
    }

    public function actionDetail($id,$rid)
    {
        if(!Yii::$app->request->isAjax){
            return $this->redirect(Yii::$app->request->referrer);
        }
        $valid = ValidController::RestaurantValid($rid);

        if($valid)
        {
            Yii::$app->session->setFlash('error', Yii::t('food','This restaurant was not valid now.'));
            return $this->redirect(Yii::$app->request->referrer);
        }

        $valid = ValidController::FoodValid($id);
        if (!$valid) {
            Yii::$app->session->setFlash('error', Yii::t('food','This food was not valid now.'));
            return $this->redirect(Yii::$app->request->referrer);
        }

        $fooddata = Food::find()->where('Food_ID = :id' ,[':id' => $id])->one();
        $foodlimit = Foodstatus::find()->where('Food_ID = :id' ,[':id' => $id])->one();

        if(empty($fooddata))
        {
            Yii::$app->session->setFlash('error', Yii::t('food','Something Went Wrong. Please Try Again Later!'));
            return $this->redirect(Yii::$app->request->referrer);
        }
        
        $avaialbePromotion = PromotionController::getPromotion();
       
        if($avaialbePromotion)
        {
            $fooddata->promotion_enable = 2;
            $price =  PromotionController::getPromotioinPrice($fooddata->Price,$fooddata->Food_ID,1);
            if(is_array($price))
            {
                $fooddata->promotion_price = $price['price'];
                $fooddata->promotion_text = $price['message'];
                $fooddata->promotion_enable = 1;
                $fooddata->promotion_left = $price['left'];
            }
        }
       
       
        $foodtype = Foodselectiontype::find()->where('Food_ID = :id',[':id' => $id])->orderBy(['ID' => SORT_ASC])->all();
      
        $cartSelection = new CartSelection;
        $cart = new Cart;
        
        $comments = Foodrating::find()->where('Food_ID = :fid', [':fid'=>$id])->orderBy(['created_at' => SORT_DESC])->all();

        return $this->renderAjax('detail',['fooddata' => $fooddata,'foodlimit' => $foodlimit,'foodtype' => $foodtype, 'cart'=>$cart ,'cartSelection' => $cartSelection, 'comments'=>$comments]);
    }

    public function actionCreateEditFood($rid,$id=0)
    {
        CommonController::restaurantPermission($rid);
        $food = Food::find()->where('food.Food_ID = :id',[':id'=>$id])->joinWith(['foodStatus','transName'])->one();
        if(empty($food))
        {
        	$food = new Food;
    		$food->Restaurant_ID =$rid;
    		$name = new FoodName;
    		$junction = new Foodtypejunction;
            $status = new Foodstatus;
            $newData = true;
        }
        else
        {
        	$name = $food->transName;
            $status = $food->foodStatus;
        	$junction = Foodtypejunction::find()->where('Food_ID = :fid',[':fid'=>$id])->one();
            $newData = false;
        	if(empty($junction))
        	{
        		$junction = new Foodtypejunction;
        	}
        }
        $new = $food->isNewRecord ? 0 :1;
    	$link = CommonController::getRestaurantEditUrl($id,$rid,$new);

    	$array['type'] = ArrayHelper::map(Foodtype::find()->all(),'ID','Type_Desc');
    	$array['status'] = [10=>10,20=>20,30=>30,40=>40,50=>50];

    	if($food->load(Yii::$app->request->post()) && $name->load(Yii::$app->request->post()) && $status->load(Yii::$app->request->post()))
    	{
            $restaurant = Restaurant::findOne($rid);
    		$data = $this->save($food,$name,$junction,$status,$restaurant);

    		if($data['valid'])
    		{
    			Yii::$app->session->setFlash('success',Yii::t('cart','Success!'));
				
    			if($newData)
    			{
                    
    				return $this->redirect(['/Food/selection/create-edit','id'=>$data['id'],'rid'=>$rid,'status'=>1]);
    			}
    			else
    			{
    				return $this->redirect(['create-edit-food','id'=>$data['id'],'rid'=>$rid]);
    			}
	    		
    		}
    		$junction->scenario ="default";
    		
    		Yii::$app->session->setFlash('error',Yii::t('cart','Something Went Wrong!'));
    	}

    	return $this->render('crfood',['food'=>$food,'name'=>$name,'array'=>$array,'junction'=>$junction,'status'=>$status,'link'=>$link]);
    	
    }

    public function actionDelete($id)
    {
    	$food = Food::find()->where("food.Food_ID = :fid and Status =0",[':fid'=>$id])->joinWith(['restaurant','foodStatus'])->one();
    	$rid = $food['restaurant']['Restaurant_ID'];
    	if(!empty($food) && $food['restaurant']['Restaurant_Manager'] == Yii::$app->user->identity->username)
    	{
    		$status = $food->foodStatus;
    		
    		$status->Status = -1;
            if ($status->save()) {
                Yii::$app->session->setFlash('success',Yii::t('food','Item Deleted.'));
                return $this->redirect(['menu','rid'=>$rid]);
        	}
        }
    	Yii::$app->session->setFlash('error',Yii::t('food','Somethign Went Wrong'));
    	return $this->redirect(Yii::$app->request->referrer);	
    }

    protected static function save($food,$name,$junction,$status,$restaurant)
    {
    	$arrayJ = TypeAndStatusController::detectJunction($junction);
    	if($arrayJ['message'] == 0)
    	{
    		return false;
    	}

    	$junction = $arrayJ['value'];

    	$array = TypeAndStatusController::createStatus($food,$status);

    	$food = $array['food'];
        $status = $array['status'];
        
        //var_dump($status);exit;
    	$name->language = "en";
    	
        if ($restaurant['Restaurant_Status'] == 1) {
            $restaurant['Restaurant_Status'] = 2;
        }

	    $valid = $junction->validate() && $food->validate() && $name->validate() && $status->validate() && $restaurant->validate();
	   	$data['id'] = 0;

	    if($valid)
	    {
	    	$valid = false;
	    	if($food->save())
	    	{
	    		$fid = $food->Food_ID;

	    		$status->Food_ID = $fid;
	    		$name->id = $fid;
	    		$junction->Food_ID = $fid;

	    		if($name->save() && $junction->save() && $status->save() && $restaurant->save())
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
