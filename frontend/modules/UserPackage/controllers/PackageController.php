<?php

namespace frontend\modules\UserPackage\controllers;

use yii\web\Controller;
use Yii;
use yii\helpers\ArrayHelper;
use common\models\food\Food;
use common\models\Package\UserPackageDetail;
use common\models\Package\UserPackageSelectionType;
use common\models\Package\UserSubscribeType;
use common\models\Package\UserPackage;
use frontend\modules\UserPackage\controllers\SelectionTypeController;
use frontend\modules\UserPackage\controllers\DetailController;
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
        $userPackage = new UserPackage;
        $userPackageDetail = new UserPackageDetail;
        $userPackageSelectionType = new UserPackageSelectionType;
		$get = Yii::$app->request->get();

		$food = Food::find()->where('food.Food_ID = :id',[':id' => $get['Food']['Food_ID']])->joinWith('foodSelection')->one();

        $subscribeList = ArrayHelper::map(UserSubscribeType::find()->all(),'id','description');

        return $this->render("subcribepackage",['model' =>$food ,'userPackage' => $userPackage,'userPackageDetail' => $userPackageDetail ,'userPackageSelectionType' => $userPackageSelectionType ,'subscribeList' => $subscribeList]);
	}

    public function actionPostitem()
    {
        $post = Yii::$app->request->post();
        $post = SelectionTypeController::detectEmptyQuantity($post);

        if($post == 1)
        {
            return $this->redirect(Yii::$app->request->referrer);
        }

        $userPackage = self::newPackage($post);

        $isValid =$userPackage->validate();

        if($isValid)
        {

            $transaction = Yii::$app->db->beginTransaction();
            try
            {
                //$userPackage->save();
                $userPackageDetail = DetailController::newPackageDetail($post,1);
                var_dump($userPackageDetail->validate());exit;  
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
    public static function newPackage($post)
    {
        $userPackage = new UserPackage();
        $userPackage->load($post);
        $userPackage->uid = Yii::$app->user->identity->id;
        $userPackage->status = 0;
        return $userPackage;
    }
}
