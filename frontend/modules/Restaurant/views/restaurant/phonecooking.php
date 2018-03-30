<?php
use yii\helpers\Url;
use yii\helpers\Html;
use common\models\Restaurant;
use frontend\assets\StarsAsset;
use frontend\assets\RestaurantServiceAsset;
$this->title = "Placed Orders";

StarsAsset::register($this);
RestaurantServiceAsset::register($this);
?>
<div class ="container" > 
      <?php if($count > 0): ?>
      <?php foreach($restaurants as $k => $restaurant ):?>
      <?php if($restaurant['Restaurant_Orders']>0): ?>
      <div class="outer-item">
        <font style='text-align: center; font-size:1.5em;font-family: "Times New Roman", Times, serif;'>
          <a href=" <?php echo yii\helpers\Url::to(['/Restaurant/restaurant/cooking-detail','rid'=>$restaurant['Restaurant_ID']]); ?> ">
            <div style="height: 50px;">
                <?php echo $resname[$k]; ?>(<?= $restaurant['Restaurant_Orders']; ?>)
            </div>
      		</a>
        </font>
      </div>
    <?php endif; ?>
      <?php endforeach; ?>
    <?php else : ?>
      <div class="outer-item">
       <font style='text-align: center; font-size:1.5em;font-family: "Times New Roman", Times, serif;'>There is no orders placed</font>
      </div>
    <?php endif; ?>
    </div>
</div>