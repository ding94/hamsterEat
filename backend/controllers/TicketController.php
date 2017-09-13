<?php
namespace backend\controllers;

use Yii;
use yii\web\Controller;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use common\models\Ticket;
use common\models\Replies;
use common\models\Upload;
/**
 * Site controller
 */
class TicketController extends Controller
{

	public function actionIndex()
    {
    	$searchModel = new Ticket();
       	$dataProvider = $searchModel->search(Yii::$app->request->queryParams,1);

        return $this->render('index',['model'=>$dataProvider, 'searchModel'=>$searchModel]);
    }

    public function actionReply($id)
    {
    	$model = Ticket::find()->where('Ticket_ID = :id',[':id' => $id])->one();
    	$reply = new Replies;
    	$upload = new Upload;
    	$upload->scenario = 'reply';
    	return $this->render('staffreply',['model'=>$model,'reply'=>$reply,'upload'=>$upload]);
    }

}
