<?php
use yii\helpers\Url;
use yii\helpers\Html;
use common\models\food\Foodstatus;
use frontend\assets\StarsAsset;
use frontend\assets\FoodServiceAsset;
$this->title = "Manage Foods";

StarsAsset::register($this);
FoodServiceAsset::register($this);
?>
<body>
<div class ="container" ><h1>Manage Foods</h1>
  <div style="padding:10px 0px 20px 0px;"><?=Html::a('Back to Restaurants', Url::to(['restaurant/restaurant-service']), ['class'=>'btn btn-primary'])?></div>
 <div class="outer-container" id="outer" >
    <div class="menu-container" id="menucon">
      <?php foreach($foods as $k => $food ){?>
      <div class="outer-item">
      <div class="item-no-border">
        <div class="img"><?php echo Html::img('@web/imageLocation/foodImg/'.$food['PicPath']) ?></div>
        <div class="inner-item">
          <span><?php echo $food['Name']; ?></span>

          <p>Description: <?php echo $food['Description']?></p>
          <p>Ingredients: <?php echo $food['Ingredient']?></p>
          <p>Nick Name: <?php echo $food['Nickname']?></p>
    	</div>
		    <span class="small-text pull-right stars" alt="<?php echo $food['Rating']; ?>"><?php echo $food['Rating']; ?></span>
      </div>
      <?php $status = Foodstatus::find()->where('Food_ID=:id',[':id'=>$food['Food_ID']])->one(); ?>
      <?php if (!empty($status)): ?>
        <?php if ($status['Status'] == 0): ?>
              <?=Html::a('Resume Food Service', Url::to(['restaurant/active', 'id'=>$food['Food_ID'],'item'=>2]), ['id'=>'res','data-confirm'=>"Do you want to Resume Operate?",'class'=>'btn btn-warning'])?>
        <?php elseif ($status['Status'] == 1): ?>
              <?=Html::a('Pause Food Service', Url::to(['restaurant/deactive', 'id'=>$food['Food_ID'],'item'=>2]), ['id'=>'res','data-confirm'=>"Do you want to Pause Operate?",'class'=>'btn btn-warning'])?>  
        <?php endif ?>
      <?php endif ?>
      </div>
      <?php } ?>
    </div>
	</div>
</div>
</body>