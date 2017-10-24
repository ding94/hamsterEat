<?php

namespace frontend\modules\UserPackage\controllers;

use yii\web\Controller;
use Yii;
use yii\helpers\ArrayHelper;
use yii\helpers\Json;
use common\models\food\Food;
use common\models\Package\UserPackageDetail;
use common\models\Package\UserPackageSelectionType;
use common\models\Package\UserPackage;
use frontend\modules\UserPackage\controllers\SelectionTypeController;
use frontend\modules\UserPackage\controllers\DetailController;
use frontend\modules\UserPackage\controllers\DeliveryController;
use frontend\controllers\PaymentController;
/**
 * Default controller for the `UserPackage` module
 */
class PackageController extends Controller
{
    /**
     * Renders the index view for the module
     * @return string
     */
    public function actionIndex()
    {
        return $this->render('index');
    }

    public function actionSubscribepackage()
	{
		if (Yii::$app->user->isGuest) 
        {
        	Yii::$app->set->session("success",'Please Log In before process the order');
            $this->redirect(['site/login']);
        }

		$get = Yii::$app->request->get();

        $minMax = SelectionTypeController::detectMinMaxSelecttion($get['Orderitemselection']['FoodType_ID'],$get['Food']['Food_ID']);
       
        $validate = $minMax && self::detectEmptyString($get['Orderitem']['OrderItem_Quantity'],1) &&  self::detectEmptyString($get['dateTime'],2);
       
        if(!$validate)
        {
            return $this->redirect(Yii::$app->request->referrer);
        }
       
        $dateTime = $get['dateTime'];

        $fid = $get['Food']['Food_ID'];
        $itemSelection = self::removeNestedArray($get['Orderitemselection']['FoodType_ID']);

        $userPackage = new UserPackage;
        $selectionType = new UserPackageSelectionType;
        $food = Food::find()->where('food.Food_ID = :id',[':id' =>$fid])->joinWith(['foodSelection'])->asArray()->one();
        $food['foodSelection'] = DetailController::filterItem($food['foodSelection'],$itemSelection);

        $packageDetail = DetailController::newPackageDetail($fid,$get['Orderitem']['OrderItem_Quantity'],$food);
       
        return $this->render('subcribepackage',['food' => $food,'userPackage' => $userPackage , 'packageDetail' => $packageDetail ,'selectionType' => $selectionType ,'dateTime' => $dateTime]);
	}

    public function actionPostitem()
    {
        $post = Yii::$app->request->post();

        $UserPackageDetail['UserPackageDetail'] = Json::decode($post['packageDetail']);

        $userPackage = self::newPackage();
      
        $isValid =$userPackage->validate();
       
        $selectedDateTime = explode(",",$post['dateTime']);
        
        if($isValid)
        {
            $transaction = Yii::$app->db->beginTransaction();
            try
            {
                $userPackage->save();
                $pid = $userPackage->id;

                $selectionType = SelectionTypeController::newSelection($post['UserPackageSelectionType'],$pid);
                $detail = DetailController::createDetail($UserPackageDetail,$pid);
                $selectedDate = DeliveryController::createDeliveryDate($selectedDateTime,$pid);
                //$payment = PaymentController::subScribePayment($UserPackageDetail['UserPackageDetail']['totalPrice'],$pid);
                
                $isValid =  $selectionType&& $detail && $selectedDate ;
                
                if($isValid)
                {
                    $transaction->commit();
                    return $this->redirect(['subscribe/confirm-subscribe', 'id' => $pid]);
                }
            }
            catch(Exception $e)
            {
                $transaction->rollBack();
            }
        }

        Yii::$app->session->setFlash('warning', "Subscribe Fail");
        return $this->redirect(Yii::$app->request->referrer);
    }

    /*
    * create new user package
    * status default 0 pending
    */
    public static function newPackage()
    {
        $userPackage = new UserPackage();
        $userPackage->uid = Yii::$app->user->identity->id;
        $userPackage->status = 0;
        return $userPackage;
    }

    public static function detectEmptyString($data,$type)
    {
        if(empty($data))
        {
            switch ($type) {
                case 1:
                    Yii::$app->session->setFlash('danger',"Please Select At Least One Quantity");
                    break;
                case 2:
                    Yii::$app->session->setFlash('danger',"Please Chose A Date");
                    break;
                default:
                    # code...
                    break;
            }
            return false;
        }
        return true;
    }

    /*
    * remove dimension array to single array
    */
    public static function removeNestedArray($nested,$final= array())
    {
        foreach($nested as $single)
        {
            if(is_array($single))
            {
                $final = self::removeNestedArray($single,$final);
            }
            else
            {
                $final[] = $single;
            }
        }
        return array_filter($final);
    }
}


