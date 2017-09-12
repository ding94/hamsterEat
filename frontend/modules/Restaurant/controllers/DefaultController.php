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

/**
 * Default controller for the `Restaurant` module
 */
class DefaultController extends Controller
{
    /**
     * Renders the index view for the module
     * @return string
     */
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

        return $this->redirect(['/food/food-details', 'foodid'=>$foodid]);
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
        $path = Yii::$app->request->baseUrl.'/imageLocation/';

        if ($restaurant->load(Yii::$app->request->post()) && $restaurant->save())
            {
                $post = Yii::$app->request->post();

                $upload->imageFile =  UploadedFile::getInstance($restaurant, 'Restaurant_RestaurantPicPath');
                $upload->imageFile->name = time().'.'.$upload->imageFile->extension;
               // $post['User_PicPath'] = 
                $upload->upload();
                
                $restaurant->load($post);
            
                $restaurant->Restaurant_RestaurantPicPath = $upload->imageFile->name;
                $restaurant->Restaurant_Manager=Yii::$app->user->identity->username;

                $restaurant->Restaurant_AreaGroup = Yii::$app->request->post('restArea');
                $restaurant->Restaurant_Postcode = Yii::$app->request->post('postcodechosen');
                $restaurant->Restaurant_Area = Yii::$app->request->post('areachosen');
                $restaurant->Restaurant_DateTimeCreated = time();
                $restaurant->Restaurant_Status = 'Under Renovation';
                $restaurant->Restaurant_Rating = "0";
                $restaurant->save();

                $rid = Yii::$app->request->get('Restaurant_ID');
                $asd = restaurant::find()->where(['Restaurant_ID'=>$rid])->one();
                $rmanager = new Rmanager();
                $rmanager->Restaurant_ID=$asd->Restaurant_ID;

                $rmanager->save();
                
                Yii::$app->session->setFlash('success', 'Congratulations! Your restaurant has been set up and is currently waiting for a menu to be set up.');
                
                return $this->redirect(['/site/index', 'restaurant'=> $restaurant, 'restArea'=>Yii::$app->request->post('restArea'), 'postcodechosen'=>Yii::$app->request->post('postcodechosen'), 'areachosen'=>Yii::$app->request->post('areachosen')]);           
            }
        else 
            {
                return $this->render('NewRestaurant', ['restaurant' => $restaurant, 'restArea'=>$restArea, 'postcodechosen'=>$postcodechosen, 'areachosen'=>$areachosen]);
            }
    }

}
