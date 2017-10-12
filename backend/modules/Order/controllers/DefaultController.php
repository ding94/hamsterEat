<?php

namespace backend\modules\Order\controllers;

use Yii;
use yii\web\Controller;
use backend\models\OrderSearch;

/**
 * Default controller for the `Order` module
 */
class DefaultController extends Controller
{
    /**
     * Renders the index view for the module
     * @return string
     */
    public function actionIndex()
    {
        $searchModel = new OrderSearch();
    	$dataProvider = $searchModel->search(Yii::$app->request->queryParams);

		return $this->render('index',['model' => $dataProvider , 'searchModel' => $searchModel]);
    }
}
