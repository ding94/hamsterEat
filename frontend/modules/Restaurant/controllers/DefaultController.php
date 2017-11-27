<?php
namespace frontend\modules\Restaurant\controllers;

use yii;
use yii\web\Controller;
use common\models\Restaurant;
use common\models\food\Food;
Use common\models\Area;
use yii\helpers\ArrayHelper;
use common\models\Upload;
use yii\web\UploadedFile;
use common\models\Rmanager;
use common\models\Rmanagerlevel;
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
                         'view-restaurant', 'all-rmanagers', 'show-monthly-earnings','get-area'],
                         'allow' => true,
                         'roles' => ['restaurant manager'],
 
                     ],
                    [
                        'actions' => ['index','show-by-food', 'food-filter', 'restaurant-filter','food-details','restaurant-details','addsession'],
                        'allow' => true,
                        'roles' => ['@','?'],

                    ],
                    
                 ]
             ]
        ];
    }

//--This function gets the restaurants in the area according to the users postcode and area
    public function actionIndex($groupArea,$type=0,$filter="")
    {
        $cookies = Yii::$app->request->cookies;
        /*$halal = $cookies->getValue('halal', 'value');
        $query = restaurant::find()->distinct()->where('Restaurant_AreaGroup = :group and Restaurant_Status = :status' ,[':group' => $groupArea, ':status'=>'Operating'])->joinWith(['rJunction']);
        if(empty($halal) || $halal['value'] == 0)
        {
            $query->andWhere('Type_ID =  24');
        }*/
        $halal = $cookies->getValue('halal');
       
        $query = restaurant::find()->distinct()->where('Restaurant_AreaGroup = :group and Restaurant_Status = :status' ,[':group' => $groupArea, ':status'=>'Operating'])->joinWith(['rJunction']);
        
        if(empty($halal) || $halal == 0)
        {
            $query->andWhere('Type_ID =  24');
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

        return $this->render('index',['restaurant'=>$restaurant, 'groupArea'=>$groupArea, 'allrestauranttype'=>$allrestauranttype ,'type' => $type,'filter'=>$filter,'pagination'=>$pagination]);
    }


    public function actionAddsession($page)
    {
        $model = new Area;
        $postcodeArray = ArrayHelper::map(Area::find()->all(),'Area_Postcode','Area_Postcode');
        if (Yii::$app->request->post()) 
        {
            $model->load(Yii::$app->request->post());
            $groupArea = Area::find()->where('Area_Postcode = :p and Area_Area = :a',[':p'=> $model['Area_Postcode'] , ':a'=>$model['Area_Area']])->one()->Area_Group;
            $session = new Session;
            $session->open();
            $session['postcode'] = $model['Area_Postcode'];
            $session['area'] = $model['Area_Area'];
            $session['group'] = $groupArea;

            if ($page== 'index2') {
                return $this->redirect(['/Restaurant/default/show-by-food','groupArea'=>$groupArea]);
            }
            else{
                return $this->redirect(['/Restaurant/default/index','groupArea'=>$groupArea]);
            }
            
        }
        return $this->renderAjax('addsession',['model'=>$model,'postcodeArray'=>$postcodeArray]);
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
                return $this->redirect(['/site/index']);
            }
        }

        $id = restaurant::find()->where('restaurant.Restaurant_ID = :rid' ,[':rid' => $rid])->innerJoinWith('restaurantType')->one();

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
        $postcodeArray = ArrayHelper::map(Area::find()->all(),'Area_Postcode','Area_Postcode');
        if(Yii::$app->request->isPost)
        {
            $area = Yii::$app->request->post('Area');
            $postcodechosen = $area['Area_Postcode'];
            $areachosen = $area['Area_Area'];
            $restArea = Area::find()->where('Area_Postcode = :area_postcode and Area_Area = :area_area',[':area_postcode'=> $area['Area_Postcode'] , ':area_area'=>$area['Area_Area']])->one();        
            $restArea = $restArea['Area_Group'];
            // return $this->actionEditRestaurantDetails2($restArea, $postcodechosen, $areachosen, $rid);
            return $this->redirect(['new-restaurant', 'restArea'=>$restArea, 'postcodechosen'=>$postcodechosen, 'areachosen'=>$areachosen]);
        }

        return $this->render('newrestaurantlocation',['postcode'=>$postcode, 'postcodeArray'=>$postcodeArray]);
    }
    
