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
<body>

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
            'footer' => '<a href="#" class="btn btn-primary" data-dismiss="modal">Close</a>',
    ]);
    
    Modal::end() ?>
<div class = "container">
  <div class="restaurant-info-container">
    <?php $picpath = $id['Restaurant_RestaurantPicPath'];

        if (is_null($id['Restaurant_RestaurantPicPath'])){
            $picpath = "DefaultRestaurant.jpg";
        }
         echo Html::img('@web/imageLocation/'.$picpath, ['class' => 'img-responsive pull-left', 'style'=>'height:250px; width:350px; margin:auto;']) ?> <?php echo "</th>"; ?>
    <h1><?php echo $id['Restaurant_Name']; ?><span class="pull-right"><?php echo Html::a('Report', Url::to(['/report/report-restaurant' ,'name'=>$id['Restaurant_Name']]), ['class'=>'btn btn-primary','data-toggle'=>'modal','data-target'=>'#report-modal']) ?></span></h1>
      <ul class="info">
        <?php if ($id['Restaurant_Pricing'] == 1){ ?>
        <li class="none">$</li>
        <?php } else if ($id['Restaurant_Pricing'] == 2){ ?>
        <li class= "none"> $ $ </li>
        <?php } else { ?>
        <li class= "none"> $ $ $ </li>
        <?php } 
          foreach ($id['restaurantType'] as $type):
        ?>
        <li>
          <?php echo $type['Type_Name'] ?>
        </li>
        <?php endforeach; ?>
        <li><?php echo $id['Restaurant_UnitNo'].", ".$id['Restaurant_Street'].", ".$id['Restaurant_Area'].", ".$id['Restaurant_Postcode']; ?></li>
      </ul>
      <div class="rating">
        <span class="small-text stars"><?php echo $id['Restaurant_Rating']; ?></span>
        <?php if(!empty($staff)) : ?>
            <div  style="float: right;">
            <span><?php echo Html::a('Manage Restaurant', Url::to(['/Restaurant/default/manage-restaurant-staff' ,'rid'=>$id['Restaurant_ID']]), ['class'=>'btn btn-primary']) ?></span>
            <span><?php echo Html::a('Food Operate', Url::to(['/Restaurant/restaurant/food-service' ,'id'=>$id['Restaurant_ID']]), ['class'=>'btn btn-warning']) ?></span>
             <span> <?php if ($id['Restaurant_Status'] == "Closed"): ?>
                    <?=Html::a('Resume Resturant Operate', Url::to(['restaurant/active', 'id'=>$id['Restaurant_ID'],'item'=>1]), ['id'=>'resume','data-confirm'=>"Do you want to Resume Operate?",'class'=>'btn btn-success'])?>
                    <?php elseif($id['Restaurant_Status'] == "Operating"): ?>
                    <?=Html::a('Pause Resturant Operate', Url::to(['restaurant/deactive', 'id'=>$id['Restaurant_ID'],'item'=>1]), ['id'=>'pause','data-confirm'=>"Do you want to Pause Operate?",'class'=>'btn btn-danger'])?>  
              <?php endif ?></span>
            </div>
        <?php endif ?>
    </div>
    </div>
    <br>
    <hr>
    <h2><center>Menu</h2>
    <div class = "foodItems">
    </div>
    <?php
      $rid = $id['Restaurant_ID'];
      $id = isset($_GET['foodid']) ? $_GET['foodid'] : ''; 
    ?>
    <div class="outer-container">
    <div class="menu-container">
            <?php
              foreach($rowfood as $data): 
            ?>
        <a href="<?php echo yii\helpers\Url::to(['/food/food-details','id'=>$data['Food_ID'],'rid'=>$rid]); ?>"  data-toggle="modal" data-target="#foodDetail" data-img="<?php echo $data['PicPath'];?>">
        <div class="item">
            <div class="inner-item">
            <span class="foodName"><?php echo $data['Name']; ?></span>
            <span class="small-text pull-right stars" alt="<?php echo $data['Rating']; ?>"><?php echo $data['Rating']; ?></span>
            <span><p class="price"><?php echo 'RM'.$data['Price']; ?></p></span>
            <p class="foodDesc"><?php echo $data['Description']; ?></p>
            <?php foreach($data['foodType']as $type): ?>
            <span class="tag"><?php echo $type['Type_Desc'].'&nbsp;&nbsp;&nbsp;'; ?></span>
            <?php endforeach; ?>
            </div>
            <div class="img"><?php echo Html::img('@web/imageLocation/foodImg/'.$data['PicPath']) ?></div>
        </div>
        </a>
        <?php endforeach; ?>
    </div>
    </div>
    <?php //echo \yii\widgets\LinkPager::widget([
      //'pagination' => $pagination,
    //]); ?>
</div>
</body>