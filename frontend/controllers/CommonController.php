<?php 
namespace frontend\controllers;

use yii\helpers\Html;
use yii\web\Controller;
use Yii;
use yii\helpers\ArrayHelper;
use yii\helpers\Json;
use common\models\Notification;
use common\models\NotificationSetting;
use common\models\Orders;
use common\models\Orderitem;
class CommonController extends Controller
{
	public function init()
	{
		$data = "";
		$listOfNotic = "";
		$count = "";
		$number ="";
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


			//Total Cart item
			$totalcart="";
			$cart = orders::find()->where('User_Username = :uname',[':uname'=>Yii::$app->user->identity->username])->andwhere('Orders_Status = :status',[':status'=>'Not Placed'])->one();
	        $did = $cart['Delivery_ID'];
			$cartitems = Orderitem::find()->where('Delivery_ID = :did',[':did'=>$did])->all();
			 foreach($cartitems as $totalitem)
	        {
	            $totalcart=($totalcart+$totalitem['OrderItem_Quantity']);
	        }
	        $number=$totalcart;
	       

		}
		$this->view->params['notication'] = $data;
		$this->view->params['listOfNotic'] = $listOfNotic;
		$this->view->params['countNotic'] = $count;

		$this->view->params['number'] = $number;
	}
}