//--This creates a new restaurant
    public function actionNewRestaurant($restArea, $postcodechosen, $areachosen)
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
                //$post['User_PicPath'] = 
                $upload->upload('imageLocation/');
                
                // $restaurant->load($post);
            
                $restaurant->Restaurant_RestaurantPicPath = $upload->imageFile->name;
                $restaurant->Restaurant_Manager=Yii::$app->user->identity->username;

                $restaurant->Restaurant_AreaGroup = Yii::$app->request->post('restArea');
                $restaurant->Restaurant_Postcode = Yii::$app->request->post('postcodechosen');
                $restaurant->Restaurant_Area = Yii::$app->request->post('areachosen');
                $time = time(); $restaurant->Restaurant_DateTimeCreated = $time;
                $restaurant->Restaurant_Status = 'Under Renovation';
                $restaurant->Restaurant_Rating = "0";
                
                $restaurant->save();

                RestauranttypeController::newRestaurantJunction($post['Type_ID'],$restaurant->Restaurant_ID);
                
//--------------The restaurant creator is created here
                $rmanagerlevel = new Rmanagerlevel();
                $asd =  restaurant::find()->where('Restaurant_Manager = :restaurantowner and Restaurant_DateTimeCreated = :timecreated',[':restaurantowner'=>Yii::$app->user->identity->username, ':timecreated'=>$time])->one();
                $rid = $asd['Restaurant_ID'];
                $rowner = $asd['Restaurant_Manager'];
                $rmanagerlevel->Restaurant_ID = $rid;
                $rmanagerlevel->User_Username = $rowner;
                $rmanagerlevel->RmanagerLevel_Level = 'Owner';
                $rmanagerlevel->Rmanager_DateTimeAdded = $time;
                //var_dump($rmanagerlevel->save());exit;
                $rmanagerlevel->save(false);
                
                Yii::$app->session->setFlash('success', 'Congratulations! Your restaurant has been set up and is currently waiting for a menu to be set up.');
                
                return $this->redirect(['restaurant-details', 'restaurant'=> $restaurant, 'restArea'=>Yii::$app->request->post('restArea'), 'postcodechosen'=>Yii::$app->request->post('postcodechosen'), 'areachosen'=>Yii::$app->request->post('areachosen'), 'rid'=>$rid]);           
            }
        else 
            {
                return $this->render('newrestaurant', ['restaurant' => $restaurant, 'restArea'=>$restArea, 'postcodechosen'=>$postcodechosen, 'areachosen'=>$areachosen, 'type'=>$type]);
            }
    }

