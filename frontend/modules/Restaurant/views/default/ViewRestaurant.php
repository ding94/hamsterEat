<?php
use yii\helpers\Html;
use common\models\Restaurant;
use frontend\assets\StarsAsset;
use frontend\assets\ViewRestaurantAsset;
use frontend\controllers\CommonController;

$this->title = "View Restaurant";

StarsAsset::register($this);
ViewRestaurantAsset::register($this);
?>
<body>

<div class = "container" ><h1>My Restaurants</h1>
 <div class="outer-container"id="try" >
    <div class="menu-container" id="try1">
      <?php foreach($restaurants as $restaurants): 
        $restaurant = Restaurant::find()->where('Restaurant_ID = :rid', [':rid'=>$restaurants['Restaurant_ID']])->one(); 
        $resname = CommonController::getRestaurantName($restaurants['Restaurant_ID']);
      ?>
      <a href=" <?php echo yii\helpers\Url::to(['restaurant-details','rid'=>$restaurant['Restaurant_ID']]); ?> " style="display:block" >

      <div class="item" onclick="window.document.location='<?php echo yii\helpers\Url::to(['restaurant-details','rid'=>$restaurant['Restaurant_ID']]); ?>';">
        <div class="img"><?php echo Html::img($restaurant->img) ?></div>
        <div class="inner-item">
          <span><?php echo $resname; ?></span>

          <p><?php echo $restaurant['Restaurant_UnitNo'].','.$restaurant['Restaurant_Street'].','.$restaurant['Restaurant_Area'].', '.$restaurant['Restaurant_Postcode'] ?></p>
    
        </div>
  
		    <span class="small-text pull-right stars" alt="<?php echo $restaurant['Restaurant_Rating']; ?>"><?php echo $restaurant['Restaurant_Rating']; ?></span>
        
      </div>
       </a>
      <?php endforeach; ?>
    </div>
	</div>
</div>