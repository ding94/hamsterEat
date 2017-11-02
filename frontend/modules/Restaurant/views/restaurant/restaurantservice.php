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
<body>
<div class ="container" ><h1>Owned/Manage Restaurants</h1>
 <div class="outer-container" id="outer" >
    <div class="menu-container" id="menucon">
      <?php foreach($restaurant as $k => $res ){?>
      <div class="outer-item">
      <a href=" <?php echo yii\helpers\Url::to(['restaurant/food-service','id'=>$res['Restaurant_ID']]); ?> ">
      <div class="item-no-border">
        <div class="img"><?php echo Html::img('@web/imageLocation/'.$res['Restaurant_RestaurantPicPath']) ?></div>
        <div class="inner-item">
          <span><?php echo $res['Restaurant_Name']; ?></span>

          <p><?php echo $res['Restaurant_UnitNo'].','.$res['Restaurant_Street'].','.$res['Restaurant_Area'].', '.$res['Restaurant_Postcode'] ?></p>
    	</div>
		    <span class="small-text pull-right stars" alt="<?php echo $res['Restaurant_Rating']; ?>"><?php echo $res['Restaurant_Rating']; ?></span>  	
      </div>
  		</a>
      <?php if ($res['Restaurant_Status'] == "Closed"): ?>
            <?=Html::a('Food Detail', Url::to(['restaurant/food-service', 'id'=>$res['Restaurant_ID']]), ['id'=>'food','class'=>'btn btn-warning'])?>
            <?=Html::a('Resume Operate', Url::to(['restaurant/active', 'id'=>$res['Restaurant_ID'],'item'=>1]), ['id'=>'resume','data-confirm'=>"Do you want to Resume Operate?",'class'=>'btn btn-success'])?>
          <?php elseif($res['Restaurant_Status'] == "Operating"): ?>
            <?=Html::a('Food Detail', Url::to(['restaurant/food-service', 'id'=>$res['Restaurant_ID']]), ['id'=>'food','class'=>'btn btn-warning'])?>
            <?=Html::a('Pause Operate', Url::to(['restaurant/deactive', 'id'=>$res['Restaurant_ID'],'item'=>1]), ['id'=>'pause','data-confirm'=>"Do you want to Pause Operate?",'class'=>'btn btn-danger'])?>  
          <?php endif ?>
      </div>
      <?php } ?>
    </div>
	</div>
</div>
</body>