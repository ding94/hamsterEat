<?php

namespace frontend\modules\notification\controllers;

use Yii;
use yii\web\Controller;
use yii\data\Pagination;
use yii\filters\AccessControl;
use common\models\notic\Notification;
use frontend\controllers\CommonController;

/**
 * Default controller for the `notification` module
 */
class NoticController extends Controller
{

	public function behaviors()
    {
         return [
             'access' => [
                 'class' => AccessControl::className(),
                 //'only' => ['logout', 'signup','index'],
                 'rules' => [
                    [
                        'actions' => ['index','turnoff'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                 ]
             ]
        ];
    }
    /**
     * Renders the index view for the module
     * @return string
     */
    public function actionIndex($type=0)
    {
		$title = Yii::t('notification',"All Notification");
		self::turnOffNotification();
		$link = CommonController::createUrlLink(6);
		$query = Notification::find()->where('uid = :uid',[':uid' =>Yii::$app->user->identity->id ]);

		switch ($type) {
			case 1:
				$query->andWhere('view = 0');
				$title = Yii::t('notification',"Unread");
				break;
			case 2:
				$query->andWhere('view = 1');
				$title = Yii::t('notification',"Read");
				break;
			default:
				# code...
				break;
		}
	    $count = $query->count();
      	
        $pagination = new Pagination(['totalCount' => $count]);		    
        $notification = $query->offset($pagination->offset)->limit($pagination->limit)->orderBy(['updated_at'=> SORT_DESC])->all();
      
		return $this->render('index',['notification'=>$notification,'pages' => $pagination,'link'=>$link,'title'=>$title]);


    }

    public function actionTurnoff()
	{
		Notification::updateAll(['view' => 1],'uid = :uid',[':uid'=>Yii::$app->user->identity->id]);
		return $this->redirect(Yii::$app->request->referrer); 
	}

	/*
	* passing data base on tid
	* tid=> notification type id
	* sid=> status id
	* id => can be delivery id or order it
	* uid => user id 
	*/
    public static function centerNotic($tid,$sid,$id,$uid)
	{
		switch ($tid) {
			case 1:
				OrderController::createUserNotic($tid,$sid,$id,$uid);
				break;
			case 2:
				OrderController::createUserNotic($tid,$sid,$id,$uid);
			default:
				# code...
				break;
		}
	}

	/*
	* update all params notification to be readed
	*/
	public static function turnOffNotification()
	{
		$data = [];
		if(!empty(Yii::$app->params['notication']))
		{
			foreach(Yii::$app->params['notication'] as $notic)
			{
				
				$data[]= array_column($notic, 'id');
			}

			$data = PackageController::removeNestedArray($data);
			
			foreach($data as $id)
			{
				$model = Notification::findOne($id);
				$model->view = 1;
				$model->save();
			}
		}
	}
}
