<?php
namespace backend\controllers;

use Yii;
use backend\controllers\CommonController;
use common\models\PauseOperationTime;
/**
 * Site controller
 */
class PauseServiceController extends CommonController
{
    public function actionPauseTime()
    {
        $searchModel = new PauseOperationTime();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        
        return $this->render('pause-time',['searchModel'=>$searchModel,'dataProvider'=>$dataProvider]);
    }

    public function actionConditionDelete($id)
    {
        $model =  PauseOperationTime::findOne($id);
        if (!empty($model)) {
            $model->delete();
            Yii::$app->session->setFlash('success', 'Saved');
        }
        return $this->redirect(['/pause-service/pause-time']);
    }

    public function actionSetPauseTime()
    {
        $model = new PauseOperationTime();
        $date_format = array();
        $symbol = array();

        //create 2 array, 1 for set date format, 1 for condition
        $date_format['H'] = 'Hour (24 hours format, 00-23)';
        $date_format['N'] = 'Day of week (1 = Monday) ~ 7 = Sunday)';

        $symbol['=='] = 'Equals to';
        $symbol['>'] = 'Bigger than';
        $symbol['>='] = 'Bigger or equals to';
        $symbol['<'] = 'Smaller than';
        $symbol['<='] = 'Smaller or equals to';

        if ($model->load(Yii::$app->request->post())) {
            $model['date_format'] =Yii::$app->request->post('date');
            $model['symbol'] =Yii::$app->request->post('symbol');

            $valid = true;
            $message = 'Something went wrong';
            //detection of date
            switch ($model['date_format']) {
                case 'H':
                    if ($model['time'] < 00 || $model['time'] > 23) {
                        $valid = false;
                        $message = 'Hours cannot smaller than 00 or bigger than 23';
                    }
                    break;

                case 'N':
                    if ($model['time'] < 1 || $model['time'] > 7) {
                        $valid = false;
                        $message = 'Day cannot smaller than 1 or bigger than 7';
                    }
                    break;
                
                default:
                    $valid = false;
                    break;
            }
            //saving part
            if ($valid == true) {
                if ($model->validate()) {
                    $model->save();
                    Yii::$app->session->setFlash('success', 'Saved');
                    return $this->redirect(['/pause-service/pause-time']);
                }
                else{
                    Yii::$app->session->setFlash('warning', 'Failed to save.');
                }
            }
            else{
                Yii::$app->session->setFlash('warning',$message);
            }
        }
        return $this->render('set-pause-time',['model'=>$model,'date_format'=>$date_format,'symbol'=>$symbol]);
    }
}
