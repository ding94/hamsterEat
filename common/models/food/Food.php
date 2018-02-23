<?php

namespace common\models\food;

use Yii;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;
use frontend\controllers\CartController;
use common\models\Restaurant;
use common\models\Rmanagerlevel;
use common\models\Order\Orderitem;
use yii\helpers\Url;
// use common\models\food\FoodChangeLog;

/**
 * This is the model class for table "food".
 *
 * @property integer $Food_ID
 * @property integer $Restaurant_ID
 * @property double $Rating
 * @property integer $Sales
 * @property string $Name
 * @property double $Price
 * @property double $BeforeMarkedUp
 * @property string $Description
 * @property string $Ingredient
 * @property string $Nickname
 * @property string $PicPath
 * @property integer $created_at
 * @property integer $updated_at
 */
class Food extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public $foodPackage = 0;
    public $zhName;
    public $enName;

    public static function tableName()
    {
        return 'food';
    }

    public function behaviors()
    {
        return [
            TimestampBehavior::className(),
            'timestamp' => [
                'class' => 'yii\behaviors\TimestampBehavior',
                'attributes' => [
                    ActiveRecord::EVENT_BEFORE_INSERT => ['created_at','updated_at'],
                    ActiveRecord::EVENT_BEFORE_UPDATE => ['updated_at'],
                ],
            ],
        ];
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['Restaurant_ID'], 'required'],
            [['Description'],'required','message'=>Yii::t('common','description').Yii::t('common',' cannot be blank.')],
            [['Ingredient'],'required','message'=>Yii::t('common','description').Yii::t('common',' cannot be blank.')],
            [['Restaurant_ID', 'Sales', 'created_at', 'updated_at'], 'integer'],
            [['Rating', 'Price', 'BeforeMarkedUp'], 'number'],
            [['Description', 'Ingredient', 'Nickname'], 'string'],
            [['enName','zhName'],'string'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'Food_ID' => 'Food ID',
            'Restaurant_ID' => 'Restaurant ID',
            'Rating' => 'Rating',
            'Sales' => 'Sales',
            'Price' => 'Price',
            'BeforeMarkedUp' => 'Before Marked Up',
            'Description' => 'Description',
            'Ingredient' => 'Ingredient',
            'Nickname' => 'Nickname',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
            'enName' => 'English Name',
            'zhName' => 'Chinese Name',
        ];
    }

    public function getFoodType()
    {
        return $this->hasMany(Foodtype::className(), ['ID'=>'Type_ID'])->viaTable('foodtypejunction', ['Food_ID'=>'Food_ID']);
    }

    public function getJunction()
    {
        return $this->hasMany(Foodtypejunction::className(),['Food_ID' => 'Food_ID']);
    }

    public function getFoodStatus()
    {
        return $this->hasOne(Foodstatus::className(), ['Food_ID'=>'Food_ID']);
    }
   
    public function getFoodselectiontypes()
    {
        return $this->hasMany(Foodselectiontype::className(),['Food_ID' => 'Food_ID']);
    }

    public function getFoodSelection()
    {
        return $this->hasMany(Foodselection::className(),['Food_ID'=> 'Food_ID']);
    }

    public function getOrderitem()
    {
        return $this->hasMany(Orderitem::className(),['Food_ID' => 'Food_ID']);
    }

    public function getSelectedtpye()
    {
        return $this->hasOne(Foodselectiontype::className(),['ID' => $this->foodSelection->Type_ID]);
    }

    public function getRestaurant()
    {
        return $this->hasOne(Restaurant::className(),['Restaurant_ID' => 'Restaurant_ID']);
    }

    public function getRJunction()
    {
        return $this->hasMany(Restauranttypejunction::className(),['Restaurant_ID' => $this->restaurant->Restaurant_ID]);
    }

    public function getManager()
    {
        return $this->hasMany(Rmanagerlevel::className(),['Restaurant_ID' => 'Restaurant_ID']);
    }

    public function getRoundprice()
    {
        return CartController::actionRoundoff1decimal($this->BeforeMarkedUp);
    }

    public function getTransName()
    {
        return $this->hasOne(FoodName::className(),['id'=>'Food_ID'])->andOnCondition(['language' => 'en']);
    }

    public function getOriginName()
    {
        $data = FoodName::find()->where("id = :id and language = 'en'",[':id'=>$this->Food_ID])->one();
        return $data->translation;
    }

    public function getCookieName()
    {
        $cookies = Yii::$app->request->cookies;
        $language = $cookies->getValue('language', 'value');
        $data = FoodName::find()->where("id = :id and language = :l",[':id'=>$this->Food_ID,':l'=>$language])->one();
        if(empty($data))
        {
           return $this->getOriginName();
        }
        return $data->translation;
    }

    /*
    * use only for upload image
    */
    public function getImg()
    {
        $data = "";
        $images = FoodImg::find()->where('fid = :id',[':id'=>$this->Food_ID])->all();
        foreach($images as $image)
        {
            $data[] =  Yii::getAlias('@web').'/'.Yii::$app->params['foodImg'].$image->img;
        }
        if(empty($data))
        {
            $data[] = Yii::$app->params['defaultFoodImg'];
        }
        return $data;
    }

    /*
    * use only for upload image
    */
    public function getCaptionImg()
    {
        $data = "";
        $images = FoodImg::find()->where('fid = :id',[':id'=>$this->Food_ID])->all();

        if(empty($images))
        {
            $data['data'][0]['caption'] = Yii::$app->params['defaultFoodImg'];
            $data['data'][0]['key'] = "0";
            $data['header'] = true;

        }
        else
        {
            foreach($images as $i=>$image)
            {
                $data['data'][$i]['caption'] =  $image->img;
                $data['data'][$i]['url'] = Url::to(['/food-img/delete','id'=>$image->id]);
                $data['data'][$i]['key'] = $image->id;

            }
            $data['header'] = false;

        }
        
        
        return $data;
    }

    /*
    * show single image 
    */
    public function getSingleImg()
    {
        $images = FoodImg::find()->where('fid = :id',[':id'=>$this->Food_ID])->all();
        foreach($images as $image)
        {
            if(file_exists(Yii::$app->params['foodImg'].$image->img))
            {

                return Yii::getAlias('@web').'/'.Yii::$app->params['foodImg'].$image->img;
            }
        }

        return  Yii::$app->params['defaultFoodImg'];
    }

    /*
    * show multiple image 
    */
    public function getMultipleImg()
    {
        //$data [] ="";
        $images = FoodImg::find()->where('fid = :id',[':id'=>$this->Food_ID])->all();
        foreach($images as $image)
        {
            if(file_exists(Yii::$app->params['foodImg'].$image->img))
            {
                $data[] = Yii::getAlias('@web').'/'.Yii::$app->params['foodImg'].$image->img;
                //return Yii::getAlias('@web').'/'.Yii::$app->params['foodImg'].$image->img;
            }
        }

        if(empty($data))
        {
           
           $data[] = Yii::$app->params['defaultFoodImg'];
          
        }
        
        return $data; 
        
    }

    // public function afterSave($insert,$changedAttributes)
    // {
    //     if($insert == false){
    //         $fid = $this->Food_ID;
    //         $rid = $this->Restaurant_ID;

    //         foreach ($changedAttributes as $name => $value) {
    //             if($name != 'updated_at'){
    //                 if($name == 'Price' || $name == 'BeforeMarkedUp'){
    //                     if($value != $this->$name){
    //                         $log = new FoodChangeLog;
    //                         $log->fid = $fid;
    //                         $log->rid = $rid;
    //                         $log->created_at = new \yii\db\Expression('NOW()');
    //                         $log->description = $name .' changed from ' . $value . ' to ' . $this->$name;
    //                         $log->save();
    //                     }
    //                 } else {
    //                     $log = new FoodChangeLog;
    //                     $log->fid = $fid;
    //                     $log->rid = $rid;
    //                     $log->created_at = new \yii\db\Expression('NOW()');
    //                     $log->description = $name .' changed from ' . $value . ' to ' . $this->$name;
    //                     $log->save();
    //                 }
    //             }

    //         }
    //     }
    // }
}
