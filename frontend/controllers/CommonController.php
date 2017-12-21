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
use common\models\Cart\Cart;
use common\models\Rmanagerlevel;
use yii\web\HttpException;

class CommonController extends Controller
{
    public function beforeAction($action)
    {
        if (!parent::beforeAction($action)) {

             return false;
        }
        if(!Yii::$app->user->isGuest)
        {
            if(Yii::$app->user->identity->status == 1 || Yii::$app->user->identity->status == 2)
            {
                $controller = Yii::$app->controller->id;
                $action = Yii::$app->controller->action->id;
                $permissionName = $controller.'/'.$action; 
               
                if($permissionName   == 'site/validation')
                {
                    return true;
                }

                if($permissionName == 'site/logout' || $permissionName == 'site/resendconfirmlink' || $permissionName == 'site/confirm' || $permissionName == 'site/rmanager'|| $permissionName == 'site/signup' || $permissionName == 'site/deliveryman' || $permissionName == 'site/referral' || $permissionName == 'site/resendconfirmlink-referral')
                {
                    return true;
                }
                
                $this->view->params['notication'] = "";
                $this->view->params['listOfNotic'] = "";
                $this->view->params['countNotic'] = "";
                $this->view->params['number'] = "";
                    
                $this->redirect(['/site/validation']);
                return false;
                    //Yii::$app->end();
            }
        }
       
        return true;
    }
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
            $cart = Cart::find()->where(['uid'=> Yii::$app->user->identity->id])->count();
            $number = $cart == 0 ? "" : $cart;

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
                            Url::to(['/order/my-orders','status'=>'Not Paid']) => 'Not Paid',
                            Url::to(['/order/my-orders','status'=>'Pending']) => 'Pending',
                            Url::to(['/order/my-orders','status'=>'Canceled']) => 'Canceled',
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

    public static function getRestaurantOrdersUrl($rid){
        $data = [
                    Url::to(['/order/restaurant-order-history','rid'=>$rid]) => 'Back',
                    Url::to(['/order/restaurant-orders','rid'=>$rid,'status'=>'Pending']) => 'Pending',
                    Url::to(['/order/restaurant-orders','rid'=>$rid,'status'=>'Canceled']) => 'Canceled',
                    Url::to(['/order/restaurant-orders','rid'=>$rid,'status'=>'Preparing']) => 'Preparing',
                    Url::to(['/order/restaurant-orders','rid'=>$rid,'status'=>'Pick Up In']) => 'Pick Up In',
                    Url::to(['/order/restaurant-orders','rid'=>$rid,'status'=>'On The Way']) => 'On The Way',
                ];

        return $data;
    }

    public static function restaurantPermission($rid)
    {
        $staff = Rmanagerlevel::find()->where('rmanagerlevel.Restaurant_ID = :rid and rmanagerlevel.User_Username = :u and  Rmanager_Approval = 1',[':rid'=>$rid,':u' => Yii::$app->user->identity->username])->joinWith(['manager','restaurant'])->one();
       
        if(empty($staff))
        {
            throw new HttpException('403','Permission Denied!');
        }

        $controller = Yii::$app->controller->id;
        $action = Yii::$app->controller->action->id;
        $permissionName = $controller.'/'.$action;
        $auth = \Yii::$app->authManager;
        
        $verify = $auth->getChildren($staff->RmanagerLevel_Level);
       
        if(empty($verify[$permissionName]))
        {
           throw new HttpException('403','Permission Denied!');
        }

        $data[0] = $staff->restaurant->Restaurant_AreaGroup;
        $data[1] = $staff->restaurant->Restaurant_Area;
        $data[2] = $staff->RmanagerLevel_Level;
        return $data;
    }

    public static function getRestaurantUrl($restArea,$areachosen,$staff,$rid)
    {
        //$restArea = $staff->restaurant->Restaurant_AreaGroup;
        //$areachosen = $staff->restaurant->Restaurant_Area;
        $data = [];
        $data = [
                    Url::to(['/order/restaurant-orders','rid'=>$rid]) => 'Restaurant Orders',
                    Url::to(['/order/restaurant-order-history','rid'=>$rid]) => 'Restaurant Orders History',
                ];
        switch ($staff) {
            case 'Owner':
                $data[ Url::to(['/Restaurant/profit/index','rid'=>$rid])] = 'Views Earnings';
                $data[Url::to(['/Restaurant/default/edit-restaurant-details','rid'=>$rid,'restArea' => $restArea,'areachosen' => $areachosen])] = 'Edit Details';
                $data[Url::to(['/Restaurant/default/manage-restaurant-staff','rid'=>$rid])] = 'Manage Staffs';
                $data[Url::to(['/food/menu','rid'=>$rid,'page'=>'menu'])] = 'Manage Menu';
                break;
            case 'Manager':
                $data[Url::to(['/Restaurant/default/edit-restaurant-details','rid'=>$rid,'restArea' => $restArea,'areachosen' => $areachosen])] = 'Edit Details';
                $data[Url::to(['/Restaurant/default/manage-restaurant-staff','rid'=>$rid])] = 'Manage Staffs';
                 $data[Url::to(['/food/menu','rid'=>$rid,'page'=>'menu'])] = 'Manage Menu';
                break;
            default:
                # code...
                break;
        }
    
        return $data;
    }

    /*
    * remove dimension array to single array
    */
    public static function removeNestedArray($nested,$final= array())
    {
        foreach($nested as $single)
        {
            if(is_array($single))
            {
                $final = self::removeNestedArray($single,$final);
            }
            else
            {
                $final[] = $single;
            }
        }
        return array_filter($final);
    }
}