<?php
namespace frontend\modules\Restaurant\controllers;

use yii;
use yii\web\Cookie;
use yii\web\Controller;
use common\models\Restaurant;
use common\models\food\Food;
Use common\models\Area;
use yii\helpers\ArrayHelper;
use common\models\Upload;
use yii\web\UploadedFile;
use common\models\Rmanager;
use common\models\Rmanagerlevel;
use common\models\Order\Orderitem;
use yii\filters\AccessControl;
use common\models\User;
use common\models\AuthAssignment;
use common\models\user\Userdetails;
use common\models\food\Foodtype;
use yii\data\Pagination;
use common\models\Restauranttypejunction;
use common\models\Restauranttype;
use frontend\modules\Restaurant\controllers\RestauranttypeController;
use frontend\controllers\CommonController;
use common\models\MonthlyUnix;
use common\models\Object;
use yii\web\Session;

/**
 * Default controller for the `Restaurant` module
 */
class DefaultController extends CommonController
{
    /**
     * Renders the index view for the module
     * @return string
     */

    public function behaviors()
    {
         return [
             'access' => [
                 'class' => AccessControl::className(),
                 //'only' => ['logout', 'signup','index'],
                 'rules' => [
                     [
                         'actions' => ['new-restaurant-location','new-restaurant-details','new-restaurant','edit-restaurant-details','edit-restaurant-area','edited-location-details','edit-restaurant-details2','manage-restaurant-staff','delete-restaurant-staff','add-staff',
                         'view-restaurant', 'all-rmanagers','get-area'],
                         'allow' => true,
                         'roles' => ['restaurant manager'],
 
                     ],
                    [
                        'actions' => ['index','show-by-food', 'food-filter', 'restaurant-filter','food-details','restaurant-details','addsession','changecookie'],
                        'allow' => true,
                        'roles' => ['@','?'],

                    ],
                    
                 ]
             ]
        ];
    }

//--This function gets the restaurants in the area according to the users postcode and area
    public function actionIndex($type=0,$filter="")
    {
        $cookies = Yii::$app->request->cookies;
        $session = Yii::$app->session;
        /*$halal = $cookies->getValue('halal', 'value');
        $query = restaurant::find()->distinct()->where('Restaurant_AreaGroup = :group and Restaurant_Status = :status' ,[':group' => $groupArea, ':status'=>'Operating'])->joinWith(['rJunction']);
        if(empty($halal) || $halal['value'] == 0)
        {
            $query->andWhere('Type_ID =  24');
        }*/
        $halal = $cookies->getValue('halal');
       
        $query = restaurant::find()->distinct()->where('Restaurant_AreaGroup = :group and Restaurant_Status = :status' ,[':group' => $session['group'], ':status'=>'Operating'])->joinWith(['rJunction']);
        
        if(!empty($halal) || $halal == 1)
        {
            $query->andWhere('Type_ID =  23');
        }
      
        if($type !=0)
        {
            $query->OrWhere('Type_ID = :tid',[':tid' => $type]);
        }

        if(!empty($filter))
        {
            $query->andWhere(['like','Restaurant_Name',$filter]);
        }

        
        $pagination = new Pagination(['totalCount'=>$query->count(),'pageSize'=>10]);
        $restaurant = $query->offset($pagination->offset)
        ->limit($pagination->limit)
        ->all();
        
        $typequery= Restauranttype::find()->orderBy(['Type_Name'=>SORT_ASC])->where(['and',['!=','id',23],['!=','id',24]])->all();
        $allrestauranttype = ArrayHelper::map($typequery,'ID','Type_Name');
       
       $staffs = Rmanagerlevel::find()->where('User_Username=:u',[':u' => Yii::$app->user->identity->username])->all();
       if (!empty($staffs)) {
           $staffs = true;
       }
       else{
        $staff=false;
       }

        // var_dump($restaurant[0]['restaurantType'][0]);exit;
        /*$types = Restauranttype::find()->orderBy(['Type_Name'=>SORT_ASC])->all();
        $mode = 1;

        $search = new Food();

        if ($search->load(Yii::$app->request->post()))
        {
            $mode = 3;
            $keyword = $search->Nickname;
            $restaurant = restaurant::find()->where('Restaurant_AreaGroup = :group and Restaurant_Status = :status' ,[':group' => $groupArea, ':status'=>'Operating'])->andWhere(['like', 'Restaurant_Name', $keyword]);
            $pagination = new Pagination(['totalCount'=>$restaurant->count(),'pageSize'=>10]);
            $restaurant = $restaurant->offset($pagination->offset)
            ->limit($pagination->limit)
            ->all();

            return $this->render('index',['restaurant'=>$restaurant, 'groupArea'=>$groupArea, 'types'=>$types, 'mode'=>$mode, 'search'=>$search, 'keyword'=>$keyword,'pagination'=>$pagination]);
        }*/
        $this->layout = 'main3';
        return $this->render('index',['restaurant'=>$restaurant, 'allrestauranttype'=>$allrestauranttype ,'type' => $type,'filter'=>$filter,'pagination'=>$pagination,'staffs'=>$staffs]);
    }


