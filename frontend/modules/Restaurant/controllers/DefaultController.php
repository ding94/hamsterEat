<?php

namespace frontend\modules\Restaurant\controllers;

use yii;
use yii\web\Controller;
use common\models\Restaurant;
use common\models\Food;
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


/**
 * Default controller for the `Restaurant` module
 */
class DefaultController extends Controller
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
                         'actions' => ['new-restaurant-location','new-restaurant-details','new-restaurant','edit-restaurant-details','edit-restaurant-area','edited-location-details','edit-restaurant-details2','manage-restaurant-staff','delete-restaurant-staff','add-staff','add-as-owner',
                        'add-as-manager', 'add-as-operator'],
                         'allow' => true,
                         'roles' => ['restaurant manager'],
 
                     ],
                     [
                        'actions' => ['index','restaurant-details','food-details'],
                        'allow' => true,
                        'roles' => ['?','@'],

                    ]
                 ]
             ]
        ];
    }

    public function actionIndex($groupArea)
    {
        //$aa = Yii::$app->request->get();
        $restaurant = restaurant::find()->where('Restaurant_AreaGroup = :group' ,[':group' => $groupArea])->all();

        return $this->render('index',['restaurant'=>$restaurant, 'groupArea'=>$groupArea]);
    }

    public function actionRestaurantDetails($rid)
    {
        $id = restaurant::find()->where('Restaurant_ID = :id' ,[':id' => $rid])->one();

        $rowfood = food::find()->where('Restaurant_ID=:id', [':id' => $rid])->all();

        return $this->render('RestaurantDetails',['id'=>$id, 'rowfood'=>$rowfood]);
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

        return $this->render('NewRestaurantLocation', ['postcode'=>$postcode ,'list'=>$list]);
    }
    
    public function actionNewRestaurantDetails()
    {
        $area = Yii::$app->request->post('Area');
        $postcodechosen = $area['Area_Postcode'];
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

                $restaurant->Restaurant_Tag = implode(',',$restaurant->Restaurant_Tag);
                
                $restaurant->save();

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
                return $this->render('NewRestaurant', ['restaurant' => $restaurant, 'restArea'=>$restArea, 'postcodechosen'=>$postcodechosen, 'areachosen'=>$areachosen]);
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

                $restaurantdetails->Restaurant_Tag = implode(',',$restaurantdetails->Restaurant_Tag);
                $restaurantdetails->Restaurant_Postcode = $postcodechosen;
                $restaurantdetails->Restaurant_Area = $areachosen;
                $restaurantdetails->Restaurant_AreaGroup = $restArea;

                 $isValid = $restaurantdetails->validate();
                if($isValid){
                    $restaurantdetails->save();

    
                Yii::$app->session->setFlash('success', "Update completed");
                return $this->redirect(['restaurant-details', 'rid'=>$rid]);
            
                }
                else{
                    Yii::$app->session->setFlash('warning', "Fail Update");
                }

        }
      
    //$this->view->title = 'Update Profile';
    //$this->layout = 'user';
    return $this->render('EditRestaurantDetails', ['restaurantdetails'=>$restaurantdetails, 'postcodechosen'=>$postcodechosen, 'areachosen'=>$areachosen, 'restArea'=>$restArea]);
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

        return $this->render('EditRestaurantLocation',['restaurantdetails'=>$restaurantdetails, 'postcode'=>$postcode ,'list'=>$list, 'rid'=>$rid]);
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
        $rstaff = rmanagerlevel::find()->where('Restaurant_ID = :rid',[':rid'=>$rid])->all();

        $id = restaurant::find()->where('Restaurant_ID = :rid',[':rid'=>$rid])->one();
        //var_dump($rstaff);exit;

        return $this->render('ManageRestaurantStaff',['rid'=>$rid, 'rstaff'=>$rstaff, 'id'=>$id]);
    }

    public function actionDeleteRestaurantStaff($rid, $uname)
    {
        $sql = "DELETE FROM rmanagerlevel WHERE User_Username = '$uname' AND Restaurant_ID = $rid";
        Yii::$app->db->createCommand($sql)->execute();
        $rid = $rid;
        $id = restaurant::find()->where('Restaurant_ID = :rid',[':rid'=>$rid])->one();
        $rstaff = rmanagerlevel::find()->where('Restaurant_ID = :rid',[':rid'=>$rid])->all();

        return $this->render('ManageRestaurantStaff',['rid'=>$rid, 'id'=>$id,'rstaff'=>$rstaff]);
    }

    public function actionAddStaff($rid, $num)
    {
        $search = user::find()->innerJoinWith('authAssignment','user.id = authAssignment.user_id')->where(['auth_assignment.item_name' => "restaurant manager"])->all();

        $rid = $rid;
        $num = $num;

        return $this->render('addstaff',['search'=>$search, 'rid'=>$rid, 'num'=>$num]);
    }

    public function actionAddAsOwner($rid, $uname)
    {
        $time = time();
        $sql = "INSERT INTO rmanagerlevel (User_Username, Restaurant_ID, RmanagerLevel_Level, Rmanager_DateTimeAdded) VALUES ('$uname', $rid, 'Owner', $time)";
        Yii::$app->db->createCommand($sql)->execute();

        $rid = $rid;

        return $this->redirect(['manage-restaurant-staff','rid'=>$rid]);
    }

    public function actionAddAsManager($rid, $uname)
    {
        $time = time();
        $sql = "INSERT INTO rmanagerlevel (User_Username, Restaurant_ID, RmanagerLevel_Level, Rmanager_DateTimeAdded) VALUES ('$uname', $rid, 'Manager', $time)";
        Yii::$app->db->createCommand($sql)->execute();

        $rid = $rid;

        return $this->redirect(['manage-restaurant-staff','rid'=>$rid]);
    }

    public function actionAddAsOperator($rid, $uname)
    {
        $time = time();
        $sql = "INSERT INTO rmanagerlevel (User_Username, Restaurant_ID, RmanagerLevel_Level, Rmanager_DateTimeAdded) VALUES ('$uname', $rid, 'Operator', $time)";
        Yii::$app->db->createCommand($sql)->execute();

        $rid = $rid;

        return $this->redirect(['manage-restaurant-staff','rid'=>$rid]);
    }

    public function actionShowByFood()
    {
        
    }
}
