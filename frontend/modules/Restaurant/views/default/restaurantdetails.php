<?php
use yii\helpers\Html;
use yii\helpers\Url;
use yii\bootstrap\Modal;
use common\models\Rmanagerlevel;
use frontend\assets\StarsAsset;
use frontend\assets\RestaurantDetailsAsset;
$this->title = $id['Restaurant_Name'];

StarsAsset::register($this);
RestaurantDetailsAsset::register($this);
?>

<?php Modal::begin([
            'id'     => 'foodDetail',
            'size'   => 'modal-lg',
            //'footer' => '<a href="#" class="btn btn-primary" data-dismiss="modal">Close</a>',
    ]);
    
    Modal::end() ?>
   
  <?php Modal::begin([
            'header' => '<h2 class="modal-title">Report</h2>',
            'id'     => 'report-modal',
            'size'   => 'modal-sm',
            'footer' => '<a href="#" class="raised-btn alternative-btn" data-dismiss="modal">Close</a>',
    ]);
    
    Modal::end() ?>
<div class = "container">
 <!--<a class="back" href="../web/index.php?r=ticket%2Findex"><i class="fa fa-angle-left">&nbsp;Back</i></a><br>-->
  
<a href="#top" class="scrollToTop"></a>
  <div class="restaurant-info-container">
    <?php $picpath = $id['Restaurant_RestaurantPicPath'];

        if (is_null($id['Restaurant_RestaurantPicPath'])){
            $picpath = "DefaultRestaurant.jpg";
        }
        ?>
        <div class="restaurant-img-div">
        <?php echo Html::a("Back" ,Yii::$app->request->referrer,['class'=>'raised-btn secondary-btn','id'=>'back'])?>
        <?php 
         echo Html::img('@web/imageLocation/'.$picpath, ['class' => 'restaurant-img']) 
         ?>
        </div> 
        <div class="restaurant-info-inner">
        <div class="restaurant-name-div"><h1 class="restaurant-name"><?php echo $id['Restaurant_Name']; ?></h1><span class="report-button"><?php echo Html::a('Report', Url::to(['/report/report-restaurant' ,'name'=>$id['Restaurant_Name']]), ['class'=>'raised-btn secondary-btn','data-toggle'=>'modal','data-target'=>'#report-modal']) ?></span></div>
        <div class="rating"><span class="small-text stars"><?php echo $id['Restaurant_Rating']; ?></span></div>
        <div class="info-div">
          <ul class="info">
            <?php if ($id['Restaurant_Pricing'] == 1){ ?>
            <li>$</li>
            <?php } else if ($id['Restaurant_Pricing'] == 2){ ?>
            <li> $ $ </li>
            <?php } else { ?>
            <li> $ $ $ </li>
            <?php } 
              foreach ($id['restaurantType'] as $type):
            ?>
            <li>
              <?php echo $type['Type_Name'] ?>
            </li>
            <?php endforeach; ?>
            <li class="none"><?php echo $id['Restaurant_UnitNo'].", ".$id['Restaurant_Street'].", ".$id['Restaurant_Area'].", ".$id['Restaurant_Postcode']; ?></li>
          </ul>
        </div>
    <?php if(!empty($staff)) : ?>
            <div id="button-container">
                <span><?php echo Html::a('Manage Restaurant', Url::to(['/Restaurant/default/manage-restaurant-staff' ,'rid'=>$id['Restaurant_ID']]), ['class'=>'resize-btn raised-btn main-btn']) ?></span>
                <span> <?php if ($id['Restaurant_Status'] == "Closed"): ?>
                    <?=Html::a('Resume Resturant Operate', Url::to(['restaurant/active', 'id'=>$id['Restaurant_ID'],'item'=>1]), ['id'=>'resume','data-confirm'=>"Do you want to Resume Operate?",'class'=>'resize-btn raised-btn btn-success'])?>
                    <?php elseif($id['Restaurant_Status'] == "Operating"): ?>
                    <?=Html::a('Pause Resturant Operate', Url::to(['restaurant/pauserestaurant', 'id'=>$id['Restaurant_ID'],'item'=>1]), ['id'=>'pause','data-confirm'=>"Do you want to Pause Operate?",'class'=>'resize-btn raised-btn btn-danger'])?>  
                <?php endif ?></span>
            </div>
        <?php endif ?>
         </div>
    </div>
    <br>
    <hr class="restaurantdetails-hr">
    <h2><center>Menu</h2>
    
    <div class = "foodItems">
    </div>
    
    <?php
      $rid = $id['Restaurant_ID'];
      $id = isset($_GET['foodid']) ? $_GET['foodid'] : ''; 
    ?>
    <div class="outer-container">
    <div class="menu-container" id="menu-container">
            <?php
              foreach($rowfood as $data): 
            ?>
        <a href="<?php echo yii\helpers\Url::to(['/food/food-details','id'=>$data['Food_ID'],'rid'=>$rid]); ?>"  class ="food-link" data-toggle="modal" data-target="#foodDetail" data-img="<?php echo $data['PicPath'];?> ">
        <div class="item">
            <div class="img"><?php echo Html::img('@web/imageLocation/foodImg/'.$data['PicPath']) ?></div>
            <div class="inner-item">
            <div class="foodName-div"><span class="foodName"><?php echo $data['Name']; ?></span><span class="small-text stars" alt="<?php echo $data['Rating']; ?>"><?php echo $data['Rating']; ?></span></div>
            <!-- <div class="stars-div"></div> -->
            <div class="price-div"><span class="price"><?php echo 'RM'.$data['Price']; ?></span></div>
            <div class="foodDesc-div"><span class="foodDesc"><?php echo $data['Description']; ?></span></div>
            <div class="tag-div">
            <?php foreach($data['foodType']as $type): ?>
            <span class="tag"><?php echo $type['Type_Desc'].'&nbsp;&nbsp;&nbsp;'; ?></span>
            <?php endforeach; ?>
            </div>
            </div>
        </div>
        </a>
        <?php endforeach; ?>
    </div>
    </div>
    <div class="container">
    <?php echo \yii\widgets\LinkPager::widget([
      'pagination' => $pagination,
    ]); ?>
    </div>
</div>