    public function actionAddsession($page)
    {

        $postcodeArray = ArrayHelper::map(Area::find()->all(),'Area_ID','Area_Area');
            
        if (Yii::$app->request->post()) 
        {
            $post = Yii::$app->request->post();
          
            if (is_null($post['area'])) {
                Yii::$app->session->setFlash('error', 'Please select area to continue.');
                return $this->refresh();
            }

            $session = Yii::$app->session;

            if(!is_null($session['area']))
            {
                $session->remove('area');
            }

            if(!is_null($session['group']))
            {
                $session->remove('group');
            }
            
            $group = Area::findOne($post['area']);
            $session = new Session;
            $session->open();
           
           
            $session['area'] = $group->Area_Area;
            $session['group'] = $group->Area_Group;
            $session->close();
            return $this->redirect(Yii::$app->request->referrer);
            
        }
        return $this->renderAjax('addsession',['postcodeArray'=>$postcodeArray]);
    }

//--This function loads the restaurant's details
    public function actionRestaurantDetails($rid)
    {
        if (!(Yii::$app->user->isGuest)) {
            $rmanager = Rmanager::find()->where('uid=:id',[':id'=>Yii::$app->user->identity->id])->one();
        }
        if (empty($rmanager)) {
            $valid = Restaurant::find()->where('Restaurant_ID=:id AND Restaurant_Status=:s',[':id'=>$rid,':s'=>"Operating"])->one();
            if (empty($valid)) {
                Yii::$app->session->setFlash('error', 'This restaurant was not valid now.');
               
            }
        }

        $id = restaurant::find()->where('restaurant.Restaurant_ID = :rid' ,[':rid' => $rid])->innerJoinWith('restaurantType')->one();

        if(empty($id))
        {
            Yii::$app->session->setFlash('error', 'This restaurant cannot be found.');
             return $this->redirect(['/site/index']);
        }

      
        //$model = food::find()->where('Restaurant_ID=:id and Status = :status', [':id' => $rid, ':status'=> 1])->innerJoinWith('foodType',true)->innerJoinWith('foodStatus',true);
        $model = food::find()->where('Restaurant_ID=:id',[':id' => $rid])->joinWith(['foodStatus'=>function($query){
            $query->where('Status = 1');
        }]);
        // if (!empty($rmanager)) {
        //    $model = food::find()->where('Restaurant_ID=:id', [':id' => $rid])->andWhere(["!=","Status",'-1'])->andWhere(["!=","foodtypejunction.Type_ID",5])->innerJoinWith('foodType',true)->innerJoinWith('foodStatus',true);
        // }
       
        //$countmodel = "SELECT DISTINCT food.Food_ID FROM food INNER JOIN foodstatus ON foodstatus.Food_ID = food.Food_ID WHERE food.Restaurant_ID = ".$rid." AND foodstatus.Status = ".true."";
        //$resultcountmodel = Yii::$app->db->createCommand($countmodel)->execute();
        $countQuery = clone $model;
        $pagination = new Pagination(['totalCount'=>$countQuery->count(),'pageSize'=>10]);
        $rowfood = $model->offset($pagination->offset)
        ->limit($pagination->limit)
      
        ->all();
        
        if (!(Yii::$app->user->isGuest)) {
        $staff = Rmanagerlevel::find()->where('User_Username = :uname and Restaurant_ID = :id', [':uname'=>Yii::$app->user->identity->username, ':id'=>$rid])->one();
        return $this->render('restaurantdetails',['id'=>$id, 'rowfood'=>$rowfood, 'staff'=>$staff,'pagination'=>$pagination, 'rid'=>$rid]);
        }
        return $this->render('restaurantdetails',['id'=>$id, 'rowfood'=>$rowfood,'pagination'=>$pagination, 'rid'=>$rid]);
    }

//--This function loads the Food Details according to the FoodController
    public function actionFoodDetails($fid)
    {
        $fooddata = food::find()->where('Food_ID=:id',[':id'=>$fid])->one();
        $foodid = $fooddata['Food_ID'];

        return $this->redirect(['/food/food-details', 'id'=>$foodid]);
    }

//--This function captures the new restaurant's area group based on the entered postcode and area
    public function actionNewRestaurantLocation()
    {
        $postcode = new Area();
        $postcodeArray = ArrayHelper::map(Area::find()->all(),'Area_Area','Area_Area');
        if(Yii::$app->request->isPost)
        {
            $area = Yii::$app->request->post('Area');
            $areachosen = $area['Area_Area'];
            $restArea = Area::find()->where('Area_Area = :area_area',[':area_area'=>$area['Area_Area']])->one();        
            $restArea = $restArea['Area_Group'];
            // return $this->actionEditRestaurantDetails2($restArea, $postcodechosen, $areachosen, $rid);
            return $this->redirect(['new-restaurant', 'restArea'=>$restArea, 'areachosen'=>$areachosen]);
        }

        return $this->render('newrestaurantlocation',['postcode'=>$postcode, 'postcodeArray'=>$postcodeArray]);
    }
    
//--This creates a new restaurant
    public function actionNewRestaurant($restArea, $areachosen)
    {
        $restaurant = new Restaurant();

        $upload = new Upload();
        //$path = Yii::$app->request->baseUrl.'/imageLocation/';
        $foodjunction = new Restauranttypejunction();
        $type = ArrayHelper::map(Restauranttype::find()->orderBy(['(Type_Name)' => SORT_ASC])->all(),'ID','Type_Name');

        if ($restaurant->load(Yii::$app->request->post()))
            {
                $post = Yii::$app->request->post();
                $upload->imageFile =  UploadedFile::getInstance($restaurant, 'Restaurant_RestaurantPicPath');
                $upload->imageFile->name = time().'.'.$upload->imageFile->extension;

                $upload->upload('imageLocation/');

                $restaurant->Restaurant_RestaurantPicPath = $upload->imageFile->name;
                $restaurant->Restaurant_Manager=Yii::$app->user->identity->username;

                $restaurant->Restaurant_AreaGroup = $restArea;
                $restaurant->Restaurant_Area = $areachosen;
                $restaurant->Restaurant_DateTimeCreated = time();
                $restaurant->Restaurant_Status = 'Under Renovation';
                $restaurant->Restaurant_Rating = "0";
                $restaurant->approval = 0;

                if ($restaurant->validate()) {
                    $restaurant->save();

                    $post['Type_ID'][] = $post['Restauranttypejunction']['Type_ID'];
                    RestauranttypeController::newRestaurantJunction($post['Type_ID'],$restaurant->Restaurant_ID);
                    
    //--------------The restaurant creator is created here
                    $rmanagerlevel = new Rmanagerlevel();
                    $rmanagerlevel->User_Username = Yii::$app->user->identity->username;
                    $rmanagerlevel->Restaurant_ID =  $restaurant['Restaurant_ID'];
                    $rmanagerlevel->RmanagerLevel_Level = 'Owner';
                    $rmanagerlevel->Rmanager_DateTimeAdded = time();
                    
                    if ($rmanagerlevel->validate()) {
                        $rmanagerlevel->save();
                        Yii::$app->session->setFlash('success', 'Registered! Please wait admin to confirm restaurant information!');
                        return $this->redirect(['/Restaurant/restaurant/restaurant-service']);       
                    }
                    else
                    {
                        RestauranttypeController::deleteRestaurantJunction($restaurant['Restaurant_ID']);
                        $restaurant->delete();
                    }
                }

                Yii::$app->session->setFlash('warning', 'Register Failed!');
                return $this->redirect(['/Restaurant/restaurant/restaurant-service']);  
            }
        return $this->render('newrestaurant', ['restaurant' => $restaurant, 'type'=>$type,'area'=>$areachosen,'foodjunction'=>$foodjunction]);
    }

//--This saves the edited restaurant details
    public function actionEditRestaurantDetails($rid, $areachosen, $restArea)
    {
        $upload = new Upload();

        //$path = Yii::$app->request->baseUrl.'/imageLocation/';
        $staff = Rmanagerlevel::find()->where('User_Username = :uname and Restaurant_ID = :id', [':uname'=>Yii::$app->user->identity->username, ':id'=>$rid])->one();
        $restaurantdetails = restaurant::find()->where('Restaurant_ID = :rid'  , [':rid' => $rid])->one();
        $rpicpath = $restaurantdetails['Restaurant_RestaurantPicPath'];
        $restArea = $restArea;
        $areachosen = $areachosen;
        $restaurant = Restaurant::find()->where(Restaurant::tableName().'.Restaurant_ID = :id' ,[':id' => $rid])->innerJoinWith('restaurantType',true)->one();

        $linkData = CommonController::restaurantPermission($rid);
        $link = CommonController::getRestaurantUrl($linkData[0],$linkData[1],$linkData[2],$rid);

        $chosen = ArrayHelper::map($restaurant['restaurantType'],'ID','ID');
        $type = ArrayHelper::map(RestaurantType::find()->orderBy(['(Type_Name)' => SORT_ASC])->all(),'ID','Type_Name');

        if($restaurantdetails->load(Yii::$app->request->post()))
        {
                $post = Yii::$app->request->post();
        
                //$model->action = 1;
                //$model->action_before=1;
                $upload->imageFile =  UploadedFile::getInstance($restaurantdetails, 'Restaurant_RestaurantPicPath');

                if (!is_null($upload->imageFile))
                {
                    $upload->imageFile->name = time().'.'.$upload->imageFile->extension;
                    // $post['User_PicPath'] = 
                     $upload->upload('imageLocation/');
                     
                     //$restaurantdetails->load($post);
                 
                     $restaurantdetails->Restaurant_RestaurantPicPath = $upload->imageFile->name;
     
                     Yii::$app->session->setFlash('success', 'Upload Successful');
                }
                else
                {
                    $restaurantdetails->Restaurant_RestaurantPicPath = $rpicpath;
                }

                $restaurantdetails->Restaurant_Area = $areachosen;
                $restaurantdetails->Restaurant_AreaGroup = $restArea;

                 $isValid = $restaurantdetails->validate();
                if($isValid)
                {
                    $restaurantdetails->save();

                    $restaurant = Restaurant::findOne($rid);
                    $modelJunction = $restaurant->rJunction;
            
                    $junctionData = RestauranttypeController::diffStatus($modelJunction,$post['Type_ID']);
            
                        $transaction = Yii::$app->db->beginTransaction();
                        try
                        {
                            if(!empty($junctionData[0]))
                            {
                                foreach($junctionData[0] as $deleteId)
                                {
                                        Restauranttypejunction::deleteAll('Restaurant_ID = :rid and Type_ID = :tid',[':rid' => $restaurant->Restaurant_ID, ':tid' => $deleteId]);
                                }
                            }
                            
                            if(!empty($junctionData[1]))
                            {
                                RestauranttypeController::newRestaurantJunction($junctionData[1],$restaurant->Restaurant_ID);
                            }

                            $transaction->commit();
                            Yii::$app->session->setFlash('success', "Success edit");

                            Yii::$app->session->setFlash('success', "Update completed");
                            return $this->redirect(['restaurant-details', 'rid'=>$rid]);
                        }

                        catch(Exception $e)
                        {
                            $transaction->rollBack();
                        }
                            Yii::$app->session->setFlash('warning', "Fail edit");
                            return $this->redirect(Yii::$app->request->referrer);
                        }
                        else
                        {
                            Yii::$app->session->setFlash('warning', "Fail edit");
                            return $this->redirect(Yii::$app->request->referrer);
                        }
                }

    //$this->view->title = 'Update Profile';
    //$this->layout = 'user';
    
    return $this->render('editrestaurantdetails', ['restaurantdetails'=>$restaurantdetails, 'areachosen'=>$areachosen, 'restArea'=>$restArea, 'chosen'=>$chosen, 'type'=>$type, 'staff'=>$staff,'link'=>$link]);
    }

