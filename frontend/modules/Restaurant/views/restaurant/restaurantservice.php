<?php
use yii\helpers\Url;
use yii\helpers\Html;
use common\models\Restaurant;
use frontend\assets\StarsAsset;
use frontend\assets\RestaurantServiceAsset;
$this->title = "Owned/Manage Restaurants";

StarsAsset::register($this);
RestaurantServiceAsset::register($this);
?>
<div class ="container" ><h1>Manage Owned Restaurants <?= Html::a('Add Restaurant', ['/Restaurant/default/new-restaurant-location'], ['class'=>'raised-btn main-btn']) ?></h1> 
 <div class="outer-container" id="outer" >
    <div class="menu-container" id="menucon">
      <?php if(!empty($restaurants)): ?>
      <?php foreach($restaurants as $k => $restaurant ){?>
      <div class="outer-item">
        <a href=" <?php echo yii\helpers\Url::to(['/order/restaurant-order-history','rid'=>$restaurant['Restaurant_ID']]); ?> ">
          <div class="item-no-border">
            <div class="img"><?php echo Html::img(Yii::$app->params['restaurant'].$restaurant['Restaurant_RestaurantPicPath']) ?></div>
            <div class="inner-item">
              <div class="restaurant-name-div">
                <span class="restaurant-name"><?php echo $restaurant['Restaurant_Name']; ?></span>
                <span class="small-text pull-right stars" alt="<?php echo $restaurant['Restaurant_Rating']; ?>"><?php echo $restaurant['Restaurant_Rating']; ?></span>
              </div>
              <span><p><?php echo $restaurant['Restaurant_UnitNo'].','.$restaurant['Restaurant_Street'].','.$restaurant['Restaurant_Area'].', '.$restaurant['Restaurant_Postcode'] ?></p></span>
            </div>  	
          </div>
    		</a>
        <?= Html::a('Placed Orders',['/Restaurant/restaurant/cooking-detail','rid'=>$restaurant['Restaurant_ID']],['class'=>'raised-btn btn-success success-btn placed-orders-btn']);?>
      </div>
      <?php } ?>
    <?php else : ?>
      <div class="outer-item">
       <p>There is no restaurant assgined for you</p>
      </div>
    <?php endif; ?>
    </div>
	</div>
</div>