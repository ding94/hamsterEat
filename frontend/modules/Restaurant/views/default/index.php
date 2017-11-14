<?php
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\LinkPager;
use common\models\Restauranttype;
use kartik\widgets\ActiveForm;
use frontend\assets\StarsAsset;
use frontend\assets\RestaurantDefaultIndexAsset;

$this->title = "Available Restaurants";
StarsAsset::register($this);
RestaurantDefaultIndexAsset::register($this);
?>
 <script src="https://code.jquery.com/jquery-1.10.2.js"></script>
<div class="container" id="group-area-index">
    <h1>Order Food for Delivery</h1>
   
    <?php echo Html::a('<i class="fa fa-cutlery"> Food</i>', ['show-by-food', 'groupArea'=>$groupArea], ['class'=>'btn btn-default']); ?>
    <input type="checkbox" id="sidebartoggler" name="" value="">
    <div class="page-wrap">
       <label for="sidebartoggler" class="toggle">Filter</label>
	  <a href="#top" class="scrollToTop"></a>
      <div class="filter">
        <div class="filter container">
          <div class="input-group">
            <?php $form = ActiveForm::begin(['id' => 'form-searchrestaurant','method' => 'get']) ?>
              <div class="input-group">
                <input id="food-nickname" class="form-control" name="filter" placeholder="Search Restaurant" type="text"><span class="input-group-btn"><button type="submit" class="btn btn-default icon-button"><i class="fa fa-search"></i>
                </button></span>
              </div>
           
            <?php ActiveForm::end(); ?>
          </div>
          <div class ="filter-name">
            <p><i class="fa fa-sliders"> Filter By</i></p>
          </div>
          <ul class ="filter-list">
          <?php echo Html::a('<li>All</li>', ['index', 'groupArea'=>$groupArea])."&nbsp;&nbsp;"; ?>
            <?php foreach ($allrestauranttype as $i=> $data) : ?>
              <?php if(empty($filter)) :?>
                <?php echo Html::a('<li>'.$data.'</li>', ['/Restaurant/default/index', 'groupArea'=>$groupArea ,'type'=>$i])."&nbsp;&nbsp;"; ?>
              <?php else :?>
                 <?php echo Html::a('<li>'.$data.'</li>', ['/Restaurant/default/index', 'groupArea'=>$groupArea ,'type'=>$i ,'filter'=>$filter])."&nbsp;&nbsp;"; ?>
              <?php endif ;?>
            <?php endforeach; ?>
          </ul>
        </div>
      </div>
    </div>
<br>

  <?php if(!empty($filter) && !empty($type)) : ?>

        <h3>Showing results similar to <?php echo $filter ?> with filter <?php echo $allrestauranttype[$type]?></h3>
    <?php elseif(!empty($type)) : ?>
        <h3>Filter By <?php echo $allrestauranttype[$type]?></h3>
    <?php elseif(!empty($filter)) :?>
        <h3>Showing results similar to <?php echo $filter ?></h3>
    <?php endif ;?>

    <div class="outer-container">
      <div class="menu-container">
        <?php foreach($restaurant as $data) :?>
          <a href="<?php echo yii\helpers\Url::to(['restaurant-details','rid'=>$data['Restaurant_ID']]); ?>">
            <div class="list">
              <?php $picpath = $data['Restaurant_RestaurantPicPath']; 
                if (is_null($data['Restaurant_RestaurantPicPath'])){
                  $picpath = "DefaultRestaurant.jpg";
                }
              ?>
              <th rowspan = "5">
                <?php echo Html::img('@web/imageLocation/'.$picpath, ['class' => 'img-responsive','style'=>'height:200px; width:298px; margin-bottom:20px;']) ?>
              </th>
              <span class="name">
                <?php echo $data['Restaurant_Name']; ?>
              </span>
              <div class="rating pull-right">
              <span class="small-text stars">
                <?php echo $data['Restaurant_Rating']; ?>
              </span>
              </div>
              <ul class="tag">
                <?php if ($data['Restaurant_Pricing'] == 1){ ?>
                <li class="none">$</li>
                <?php } else if ($data['Restaurant_Pricing'] == 2){ ?>
                <li class= "none"> $ $ </li>
                <?php } else { ?>
                <li class= "none"> $ $ $ </li>
                <?php } 
                  foreach ($data['restaurantType'] as $type) :
                ?>
                <li><?php echo $type['Type_Name']; ?></li>
                <?php endforeach; ?>
              </ul>            
            </div>
          </a>
        <?php endforeach; ?>
      </div>
    </div>
    <?php echo LinkPager::widget([
          'pagination' => $pagination,
    ]); ?>
</div>