<?php

namespace backend\modules\Restaurant\controllers;

use Yii;
use yii\web\Controller;
use backend\models\RestaurantSearch;
use common\models\Area;
use common\models\User;
use common\models\Rmanager;
use yii\web\NotFoundHttpException;
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
        $model = User::find()->where('username = :name',[':name' => $name])->joinWith('manager')->one();

        return $this->renderPartial('_manager',['model' => $model]);
    }

    public function actionActive($name)
    {
        $model = self::findModel($name);
        $model->Rmanager_Approval = 1;
        $model->Rmanager_DateTimeApproved = time();
        if($model->save())
        {
            Yii::$app->session->setFlash('success', "Approve completed");
        }
        else
        {
            Yii::$app->session->setFlash('warning', "Approve fail");
        }

        return $this->redirect(Yii::$app->request->referrer);
    }

    public function actionDeactive($name)
    {
        $model = self::findModel($name);
        $model->Rmanager_Approval = 0;
        if($model->save())
        {
            Yii::$app->session->setFlash('success', "Deapprove completed");
        }
        else
        {
            Yii::$app->session->setFlash('warning', "Deapprove fail");
        }
        return $this->redirect(Yii::$app->request->referrer);
    }

    protected function findModel($name)
    {
        $model = Rmanager::find()->where('User_username = :name',[':name' =>$name])->one();
        if ($model !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested restaurant does not exist.');
        }
    }
}
