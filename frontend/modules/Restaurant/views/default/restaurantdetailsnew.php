<?php
use yii\helpers\Html;
use yii\helpers\Url;
use yii\bootstrap\Modal;
use frontend\assets\StarsAsset;
use frontend\assets\RestaurantDetailsAsset;
use frontend\controllers\CartController;

$this->title = $id['Restaurant_Name'];

StarsAsset::register($this);
RestaurantDetailsAsset::register($this);


date_default_timezone_set("Asia/Kuala_Lumpur");
?>
<?php Modal::begin([
            'id'     => 'foodDetail',
            'size'   => 'modal-lg',
            //'footer' => '<a href="#" class="btn btn-primary" data-dismiss="modal">Close</a>',
    ]);
    
    Modal::end() ?>
   
  <?php Modal::begin([
            'header' => '<h2 class="modal-title">'.Yii::t('common','Report').'</h2>',
            'id'     => 'report-modal',
            'size'   => 'modal-sm',
            'footer' => '<a href="#" class="raised-btn alternative-btn" data-dismiss="modal">'.Yii::t('common','Close').'</a>',
    ]);
    
    Modal::end() ?>
<div class = "container">
 <!--<a class="back" href="../web/index.php?r=ticket%2Findex"><i class="fa fa-angle-left">&nbsp;Back</i></a><br>-->
  
<a href="#top" class="scrollToTop"></a>
  <div class="restaurant-info-container">
        <div class="restaurant-img-div">
        <?php echo Html::a(Yii::t('common',"Back") ,Yii::$app->request->referrer,['class'=>'raised-btn secondary-btn','id'=>'back'])?>
        <?php echo Html::img($id->img, ['class' => 'restaurant-img']) ?>
        </div> 
        <div class="restaurant-info-inner">
        <div class="restaurant-name-div"><h1 class="restaurant-name"><?php echo $id['Restaurant_Name']; ?></h1>
        <?php if(!Yii::$app->user->isGuest):?>
        <span class="report-button"><?php echo Html::a(Yii::t('common','Report'), Url::to(['/report/report-restaurant' ,'name'=>$id['Restaurant_Name']]), ['class'=>'raised-btn secondary-btn','data-toggle'=>'modal','data-target'=>'#report-modal']) ?>
        <?php endif ;?>
        </span>
    </div>
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
         </div>
    </div>
    <br>
<!--     <hr class="restaurantdetails-hr"> -->
    <div id="category-bar">
        <ul class="container">
        <?php
        $count=0; 
        foreach($allfoodtype as $foodtype):
            $count+=1;
        ?>
            <a src="#" class="scroll-link" data-id="section<?php echo $count ?>"><li><?php echo $foodtype['name']; ?></li></a>
        <?php endforeach; ?>
        </ul>
    </div>

    <?php
    $k = 0; 
    foreach($allfoodtype as $sft): 
        $k+=1;
    ?>
    <div style="margin-top: 100px;" id="section<?php echo $k;?>" class="foodtype">
        <div class="foodtype-name">
            <h1><?php echo $sft['name']; ?></h1>
        </div>
        <div class="outer-container">
        <div class="menu-container" id="menu-container">
        <?php 
            foreach($rowfood as $newdata):
                foreach($newdata['junction'] as $datatype):
                    if ($datatype['Type_ID']==$sft['id']) { ?>
                        <?php $imgdata =  $newdata->multipleImg?>
        <a href="<?php echo yii\helpers\Url::to(['/food/food-details','id'=>$newdata['Food_ID'],'rid'=>$rid]); ?>"  class ="food-link" data-toggle="modal" data-target="#foodDetail" data-img= <?php echo json_encode($imgdata) ?>>
        <div class="item">
            <div class="img">
                <?php if (time() < strtotime(date("Y/m/d 11:0:0"))):?>
                    <div class="corner-ribbon top-left sticky red shadow"><span>-15%</span></div>
                <?php endif; ?>
                <img src=<?php echo $newdata->singleImg?> alt="">
            </div>
            <div class="inner-item">
            <div class="foodName-div"><span class="foodName"><?php echo $newdata['cookieName']; ?></span><span class="small-text stars" alt="<?php echo $newdata['Rating']; ?>"><?php echo $newdata['Rating']; ?></span></div>
            <!-- <div class="stars-div"></div> -->
            <div class="price-div">
                <?php if (time() < strtotime(date("Y/m/d 11:0:0"))):?>
                    <span class="price"><strike><?php echo 'RM'.$newdata['Price']; ?></strike>        <?php $newdata['Price']=$newdata['Price']*0.85;$newdata['Price'] = CartController::actionRoundoff1decimal($newdata['Price']); echo 'RM'.number_format($newdata['Price'],2); ?></span>
                <?php else: ?>
                    <span class="price"><?php echo 'RM'.$newdata['Price']; ?></span>
                <?php endif;?>
            </div>
            <div class="foodDesc-div"><span class="foodDesc"><?php echo $newdata['Description']; ?></span></div>
            </div>
        </div>
        </a>
        <?php            
                    }
        ?>
            <?php endforeach; ?>    
        <?php endforeach; ?>
        </div>
        </div>
    </div>
    <?php endforeach; ?>
</div>