//--This saves the edited restaurant details
    public function actionEditRestaurantDetails($rid, $postcodechosen, $areachosen, $restArea)
    {
        $upload = new Upload();

        //$path = Yii::$app->request->baseUrl.'/imageLocation/';
        $staff = Rmanagerlevel::find()->where('User_Username = :uname and Restaurant_ID = :id', [':uname'=>Yii::$app->user->identity->username, ':id'=>$rid])->one();
        $restaurantdetails = restaurant::find()->where('Restaurant_ID = :rid'  , [':rid' => $rid])->one();
        $rpicpath = $restaurantdetails['Restaurant_RestaurantPicPath'];
        $restArea = $restArea;
        $postcodechosen = $postcodechosen;
        $areachosen = $areachosen;
        $restaurant = Restaurant::find()->where(Restaurant::tableName().'.Restaurant_ID = :id' ,[':id' => $rid])->innerJoinWith('restaurantType',true)->one();
        $link = CommonController::getRestaurantUrl($rid,$restaurant['Restaurant_AreaGroup'],$restaurant['Restaurant_Area'],$restaurant['Restaurant_Postcode'],$staff['RmanagerLevel_Level'],$staff['RmanagerLevel_Level']);

        $chosen = ArrayHelper::map($restaurant['restaurantType'],'ID','ID');
        $type = ArrayHelper::map(RestaurantType::find()->orderBy(['(Type_Name)' => SORT_ASC])->all(),'ID','Type_Name');
        //var_dump($restArea,$areachosen,$postcodechosen,$rid);exit;

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

                $restaurantdetails->Restaurant_Postcode = $postcodechosen;
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
    
    return $this->render('editrestaurantdetails', ['restaurantdetails'=>$restaurantdetails, 'postcodechosen'=>$postcodechosen, 'areachosen'=>$areachosen, 'restArea'=>$restArea, 'chosen'=>$chosen, 'type'=>$type, 'staff'=>$staff,'link'=>$link]);
    }

    /* Function for dependent dropdown in frontend index page. */
    public function actionGetArea()
    {
    if (isset($_POST['depdrop_parents'])) {
        $parents = $_POST['depdrop_parents'];
        if ($parents != null) {
            $cat_id = $parents[0];
            $out = self::getAreaList($cat_id); 
            echo json_encode(['output'=>$out, 'selected'=>'']);
            return;
        }
    }
    echo json_encode(['output'=>'', 'selected'=>'']);
    }

    public static function getAreaList($postcode)
    {
        $area = Area::find()->where(['like','Area_Postcode' , $postcode])->select(['Area_ID', 'Area_Area'])->all();
        $areaArray = [];
        foreach ($area as $area) {
            $object = new Object();
            $object->id = $area['Area_Area'];
            $object->name = $area['Area_Area'];

            $areaArray[] = $object;
        }
        return $areaArray;
    }

    public function actionEditRestaurantArea($rid)
    {
        $restaurantdetails = restaurant::find()->where('Restaurant_ID = :rid'  , [':rid' => $rid])->one();
        $rid = $restaurantdetails['Restaurant_ID'];
        $postcode = new Area();
        $postcodeArray = ArrayHelper::map(Area::find()->all(),'Area_Postcode','Area_Postcode');

        if($postcode->load(Yii::$app->request->post()))
        {
            $area = Yii::$app->request->post('Area');
            $postcodechosen = $area['Area_Postcode'];
            $areachosen = $area['Area_Area'];
            $restArea = Area::find()->where('Area_Postcode = :area_postcode and Area_Area = :area_area',[':area_postcode'=> $area['Area_Postcode'] , ':area_area'=>$area['Area_Area']])->one();        
            $restArea = $restArea['Area_Group'];

            // return $this->actionEditRestaurantDetails2($restArea, $postcodechosen, $areachosen, $rid);
            return $this->redirect(['edit-restaurant-details', 'restArea'=>$restArea, 'postcodechosen'=>$postcodechosen, 'rid'=>$rid, 'areachosen'=>$areachosen]);
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
        $link = CommonController::getRestaurantUrl($rid,$id['Restaurant_AreaGroup'],$id['Restaurant_Area'],$id['Restaurant_Postcode'],$me['RmanagerLevel_Level']);

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
    public function actionShowByFood($groupArea,$type = 0,$filter="")
    {
        $cookies = Yii::$app->request->cookies;
        $halal = $cookies->getValue('halal');
      
        $query = food::find()->distinct()->where('restaurant.Restaurant_AreaGroup = :group and foodstatus.Status = 1',[':group' => $groupArea])->joinWith(['restaurant','junction','foodStatus']);

        if(empty($halal) || $halal['value'] == 0)
        {
            $query->andWhere('foodtypejunction.Type_ID =  4');
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
        
        $foodquery = Foodtype::find()->orderBy(['Type_Desc'=>SORT_ASC]);
        if(empty($halal) || $halal['value'] == 0)
        {
            $foodquery->andWhere('ID != 3');
        }
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
        return $this->render('index2',['food'=>$food, 'pagination' => $pages, 'groupArea'=>$groupArea, 'allfoodtype'=>$allfoodtype, 'filter'=>$filter,'type' => $type]);
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

//--This shows the restaurant's monthly earnings
    public function actionShowMonthlyEarnings($rid)
    {
        $restaurant = Restaurant::find()->where('Restaurant_ID = :rid', [':rid' => $rid])->one();
        $restaurantname = $restaurant['Restaurant_Name'];
        $staff = Rmanagerlevel::find()->where('User_Username = :uname and Restaurant_ID = :id', [':uname'=>Yii::$app->user->identity->username, ':id'=>$rid])->one();
        $link = CommonController::getRestaurantUrl($rid,$restaurant['Restaurant_AreaGroup'],$restaurant['Restaurant_Area'],$restaurant['Restaurant_Postcode'],$staff['RmanagerLevel_Level']);
        $currentmonth = date('F');
        $currentmonthnum = date('n');
        $currentyear = date('Y');
        $selected = new MonthlyUnix();
        // var_dump($currentmonth);exit;
        $months = ArrayHelper::map(MonthlyUnix::find()->select('*')->distinct()->all(),'Month', 'Month');
        $year = ArrayHelper::map(MonthlyUnix::find()->select('*')->distinct()->all(),'Year', 'Year');

//------This filters the restaurant's earnings based on chosen month
        if($selected->load(Yii::$app->request->post()))
        {
            $selectedmonth = $selected->Month;
            $selectedyear = $selected->Year;

            $filter = MonthlyUnix::find()->where('Month = :m and Year = :y', [':m'=>$selectedmonth, ':y'=>$selectedyear])->one();
            $startunix = $filter['FirstDay'];
            $endunix = $filter['LastDay'];

            $search = "SELECT Delivery_ID from ordersstatuschange WHERE OChange_CompletedDateTime BETWEEN ".$startunix." AND ".$endunix."";
            $search = Yii::$app->db->createCommand($search)->queryAll();

            $totalearnings = 0;
            $thefinaltotalearnings = 0;
            foreach ($search as $search) :
                $deliveryid = $search['Delivery_ID'];
                $earnhere = 0;
                $moneh = "SELECT orderitem.Restaurant_Share,orderitem.Order_ID FROM orderitem INNER JOIN orders on orders.Delivery_ID = orderitem.Delivery_ID INNER JOIN food on food.Food_ID = orderitem.Food_ID INNER JOIN restaurant on restaurant.Restaurant_ID = food.Restaurant_ID WHERE restaurant.Restaurant_ID = ".$rid." AND  orders.Delivery_ID = ".$deliveryid."";
                $moneh = Yii::$app->db->createCommand($moneh)->queryAll();

                if (empty($moneh))
                {
                    $earnings = 0;
                }
                else
                {
                    foreach ($moneh as $money) :
                        $earnings = $money['Restaurant_Share'];
                        $earnhere = $earnings + $earnhere;
                    endforeach;
                }

                $totalearnings = $totalearnings + $earnhere;

            endforeach;

            $thefinaltotalearnings = $totalearnings + $thefinaltotalearnings;
            $mode = 2;

            return $this->render('restaurantearnings', ['rid'=>$rid , 'restaurant'=>$restaurant, 'restaurantname'=>$restaurantname, 'months'=>$months, 'selected'=>$selected, 'year'=>$year, 'currentmonth'=>$currentmonth, 'currentyear'=>$currentyear, 'currentmonthnum'=>$currentmonthnum, 'totalearnings'=>$thefinaltotalearnings, 'mode'=>$mode, 'selectedmonth'=>$selectedmonth, 'selectedyear'=>$selectedyear, 'staff'=>$staff,'link'=>$link]);
        }
        
        $mode = 1;

        $thismonth = MonthlyUnix::find()->where('Month = :m and Year = :y', [':m'=>$currentmonth, ':y'=>$currentyear])->one();

        $getorders = "SELECT Delivery_ID FROM ordersstatuschange WHERE OChange_CompletedDateTime BETWEEN ".$thismonth['FirstDay']." AND ".$thismonth['LastDay']."";
        $search = Yii::$app->db->createCommand($getorders)->queryAll();

        $totalearnings = 0;
        $thefinaltotalearnings = 0;

        foreach ($search as $search) :
            $deliveryid = $search['Delivery_ID'];

            $moneh = "SELECT orderitem.Restaurant_Share,orderitem.Order_ID FROM orderitem INNER JOIN orders on orders.Delivery_ID = orderitem.Delivery_ID INNER JOIN food on food.Food_ID = orderitem.Food_ID INNER JOIN restaurant on restaurant.Restaurant_ID = food.Restaurant_ID WHERE restaurant.Restaurant_ID = ".$rid." AND  orders.Delivery_ID = ".$deliveryid."";
            $moneh = Yii::$app->db->createCommand($moneh)->queryAll();
            $earnhere = 0;
            if (empty($moneh))
            {
                $earnings = 0;
            }
            else
            {
                foreach ($moneh as $money) :
                    $earnings = $money['Restaurant_Share'];
                    $earnhere = $earnings + $earnhere;
                endforeach;
            }

            $totalearnings = $totalearnings + $earnhere;

        endforeach;

        $thefinaltotalearnings = $totalearnings + $thefinaltotalearnings;
      

        
        return $this->render('restaurantearnings', ['rid'=>$rid , 'restaurant'=>$restaurant, 'restaurantname'=>$restaurantname, 'months'=>$months, 'selected'=>$selected, 'year'=>$year, 'currentmonth'=>$currentmonth, 'currentyear'=>$currentyear, 'currentmonthnum'=>$currentmonthnum, 'totalearnings'=>$thefinaltotalearnings, 'mode'=>$mode, 'staff'=>$staff,'link'=>$link]);
    }
}