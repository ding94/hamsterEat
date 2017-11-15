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
<div class ="container" ><h1>Manage Owned Restaurants</h1>
 <div class="outer-container" id="outer" >
    <div class="menu-container" id="menucon">
      <?php foreach($restaurant as $k => $res ){?>
      <div class="outer-item">
      <a href=" <?php echo yii\helpers\Url::to(['/Restaurant/default/restaurant-details','rid'=>$res['Restaurant_ID']]); ?> ">
      <div class="item-no-border">
        <div class="img"><?php echo Html::img('@web/imageLocation/'.$res['Restaurant_RestaurantPicPath']) ?></div>
        <div class="inner-item">
          <span><?php echo $res['Restaurant_Name']; ?></span>

          <p><?php echo $res['Restaurant_UnitNo'].','.$res['Restaurant_Street'].','.$res['Restaurant_Area'].', '.$res['Restaurant_Postcode'] ?></p>
    	</div>
		    <span class="small-text pull-right stars" alt="<?php echo $res['Restaurant_Rating']; ?>"><?php echo $res['Restaurant_Rating']; ?></span>  	
      </div>
  		</a>
      </div>
      <?php } ?>
    </div>
	</div>
</div>