    public function actionEditRestaurantArea($rid)
    {
        $restaurantdetails = restaurant::find()->where('Restaurant_ID = :rid'  , [':rid' => $rid])->one();
        $rid = $restaurantdetails['Restaurant_ID'];
        $postcode = new Area();
        $postcodeArray = ArrayHelper::map(Area::find()->all(),'Area_Area','Area_Area');
        if($postcode->load(Yii::$app->request->post()))
        {
            $area = Yii::$app->request->post('Area');
            $areachosen = $area['Area_Area'];
            if ($areachosen == ''){
                $areachosen = $restaurantdetails['Restaurant_Area'];
                $restArea = $restaurantdetails['Restaurant_AreaGroup'];
                Yii::$app->session->setFlash('danger', "Area not chosen.");
                return $this->redirect(['edit-restaurant-details', 'restArea'=>$restArea, 'rid'=>$rid, 'areachosen'=>$areachosen]);
            } else {
            $restArea = Area::find()->where('Area_Area = :area_area',[':area_area'=>$area['Area_Area']])->one();
            $restArea = $restArea['Area_Group'];
            
            // return $this->actionEditRestaurantDetails2($restArea, $postcodechosen, $areachosen, $rid);
            return $this->redirect(['edit-restaurant-details', 'restArea'=>$restArea, 'rid'=>$rid, 'areachosen'=>$areachosen]);
            }
        }

        return $this->renderAjax('editrestaurantlocation',['restaurantdetails'=>$restaurantdetails, 'postcode'=>$postcode , 'rid'=>$rid, 'postcodeArray'=>$postcodeArray]);
    }

//--This function shows all the staff under a specific restaurant
    public function actionManageRestaurantStaff($rid)
    {
        $rid = $rid;
        $rstaff = Rmanagerlevel::find()->where('Restaurant_ID = :rid',[':rid'=>$rid])->all();

        $id = Restaurant::find()->where('Restaurant_ID = :rid',[':rid'=>$rid])->one();

        $me = Rmanagerlevel::find()->where('Restaurant_ID = :rid and User_Username = :uname', [':rid'=>$rid, ':uname'=>Yii::$app->user->identity->username])->one();
        // var_dump($me);exit;
        
        $linkData = CommonController::restaurantPermission($rid);
        $link = CommonController::getRestaurantUrl($linkData[0],$linkData[1],$linkData[2],$rid);

        return $this->render('managerestaurantstaff',['rid'=>$rid, 'rstaff'=>$rstaff, 'id'=>$id, 'me'=>$me ,'link'=>$link]);
    }

//--This function deletes a staff from a specific restaurant
    public function actionDeleteRestaurantStaff($rid, $uname)
    {
        $sql = "DELETE FROM rmanagerlevel WHERE User_Username = '$uname' AND Restaurant_ID = $rid";
        Yii::$app->db->createCommand($sql)->execute();
        $rid = $rid;
        $id = restaurant::find()->where('Restaurant_ID = :rid',[':rid'=>$rid])->one();
        $rstaff = rmanagerlevel::find()->where('Restaurant_ID = :rid',[':rid'=>$rid])->all();

        return $this->redirect(Yii::$app->request->referrer);
    }


//--This function lists all the restaurant managers in hamsterEat
    public function actionAllRmanagers($rid)
    {
        $allrmanagers = user::find()->innerJoinWith('authAssignment','user.id = authAssignment.user_id')->where(['auth_assignment.item_name' => "restaurant manager"])->all();

        $food = new Food;

        $keyword = '';

//------This filters the restaurant managers according to their name and search text
        if ($food->load(Yii::$app->request->post()))
        {
            $keyword = $food->Nickname;

            $allrmanagers = user::find()->innerJoinWith('authAssignment','user.id = authAssignment.user_id')->where(['auth_assignment.item_name' => "restaurant manager"])->andWhere(['like', 'user.username', $keyword])->all();
            
            return $this->render('allrmanagers',['allrmanagers'=>$allrmanagers, 'rid'=>$rid, 'food'=>$food, 'keyword'=>$keyword]);
            
        }
        return $this->render('allrmanagers',['allrmanagers'=>$allrmanagers, 'rid'=>$rid, 'food'=>$food, 'keyword'=>$keyword]);
    }

//--This function adds restaurant managers as a staff to a specific restaurant and under different positions
    public function actionAddStaff($rid, $uname, $num)
    {
        $time = time();
        if ($num == "1")
        {
            $sql = "INSERT INTO rmanagerlevel (User_Username, Restaurant_ID, RmanagerLevel_Level, Rmanager_DateTimeAdded) VALUES ('$uname', $rid, 'Owner', $time)";
        }
        elseif ($num == "2")
        {
            $sql = "INSERT INTO rmanagerlevel (User_Username, Restaurant_ID, RmanagerLevel_Level, Rmanager_DateTimeAdded) VALUES ('$uname', $rid, 'Manager', $time)";
        }
        elseif ($num == "3")
        {
            $sql = "INSERT INTO rmanagerlevel (User_Username, Restaurant_ID, RmanagerLevel_Level, Rmanager_DateTimeAdded) VALUES ('$uname', $rid, 'Operator', $time)";
        }

        Yii::$app->db->createCommand($sql)->execute();
        
        return $this->redirect(['manage-restaurant-staff','rid'=>$rid]);
    }

//--This shows the food available in the area group according to user keyed in postcode and area
    public function actionShowByFood($type = 0,$filter="")
    {
        $cookies = Yii::$app->request->cookies;
        $session = Yii::$app->session;
        $halal = $cookies->getValue('halal');
      
        $query = food::find()->distinct()->where('restaurant.Restaurant_AreaGroup = :group and foodstatus.Status = 1',[':group' => $session['group']])->joinWith(['restaurant','junction','foodStatus']);

        if(!empty($halal) || $halal == 1)
        {
            $query->andWhere('foodtypejunction.Type_ID =  3');
        }
        
         

        if($type != 0)
        {
          $query->OrWhere('foodtypejunction.Type_ID = :tid', [':tid' => $type]);
        }

        if(!empty($filter))
        {
            $query->andWhere(['like','Name',$filter]);
        }
        
        $countQuery = clone $query;
        $pages = new Pagination(['totalCount' => $countQuery->count()]);
        $food = $query->offset($pages->offset)
        ->limit($pages->limit)
        ->all();
        //$food = food::find()->where('restaurant.Restaurant_AreaGroup = :group',[':group' => $groupArea])->joinWith(['restaurant' ,'junction'])->all();
        
        $foodquery = Foodtype::find()->andWhere('ID != 3 and ID != 4')->orderBy(['Type_Desc'=>SORT_ASC]);
        
        $allfoodtype = ArrayHelper::map($foodquery->all(),'ID','Type_Desc');
        
/*        $mode = 1;

        $search = new Food();
        if ($search->load(Yii::$app->request->post()))
        {
            $mode = 3;
            $keyword = $search->Nickname;

            return $this->render('index2',['restaurant'=>$restaurant, 'groupArea'=>$groupArea, 'types'=>$types, 'mode'=>$mode, 'search'=>$search, 'keyword'=>$keyword]);
        }*/

        //var_dump($types);exit;
        $this->layout = 'main3';
        return $this->render('index2',['food'=>$food, 'pagination' => $pages, 'allfoodtype'=>$allfoodtype, 'filter'=>$filter,'type' => $type]);
    }


//--This function loads the all the restaurants that the specific user is a staff in
    public function actionViewRestaurant()
    {
		//$uname = user::find()->where('username = :uname',[':uname' => Yii::$app->user->identity->username])->one();
        $restaurants = Rmanagerlevel::find()->where('User_Username = :uid',[':uid' => Yii::$app->user->identity->username])->all();
        $this->layout = '@app/views/layouts/user';
        //var_dump($restaurant);exit;
        return $this->render('ViewRestaurant',['restaurants'=>$restaurants]);
    }

//--This changes the restaurant status from under renovation to operating when a food is added
    public static function updateRestaurant($rid)
    {
        $data = Restaurant::find()->where('Restaurant_ID = :rid', [':rid'=>$rid])->one();
        if($data->Restaurant_Status == 'Under Renovation')
        {
            $data->Restaurant_Status = "Operating";
            if($data->save())
            {
                return true;
            }
            return false;
        }
        return true;
    }

    public function actionChangecookie($type)
    {
        $cookies = Yii::$app->response->cookies;
        if(!is_null($cookies['halal']))
        {
            $cookies->remove('halal');
        }
        
        $cookie =  new Cookie([
            'name' => 'halal',
            'value' => $type,
            'expire' => time() + 86400 * 365,
        ]);
          
        \Yii::$app->getResponse()->getCookies()->add($cookie);
        return $this->redirect(Yii::$app->request->referrer); 
    }
}