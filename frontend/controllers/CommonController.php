<?php 
namespace frontend\controllers;

use yii\helpers\Html;
use yii\web\Controller;
use Yii;
use yii\helpers\ArrayHelper;
use yii\helpers\Json;
use common\models\Notification;
use common\models\NotificationSetting;

class CommonController extends Controller
{
	public function init()
	{
		$data = "";
		$listOfNotic = "";
		$count = "";
		if(!Yii::$app->user->isGuest)
		{
			$result = [];
			$listOfNotic = ArrayHelper::index(NotificationSetting::find()->asArray()->all(), 'id');
			$notication = Notification::find()->where('uid = :uid and view = :v',[':uid' => Yii::$app->user->identity->id,':v'=>0])->limit(10)->asArray()->orderBy(['created_at'=>SORT_DESC])->all();
			$count = count($notication);
			$count = $count ==0 ? "" : " (".$count.")";
			foreach($notication as $single)
			{
				$result[$single['type']][] = $single;
				//$result[$single['type']]['url'] = $this->urlLink($single['type'],$single['rid']);
			}
			
			$data = $result;
		}
		$this->view->params['notication'] = $data;
		$this->view->params['listOfNotic'] = $listOfNotic;
		$this->view->params['countNotic'] = $count;
	}
}