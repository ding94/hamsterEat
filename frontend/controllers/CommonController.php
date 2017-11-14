<?php 
namespace frontend\controllers;

use yii\helpers\Html;
use yii\web\Controller;
use Yii;
use yii\helpers\Url;
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

	/*
    * url link for dropdown in mobile site
    * 1=>user profile link
    * 2=>user balance link
    * 3=>my orders link
    * 4=>ticket link
    * 5=>deliveryman link
    */
    public static function createUrlLink($type)
    {
    	switch ($type) {
    		case 1:
    			$data = [	
    						Url::to(['/user/userdetails']) => 'Edit User Details',
    						Url::to(['/user/changepassword']) => 'Change Password'
    					];
    			break;
    		case 2:
    			$data = [	
    						Url::to(['/user/userbalance']) => 'Balance History',
    						Url::to(['/topup/index']) => 'Top Up',
    						Url::to(['/withdraw/index']) => 'Withdraw',
    					];
    			break;
    		case 3:
    			$data = [
                            Url::to(['/order/my-orders']) => 'All',
                            Url::to(['/order/my-orders','status'=>'Pending']) => 'Pending',
                            Url::to(['/order/my-orders','status'=>'Preparing']) => 'Preparing',
                            Url::to(['/order/my-orders','status'=>'Pick Up In']) => 'Pick Up In',
                            Url::to(['/order/my-orders','status'=>'On The Way']) => 'On The Way',
                            Url::to(['/order/my-orders','status'=>'Completed']) => 'Completed',
    					];
    			break;
    		case 4:
    			$data = [
    						Url::to(['/ticket/index']) => 'All',
    						Url::to(['/ticket/submit-ticket']) => 'Submit Ticket',
    						Url::to(['/ticket/completed']) => 'Completed Ticket',
    				   ];
    			break;
            case 5:
                $data = [
                            Url::to(['/order/deliveryman-orders']) => 'Deliveryman Orders',
                            Url::to(['/order/deliveryman-order-history']) => 'Deliveryman Orders History',
                            Url::to(['/Delivery/daily-sign-in/delivery-location']) => 'Delivery Location',
                        ];
                break;
    		default:
    			$data =[];
    			break;	
    	}
       	
        return $data;
    }

    public static function getRestaurantUrl($rid,$restArea,$areachosen,$postcodechosen,$staff)
    {
		if($staff = "Owner")
    	{
    		$data = [
	    				Url::to(['/Restaurant/default/show-monthly-earnings','rid'=>$rid]) => 'Views Earnings',
	    				Url::to(['/Restaurant/default/edit-restaurant-details','rid'=>$rid,'restArea' => $restArea,'areachosen' => $areachosen,'postcodechosen' => $postcodechosen]) => 'Edit Details',
	    				Url::to(['/Restaurant/default/manage-restaurant-staff','rid'=>$rid]) => 'Manage Staffs',
	    				Url::to(['/order/restaurant-orders','rid'=>$rid]) => 'Restaurant Orders',
	    				Url::to(['/order/restaurant-order-history','rid'=>$rid]) => 'Restaurant Orders History',
	    				Url::to(['/food/menu','rid'=>$rid,'page'=>'menu']) => 'Manage Menu',
    				];
    	}
    	elseif($staff ="Manager")
    	{
    		$data = [
	    				Url::to(['/Restaurant/default/edit-restaurant-details','rid'=>$rid,'restArea' => $restArea,'areachosen' => $areachosen,'postcodechosen' => $postcodechosen]) => 'Edit Details',
	    				Url::to(['/Restaurant/default/manage-restaurant-staff','rid'=>$rid]) => 'Manage Staffs',
	    				Url::to(['/order/restaurant-orders','rid'=>$rid]) => 'Restaurant Orders',
	    				Url::to(['/order/restaurant-order-history','rid'=>$rid]) => 'Restaurant Orders History',
	    				Url::to(['/food/menu','rid'=>$rid,'page'=>'menu']) => 'Manage Menu',
    				];
    	}
    	else
    	{
    		$data = [
	        			Url::to(['/order/restaurant-orders','rid'=>$rid]) => 'Restaurant Orders',
	    				Url::to(['/order/restaurant-order-history','rid'=>$rid]) => 'Restaurant Orders History',
    				];
    	}
    	return $data;
    }
}