<?php

namespace backend\modules\Restaurant\controllers;

use Yii;
use yii\web\Controller;
use yii\helpers\ArrayHelper;
use backend\models\RestaurantSearch;
use common\models\Area;
use common\models\User;
use common\models\Rmanager;
use common\models\Restaurant;
use common\models\RestaurantName;
use backend\controllers\CommonController;
use yii\web\NotFoundHttpException;
/**
 * Default controller for the `Restaurant` module
 */
class DefaultController extends CommonController
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

    public function actionRestaurant_approval()
    {
        $searchModel = new RestaurantSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams,2);

        return $this->render('restaurant-approval',['model' => $dataProvider , 'searchModel' => $searchModel]);
    }

    public function actionRmanager_approval()
    {
        $searchModel = new Rmanager();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('rmanager-approval',['model' => $dataProvider , 'searchModel' => $searchModel]);
    }

    public function actionEditRestaurantDetails($rid)
    {
        $model = new RestaurantName();
        $resname['en'] ='en';
        $resname['zh'] ='zh';
        $value = '';
        foreach ($resname as $lan => $name) {
            $data = RestaurantName::find()->where('rid=:rid',[':rid'=>$rid])->andWhere(['=','language',$name])->one();
            if (!empty($data)) {
                $value[$name] = $data['translation'];
            }
            else{
                $value[$name] = '';
            }
        }

        if (Yii::$app->request->post()) {
            foreach ($resname as $lan => $name) {
                if ($model = RestaurantName::find()->where('rid=:rid',[':rid'=>$rid])->andWhere(['=','language',$name])->one()) {
                    $model;
                }else{
                    $model = new RestaurantName();
                }
                $model['rid'] = $rid;
                $model['language'] = $lan;
                if ($name == 'zh') {
                    $model['translation'] = Yii::$app->request->post('RestaurantName')['zh_name'];
                }
                else{
                    $model['translation'] = Yii::$app->request->post('RestaurantName')['en_name'];
                }
                $model->save(false);
            }
            Yii::$app->session->setFlash('success','success');
            return $this->redirect(['/restaurant/default/index']);
        }
        return $this->render('edit-restaurant-details',['value'=>$value,'model'=>$model]);
    }

    public function actionRmanagerOperate($id,$case)
    {
        $rmanager = Rmanager::find()->where('uid=:id',[':id'=>$id])->one();
        switch ($case) {
            case 1:
                $rmanager['Rmanager_Approval'] = 1;
                break;
            case 2:
                $rmanager['Rmanager_Approval'] = 0;
                break;
            default:
                break;
        }

        if($rmanager->validate()){
            $rmanager->save();
            Yii::$app->session->setFlash('success', "Approve completed");
        }
        else{
            Yii::$app->session->setFlash('warning', "Approve fail");
        }

        return $this->redirect(Yii::$app->request->referrer);
    }

    public function actionRestaurantOperate($id,$case)
    {
        //$model = self::findModel($id);
        $model = Restaurant::find()->where('Restaurant_ID = :id',[':id' =>$id])->one();
        switch ($case) {
            case 1:
                $model['approval'] = 1;
                break;
            case 2:
                $model['approval'] = 0;
                break;
            default:
                break;
        }

        if($model->save()){
            Yii::$app->session->setFlash('success', "Approve completed");
        }
        else{
            Yii::$app->session->setFlash('warning', "Approve fail");
        }

        return $this->redirect(Yii::$app->request->referrer);
    }

    protected function findModel($id)
    {
        $model = Rmanager::find()->where('User_Username = :name',[':name' =>$name])->one();
        if ($model !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested restaurant does not exist.');
        }
    }
}
