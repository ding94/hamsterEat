<?php
use yii\helpers\Url;
use yii\bootstrap\Modal;
use yii\helpers\Html;
use common\models\food\Foodstatus;
use frontend\assets\StarsAsset;
use frontend\assets\FoodServiceAsset;
$this->title = "Manage Foods";

StarsAsset::register($this);
FoodServiceAsset::register($this);

Modal::begin([
      'header' => '<h2 class="modal-title">Please choose delivery place</h2>',
      'id'     => 'add-session-modal',
      'size'   => 'modal-md',
      'footer' => '<a href="#" class="raised-btn alternative-btn" data-dismiss="modal">Close</a>',
]);
Modal::end();
?>
<body>
<div class ="container" ><h1>Manage Foods</h1>
  <div style="padding:10px 0px 20px 0px;"><?=Html::a('Back to Restaurants', Url::to(['/Restaurant/default/restaurant-details','rid'=>$rid]), ['class'=>'raised-btn secondary-btn'])?></div>
 <div class="outer-container" id="outer" >
    <div class="menu-container" id="menucon">
      <?php foreach($foods as $k => $food ){?>
      <?php $status = Foodstatus::find()->where('Food_ID=:id',[':id'=>$food['Food_ID']])->one(); ?>
      <?php if ($status['Status'] >= 0): ?>
      <div class="outer-item">
      <div class="item-no-border">
        <div class="img"><img src=<?php echo $fooddata->singleImg ?> alt=""></div>
        <div class="inner-item">
          <span class="foodName"><?php echo $food['Name']; ?></span>
          <p class="foodDesc">Description: <?php echo $food['Description']?></p>
          <p>Ingredients: <?php echo $food['Ingredient']?></p>
          <p>Nick Name: <?php echo $food['Nickname']?></p>
    	</div>
		    <span class="small-text pull-right stars" alt="<?php echo $food['Rating']; ?>"><?php echo $food['Rating']; ?></span>
      </div>
      <?php if (!empty($status)): ?>
        <?php if ($status['Status'] == 0): ?>
              <?=Html::a('Resume Food Service', Url::to(['restaurant/active', 'id'=>$food['Food_ID']]), ['id'=>'res','data-confirm'=>"Do you want to Resume Operate?",'class'=>'raised-btn btn-warning'])?>
        <?php elseif ($status['Status'] == 1): ?>
              <?=Html::a('Pause Food Service', Url::to(['restaurant/providereason', 'id'=>$food['Food_ID']]), ['id'=>'res','data-confirm'=>"Do you want to Pause Operate?",'class'=>'raised-btn btn-warning','data-toggle'=>'modal','data-target'=>'#add-session-modal'])?>  
        <?php endif ?>
      <?php endif ?>
      </div>
      <?php endif ?>
      <?php } ?>
    </div>
	</div>
</div>
</body>