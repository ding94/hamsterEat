<?php 
namespace frontend\controllers;

use yii\helpers\Html;
use yii\web\Controller;
use Yii;
use yii\helpers\ArrayHelper;
use common\models\Notification;
use common\models\NotificationSetting;

class CommonController extends Controller
{
	public function init()
	{
		$data = [];
		if(!Yii::$app->user->isGuest)
		{
			$result = [];
			$listOfNotic = ArrayHelper::map(NotificationSetting::find()->all(),'id','description');
			$notication = Notification::find()->where('uid = :uid and view = :v',[':uid' => Yii::$app->user->identity->id,':v'=>0])->asArray()->orderBy(['created_at'=>SORT_DESC])->all();
			$count = count($notication);
			foreach($notication as $single)
			{
				
				$result[$single['type']][] = $single;
			}
			$data = $result;
			$this->view->params['notication'] = $data;
			$this->view->params['listOfNotic'] = $listOfNotic;
			$this->view->params['countNotic'] = $count;
		}
	}
}