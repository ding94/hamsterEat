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
                         'view-restaurant', 'all-rmanagers', 'show-monthly-earnings'],
                         'allow' => true,
                         'roles' => ['restaurant manager'],
 
                     ],
                    [
                        'actions' => ['index','show-by-food', 'food-filter', 'restaurant-filter','food-details','restaurant-details'],
                        'allow' => true,
                        'roles' => ['@','?'],

                    ],
                    
                 ]
             ]
        ];
    }

    public function actionIndex($groupArea)
    {
        //$aa = Yii::$app->request->get();
        $restaurant = restaurant::find()->where('Restaurant_AreaGroup = :group and Restaurant_Status = :status' ,[':group' => $groupArea, ':status'=>'Operating'])->innerJoinWith('restaurantType',true)->all();
        // var_dump($restaurant[0]['restaurantType'][0]);exit;
        $types = Restauranttype::find()->orderBy(['Type_Name'=>SORT_ASC])->all();
        $mode = 1;

        $search = new Food();

        if ($search->load(Yii::$app->request->post()))
        {
            $mode = 3;
            $keyword = $search->Nickname;
            $restaurant = restaurant::find()->where('Restaurant_AreaGroup = :group and Restaurant_Status = :status' ,[':group' => $groupArea, ':status'=>'Operating'])->andWhere(['like', 'Restaurant_Name', $keyword])->all();

            return $this->render('index',['restaurant'=>$restaurant, 'groupArea'=>$groupArea, 'types'=>$types, 'mode'=>$mode, 'search'=>$search, 'keyword'=>$keyword]);
        }

        return $this->render('index',['restaurant'=>$restaurant, 'groupArea'=>$groupArea, 'types'=>$types, 'mode'=>$mode, 'search'=>$search]);
    }

    public function actionRestaurantFilter($groupArea, $rfilter)
    {
        $restaurant = restaurant::find()->where('Restaurant_AreaGroup = :group and Restaurant_Status = :status and Type_ID = :tid' ,[':group' => $groupArea, ':status'=>'Operating', ':tid'=>$rfilter])->innerJoinWith('restaurantType',true)->all();

        $types = Restauranttype::find()->orderBy(['Type_Name'=>SORT_ASC])->all();

        $mode = 2;

        $search = new Food();

        if ($search->load(Yii::$app->request->post()))
        {
            $mode = 4;
            $keyword = $search->Nickname;
            $restaurant = restaurant::find()->where('Restaurant_AreaGroup = :group and Restaurant_Status = :status and Type_ID = :tid' ,[':group' => $groupArea, ':status'=>'Operating', ':tid'=>$rfilter])->andWhere(['like', 'Restaurant_Name', $keyword])->innerJoinWith('restaurantType',true)->all();

            return $this->render('index',['restaurant'=>$restaurant, 'groupArea'=>$groupArea, 'types'=>$types, 'mode'=>$mode, 'search'=>$search, 'keyword'=>$keyword, 'rfilter'=>$rfilter]);
        }

        return $this->render('index',['restaurant'=>$restaurant, 'groupArea'=>$groupArea, 'types'=>$types, 'rfilter'=>$rfilter, 'mode'=>$mode, 'search'=>$search]);
    }

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

        $model = food::find()->where('Restaurant_ID=:id and Status = :status', [':id' => $rid, ':status'=> 1])->andWhere(["!=","foodtypejunction.Type_ID",5])->innerJoinWith('foodType',true)->innerJoinWith('foodStatus',true);
        
        if (!empty($rmanager)) {
           $model = food::find()->where('Restaurant_ID=:id', [':id' => $rid])->andWhere(["!=","foodtypejunction.Type_ID",5])->innerJoinWith('foodType',true)->innerJoinWith('foodStatus',true);
        }
        
        //$countmodel = food::find()->where('Restaurant_ID=:id and Status = :status', [':id' => $rid, ':status'=> 1])->andWhere(["!=","foodtypejunction.Type_ID",5])->innerJoinWith('foodType',true);
        // $countmodel = "SELECT DISTINCT food.Food_ID FROM food INNER JOIN foodstatus ON foodstatus.Food_ID = food.Food_ID WHERE food.Restaurant_ID = ".$rid." AND foodstatus.Status = ".true."";
        // $resultcountmodel = Yii::$app->db->createCommand($countmodel)->execute();
        // $rowfood = $model->all();
        // var_dump($model->count());exit;
        // var_dump($countmodel->count());exit;
        // $pagination = new Pagination(['totalCount'=>$resultcountmodel,'pageSize'=>10]);
        // var_dump($resultcountmodel);exit;
        // $rowfood = $model->offset($pagination->offset)
        // ->limit($pagination->limit)
        // ->all();
        $rowfood = $model->all();

        if (!(Yii::$app->user->isGuest)) {
        $staff = Rmanagerlevel::find()->where('User_Username = :uname and Restaurant_ID = :id', [':uname'=>Yii::$app->user->identity->username, ':id'=>$rid])->one();
        // return $this->render('restaurantdetails',['id'=>$id, 'rowfood'=>$rowfood, 'staff'=>$staff,'pagination'=>$pagination, 'rid'=>$rid]);
        return $this->render('restaurantdetails',['id'=>$id, 'rowfood'=>$rowfood, 'staff'=>$staff,'rid'=>$rid]);
        }
        // return $this->render('restaurantdetails',['id'=>$id, 'rowfood'=>$rowfood,'pagination'=>$pagination, 'rid'=>$rid]);
        return $this->render('restaurantdetails',['id'=>$id, 'rowfood'=>$rowfood,'rid'=>$rid]);
    }

    public function actionFoodDetails($fid)
    {
        $fooddata = food::find()->where('Food_ID=:id',[':id'=>$fid])->one();
        $foodid = $fooddata['Food_ID'];

        return $this->redirect(['/food/food-details', 'id'=>$foodid]);
    }

    public function actionNewRestaurantLocation()
    {
        $postcode = new Area();
        $list =array();
        $postcode->detectArea = 0;
        if(Yii::$app->request->isPost)
        {
            $postcode->detectArea = 1;
            $area = Yii::$app->request->post('Area');
            $postcode->Area_Postcode = $area['Area_Postcode'];
            $dataArea = Area::find()->where(['like','Area_Postcode' , $area['Area_Postcode']])->all();
            $list = ArrayHelper::map($dataArea,'Area_Area' ,'Area_Area');
            
            if(empty($list)) {
                $postcode->detectArea = 0;
                Yii::$app->session->setFlash('error', 'There is no available area under that postcode.');
            }
        }

        return $this->render('newrestaurantlocation', ['postcode'=>$postcode ,'list'=>$list]);
    }
    
    public function actionNewRestaurantDetails()
    {
        $area = Yii::$app->request->post('Area');
        $pcode = Area::find()->where('Area_Area = :area', [':area'=>$area['Area_Area']])->one();
        $postcodechosen = $pcode['Area_Postcode'];
        $areachosen = $area['Area_Area'];
        $restArea = Area::find()->where('Area_Postcode = :area_postcode and Area_Area = :area_area',[':area_postcode'=> $area['Area_Postcode'] , ':area_area'=>$area['Area_Area']])->one();        
        $restArea = $restArea['Area_Group'];

        return $this->actionNewRestaurant($restArea, $postcodechosen, $areachosen);
    }

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

    public function actionEditRestaurantDetails($rid, $postcodechosen, $areachosen, $restArea)
    {
        $upload = new Upload();
        //$path = Yii::$app->request->baseUrl.'/imageLocation/';

        $restaurantdetails = restaurant::find()->where('Restaurant_ID = :rid'  , [':rid' => $rid])->one();
        $rpicpath = $restaurantdetails['Restaurant_RestaurantPicPath'];
        $restArea = $restArea;
        $postcodechosen = $postcodechosen;
        $areachosen = $areachosen;
        $restaurant = Restaurant::find()->where(Restaurant::tableName().'.Restaurant_ID = :id' ,[':id' => $rid])->innerJoinWith('restaurantType',true)->one();
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
    return $this->render('editrestaurantdetails', ['restaurantdetails'=>$restaurantdetails, 'postcodechosen'=>$postcodechosen, 'areachosen'=>$areachosen, 'restArea'=>$restArea, 'chosen'=>$chosen, 'type'=>$type]);
    }

    public function actionEditRestaurantArea($rid)
    {
        $restaurantdetails = restaurant::find()->where('Restaurant_ID = :rid'  , [':rid' => $rid])->one();
        $rid = $restaurantdetails['Restaurant_ID'];
        $postcode = new Area();
        $list =array();
        $postcode->detectArea = 0;
        if(Yii::$app->request->isPost)
        {
            $postcode->detectArea = 1;
            $area = Yii::$app->request->post('Area');
            $postcode->Area_Postcode = $area['Area_Postcode'];
            $dataArea = Area::find()->where(['like','Area_Postcode' , $area['Area_Postcode']])->all();
            $list = ArrayHelper::map($dataArea,'Area_Area' ,'Area_Area');
            
            if(empty($list)) {
                $postcode->detectArea = 0;
                Yii::$app->session->setFlash('error', 'There is no available area under that postcode.');
            }
        }

        return $this->render('editrestaurantlocation',['restaurantdetails'=>$restaurantdetails, 'postcode'=>$postcode ,'list'=>$list, 'rid'=>$rid]);
    }

    public function actionEditedLocationDetails($rid)
    {
        $area = Yii::$app->request->post('Area');
        $postcodechosen = $area['Area_Postcode'];
        $areachosen = $area['Area_Area'];
        $restArea = Area::find()->where('Area_Postcode = :area_postcode and Area_Area = :area_area',[':area_postcode'=> $area['Area_Postcode'] , ':area_area'=>$area['Area_Area']])->one();        
        $restArea = $restArea['Area_Group'];
        $rid = $rid;
        //var_dump($restArea,$areachosen,$postcodechosen,$rid);exit;

        return $this->actionEditRestaurantDetails2($restArea, $postcodechosen, $areachosen, $rid);
    }

    public function actionEditRestaurantDetails2($restArea, $postcodechosen, $areachosen, $rid)
    {
        $restArea = $restArea;
        $postcodechosen = $postcodechosen;
        $areachosen = $areachosen;
        $rid = $rid;
        //var_dump($restArea,$areachosen,$postcodechosen,$rid);exit;

        return $this->redirect(['edit-restaurant-details', 'restArea'=>$restArea, 'postcodechosen'=>$postcodechosen, 'rid'=>$rid, 'areachosen'=>$areachosen]);
    }

    public function actionManageRestaurantStaff($rid)
    {
        $rid = $rid;
        $rstaff = Rmanagerlevel::find()->where('Restaurant_ID = :rid',[':rid'=>$rid])->all();

        $id = Restaurant::find()->where('Restaurant_ID = :rid',[':rid'=>$rid])->one();

        $me = Rmanagerlevel::find()->where('Restaurant_ID = :rid and User_Username = :uname', [':rid'=>$rid, ':uname'=>Yii::$app->user->identity->username])->one();
        //var_dump($rstaff);exit;

        return $this->render('managerestaurantstaff',['rid'=>$rid, 'rstaff'=>$rstaff, 'id'=>$id, 'me'=>$me]);
    }

    public function actionDeleteRestaurantStaff($rid, $uname)
    {
        $sql = "DELETE FROM rmanagerlevel WHERE User_Username = '$uname' AND Restaurant_ID = $rid";
        Yii::$app->db->createCommand($sql)->execute();
        $rid = $rid;
        $id = restaurant::find()->where('Restaurant_ID = :rid',[':rid'=>$rid])->one();
        $rstaff = rmanagerlevel::find()->where('Restaurant_ID = :rid',[':rid'=>$rid])->all();

        return $this->render('managerestaurantstaff',['rid'=>$rid, 'id'=>$id,'rstaff'=>$rstaff]);
    }

    public function actionAllRmanagers($rid, $num)
    {
        $allrmanagers = user::find()->innerJoinWith('authAssignment','user.id = authAssignment.user_id')->where(['auth_assignment.item_name' => "restaurant manager"])->all();

        $rid = $rid;
        $num = $num;

        $food = new Food;

        $keyword = '';

        if ($food->load(Yii::$app->request->post()))
        {
            $keyword = $food->Nickname;

            $allrmanagers = user::find()->innerJoinWith('authAssignment','user.id = authAssignment.user_id')->where(['auth_assignment.item_name' => "restaurant manager"])->andWhere(['like', 'user.username', $keyword])->all();

            return $this->render('allrmanagers',['allrmanagers'=>$allrmanagers, 'rid'=>$rid, 'num'=>$num, 'food'=>$food, 'keyword'=>$keyword]);
            
        }

        return $this->render('allrmanagers',['allrmanagers'=>$allrmanagers, 'rid'=>$rid, 'num'=>$num, 'food'=>$food, 'keyword'=>$keyword]);
    }

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

    public function actionShowByFood($groupArea)
    {
        $restaurant = Restaurant::find()->where('Restaurant_AreaGroup = :group' ,[':group' => $groupArea])->all();
        $types = Foodtype::find()->orderBy(['Type_Desc'=>SORT_ASC])->all();
        $mode = 1;

        $search = new Food();
        if ($search->load(Yii::$app->request->post()))
        {
            $mode = 3;
            $keyword = $search->Nickname;

            return $this->render('index2',['restaurant'=>$restaurant, 'groupArea'=>$groupArea, 'types'=>$types, 'mode'=>$mode, 'search'=>$search, 'keyword'=>$keyword]);
        }

        //var_dump($types);exit;
        return $this->render('index2',['restaurant'=>$restaurant, 'groupArea'=>$groupArea, 'types'=>$types, 'mode'=>$mode, 'search'=>$search]);
    }

    public function actionFoodFilter($groupArea,$typefilter)
    {
        $restaurant = Restaurant::find()->where('Restaurant_AreaGroup = :group' ,[':group' => $groupArea])->all();
        $mode = 2;
        $types = Foodtype::find()->orderBy(['Type_Desc'=>SORT_ASC])->all();
        $type = $typefilter;
        $search = new Food();

        if ($search->load(Yii::$app->request->post()))
        {
            $mode = 4;
            $keyword = $search->Nickname;

            return $this->render('index2',['restaurant'=>$restaurant, 'groupArea'=>$groupArea, 'types'=>$types, 'mode'=>$mode, 'search'=>$search, 'keyword'=>$keyword, 'filter'=>$type]);
        }

        return $this->render('index2',['restaurant'=>$restaurant, 'groupArea'=>$groupArea, 'types'=>$types, 'mode'=>$mode, 'filter'=>$type, 'search'=>$search]);
    }

    public function actionSearchFood($groupArea, $keyword)
    {
        $restaurant = Restaurant::find()->where('Restaurant_AreaGroup = :group' ,[':group' => $groupArea])->all();
        $mode = 3;
        $types = Foodtype::find()->orderBy(['Type_Desc'=>SORT_ASC])->all();

        return $this->render('index2',['restaurant'=>$restaurant, 'groupArea'=>$groupArea, 'types'=>$types, 'mode'=>$mode, 'keyword'=>$keyword]);
    }

    public function actionViewRestaurant()
    {
		//$uname = user::find()->where('username = :uname',[':uname' => Yii::$app->user->identity->username])->one();
        $restaurants = Rmanagerlevel::find()->where('User_Username = :uid',[':uid' => Yii::$app->user->identity->username])->all();
        $this->layout = '@app/views/layouts/user';
        //var_dump($restaurant);exit;
        return $this->render('ViewRestaurant',['restaurants'=>$restaurants]);
    }

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

    public function actionShowMonthlyEarnings($rid)
    {
        $restaurantname = Restaurant::find()->where('Restaurant_ID = :rid', [':rid' => $rid])->one();
        $restaurantname = $restaurantname['Restaurant_Name'];

        $currentmonth = date('n');
        $currentyear = date('Y');
        //var_dump(time());exit;

        return $this->render('restaurantearnings', ['rid'=>$rid , 'restaurantname'=>$restaurantname]);
    }
}