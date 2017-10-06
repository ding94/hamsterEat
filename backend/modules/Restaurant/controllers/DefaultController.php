<?php

namespace backend\modules\Restaurant\controllers;

use Yii;
use yii\web\Controller;
use backend\models\RestaurantSearch;
use common\models\Area;
use common\models\User;
/**
 * Default controller for the `Restaurant` module
 */
class DefaultController extends Controller
{
    /**
     * Renders the index view for the module
     * @return string
     */
    public function actionIndex()
    {
    	$searchModel = new RestaurantSearch();
    	$dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        $area = new Area;
        $stateList = $area->allstate;

		return $this->render('index',['model' => $dataProvider , 'searchModel' => $searchModel,'stateList' => $stateList]);
    }

    public function actionManager($name)
    {
        $model = User::find()->where('username = :name',[':name' => $name])->one();
        return $this->renderPartial('manager',['model' => $model]);
    }
}
