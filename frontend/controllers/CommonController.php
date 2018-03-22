<?php 
namespace frontend\controllers;

use yii\helpers\Html;
use yii\web\Controller;
use yii\web\Cookie;
use Yii;
use yii\helpers\Url;
use yii\helpers\ArrayHelper;
use yii\helpers\Json;
use common\models\notic\{Notification,NotificationType};
use common\models\Cart\Cart;
use common\models\Rmanager;
use common\models\Rmanagerlevel;
use common\models\RestaurantName;
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
                    
                $this->redirect(['/site/validation']);
                return false;
                    //Yii::$app->end();
            }
        }
       
        return true;
    }

    public function init()
    {
        $number ="";
        if(!Yii::$app->user->isGuest)
        {
            $result = array();
            Yii::$app->params['listOfNotic'] = ArrayHelper::index(NotificationType::find()->asArray()->all(), 'id');
           
            $query = Notification::find()->where('uid = :uid and view = :v',[':uid' => Yii::$app->user->identity->id,':v'=>0])->orderBy(['created_at'=>SORT_DESC]);
            $count = $query->count();
            Yii::$app->params['countNotic'] = $count == 0 ? "" : " <span class='badge'>".$count."</span>";
            $notication = $query->limit(20)->all();
            
            foreach($notication as $single)
            {
                $result[$single['type']][] = $single;
                //$result[$single['type']]['url'] = $this->urlLink($single['type'],$single['rid']);
            }
           
            Yii::$app->params['notication'] = $result;
           

            //Total Cart item
          
            $cart = Cart::find()->where(['uid'=> Yii::$app->user->identity->id])->count();
            Yii::$app->params['countCart'] = $cart == 0 ? "" : $cart;

        }
       
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
                            Url::to(['/user/userdetails']) => Yii::t('common','Edit User Details'),
                            Url::to(['/user/changepassword']) => Yii::t('user','Change Password'),
                        ];
                break;
            case 2:
                $data = [   
                            Url::to(['/user/userbalance']) => Yii::t('common','Balance history'),
                            Url::to(['/topup/index']) => Yii::t('common','Top Up'),
                            Url::to(['/withdraw/index']) => Yii::t('common','Withdraw'),
                        ];
                break;
            case 3:
                $data = [
                            Url::to(['/order/my-orders']) => Yii::t('common','All'),
                            Url::to(['/order/my-orders','status'=>1]) => Yii::t('order','Not Paid'),
                            Url::to(['/order/my-orders','status'=>2]) => Yii::t('order','Pending'),
                            Url::to(['/order/my-orders','status'=>8]) => Yii::t('order','Canceled'),
                            Url::to(['/order/my-orders','status'=>3]) => Yii::t('order','Preparing'),
                            Url::to(['/order/my-orders','status'=>11]) => Yii::t('order','Pick Up in Process'),
                            Url::to(['/order/my-orders','status'=>5]) => Yii::t('order','On The Way'),
                            Url::to(['/order/my-orders','status'=>6]) => Yii::t('order','Completed'),
                        ];
                break;
            case 4:
                $data = [
                            Url::to(['/ticket/index']) => Yii::t('common','All'),
                            Url::to(['/ticket/submit-ticket']) => Yii::t('ticket','Submit Ticket'),
                            Url::to(['/ticket/completed']) => Yii::t('ticket','Completed Ticket'),
                       ];
                break;
            case 5:
                $data = [
                            Url::to(['/Delivery/deliveryorder/order']) => Yii::t('m-delivery','Deliveryman Orders'),
                            Url::to(['/Delivery/deliveryorder/pickup']) => Yii::t('m-delivery','Pick Up Orders'),
                            Url::to(['/Delivery/deliveryorder/complete']) => Yii::t('m-delivery','Complete Orders'),
                            Url::to(['/Delivery/deliveryorder/history']) => Yii::t('m-delivery','Deliveryman Orders History'),
                            Url::to(['/Delivery/daily-sign-in/delivery-location']) => Yii::t('m-delivery','Delivery Location'),
                        ];
                break;
            case 6:
                $data = [
                           Url::to(['/notification/notic/index']) => Yii::t('notification','All Notification'),  
                           Url::to(['/notification/notic/index','type'=>1]) => Yii::t('notification','Unread'),  
                           Url::to(['/notification/notic/index','type'=>2]) => Yii::t('notification','Read'),
                           Url::to(['/notification/setting/index'])=>Yii::t('notification','Setting'),
                       ];
                break;
            default:
                $data =[];
                break;  
        }
        
        return $data;
    }

    public static function getLanguage($case='')
    {
        if (!empty($case)) {
            $cookies = Yii::$app->response->cookies;
            if(!is_null($cookies['language'])){
                $cookies->remove('language');
            }
        }
        else{
            $case='en';
        }

        $cookie =  new Cookie([
            'name' => 'language',
            'value' => $case,
            'expire' => time() + 86400 * 365,
        ]);
        \Yii::$app->getResponse()->getCookies()->add($cookie);
        return $case;
    }

    public static function getRestaurantOrdersUrl($rid){
        $data = [
                    Url::to(['/order/restaurant-order-history','rid'=>$rid]) => 'Back',
                    Url::to(['/Restaurant/restaurantorder/index','rid'=>$rid]) => 'All',
                    Url::to(['/Restaurant/restaurantorder/index','rid'=>$rid,'status'=>2]) => 'Pending',
                    Url::to(['/Restaurant/restaurantorder/index','rid'=>$rid,'status'=>3]) => 'Preparing',
                    Url::to(['/Restaurant/restaurantorder/index','rid'=>$rid,'status'=>4]) => 'Ready for Pickup',
                ];

        return $data;
    }

    public static function getRestaurantEditUrl($id,$rid,$type)
    {
        $data = [];
        $data = [
                  Url::to(['Food/default/menu','rid'=>$rid]) => 'Back',
                ];
        if($type == 1)
        {
            $data[Url::to(['/Food/default/create-edit-food','id'=>$id,'rid'=>$rid])] = 'Edit Food';
            $data[ Url::to(['/Food/selection/create-edit','id'=>$id,'rid'=>$rid])] = 'Edit Food Selection';
            $data[Url::to(['/Food/name/change','id'=>$id,'rid'=>$rid])]="Edit Name";
        }
      
        return $data;
    }

    public static function restaurantPermission($rid)
    {
        $staff = Rmanagerlevel::find()->where('rmanagerlevel.Restaurant_ID = :rid and rmanagerlevel.User_Username = :u and  Rmanager_Approval = 1',[':rid'=>$rid,':u' => Yii::$app->user->identity->username])->joinWith(['manager','restaurant'])->one();
       ;

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

        return $staff->RmanagerLevel_Level;
    }

    public static function rmanagerApproval() 
    {
        $rmanager = Rmanager::find()->where('uid=:id',[':id'=>Yii::$app->user->identity->id])->one();
        if (!empty($rmanager)) {
            if ($rmanager['Rmanager_Approval'] == 1) {
                return $rmanager;
            }
        }
        throw new HttpException('403','Permission Denied!');
    }

    public static function getRestaurantName($rid)
    {
        //cookies->reponse was cookies that ready to saved as cookies, but not available in current cookies
        //cookies->request was cookies that available in current cookies
        //$cookies = Yii::$app->response->cookies;
        $cookies = Yii::$app->request->cookies;
        if (empty($cookies['language'])) {
            $cookies = self::getLanguage();
        }
        else{
            $cookies = Yii::$app->request->cookies['language']->value;
        }
        $resname = ArrayHelper::map(RestaurantName::find()->where('rid=:rid',[':rid'=>$rid])->all(),'language','translation');
        if (!empty($resname[$cookies])) {
            $resname = $resname[$cookies];
        }
        else{
            $resname = $resname['en'];
        }

        return $resname;
    }

    public static function getRestaurantUrl($staff,$rid)
    {
        //$restArea = $staff->restaurant->Restaurant_AreaGroup;
        //$areachosen = $staff->restaurant->Restaurant_Area;
        $data = [];
        $data = [
                    Url::to(['/Restaurant/restaurantorder/index','rid'=>$rid]) => 'Restaurant Orders',
                    Url::to(['/Restaurant/restaurantorder/history','rid'=>$rid]) => 'Restaurant Orders History',
                ];
        switch ($staff) {
            case 'Owner':
                $data[Url::to(['/Restaurant/profit/index','rid'=>$rid])] = 'Views Earnings';
                $data[Url::to(['/Restaurant/statistics/index','rid'=>$rid])] = 'View Statistics';
                $data[Url::to(['/Restaurant/default/edit-restaurant-details','rid'=>$rid])] = 'Edit Details';
                $data[Url::to(['/Restaurant/default/manage-restaurant-staff','rid'=>$rid])] = 'Manage Staffs';
                $data[Url::to(['/Food/default/menu','rid'=>$rid])] = 'Manage Menu';
                break;
            case 'Manager':
                $data[Url::to(['/Restaurant/default/edit-restaurant-details','rid'=>$rid])] = 'Edit Details';
                $data[Url::to(['/Restaurant/default/manage-restaurant-staff','rid'=>$rid])] = 'Manage Staffs';
                $data[Url::to(['/Food/default/menu','rid'=>$rid])] = 'Manage Menu';
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