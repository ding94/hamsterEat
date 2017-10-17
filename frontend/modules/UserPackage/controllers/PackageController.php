<?php

namespace frontend\modules\UserPackage\controllers;

use yii\web\Controller;
use Yii;
use common\models\food\Food;
use common\models\Package\UserPackageDetail;
use common\models\Package\UserPackageSelectionType;
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
        $userPackageDetail = new UserPackageDetail;
        $userPackageSelectionType = new UserPackageSelectionType;
		$post = Yii::$app->request->post();
		$food = Food::find()->where('food.Food_ID = :id',[':id' => $post['Food']['Food_ID']])->joinWith('foodselectiontypes')->one();
        return $this->render("subcribepackage",['model' =>$food ,'userPackageDetail' => $userPackageDetail ,'$userPackageSelectionType' => $userPackageSelectionType]);
	}
}
