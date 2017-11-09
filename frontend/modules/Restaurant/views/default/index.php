<?php
use yii\helpers\Html;
use common\models\Restauranttype;
use kartik\widgets\ActiveForm;
use frontend\assets\StarsAsset;
use frontend\assets\RestaurantDefaultIndexAsset;
$this->title = "Available Restaurants";
StarsAsset::register($this);
RestaurantDefaultIndexAsset::register($this);
?>

<div class="container" id="group-area-index">
    <h1>Order Food for Delivery</h1>
   
    <?php echo Html::a('<i class="fa fa-cutlery"> Food</i>', ['show-by-food', 'groupArea'=>$groupArea], ['class'=>'btn btn-default']); ?>
    <input type="checkbox" id="sidebartoggler" name="" value="">
    <div class="page-wrap">
      <label for="sidebartoggler" class="toggle">☰</label>
      <div class="filter">
        <div class="filter container">
          <div class="input-group">
            <?php $form = ActiveForm::begin(['id' => 'form-searchrestaurant']) ?>
            <?= $form->field($search, 'Nickname',['addon'=>['append'=>['content'=>Html::submitButton('<i class="fa fa-search"></i>', ['class' => 'btn btn-default icon-button', 'name' => 'search-button2']),'asButton'=>true]]])->textInput(['placeholder' => "Search Restaurant"])->label(false); ?>
            <?php ActiveForm::end(); ?>
          </div>
          <div class ="filter-name">
            <p><i class="fa fa-sliders"> Filter By</i></p>
          </div>
          <ul class ="filter-list">
          <?php echo Html::a('<li>All</li>', ['index', 'groupArea'=>$groupArea])."&nbsp;&nbsp;"; ?>
            <?php foreach ($types as $types) :
              echo Html::a('<li>'.$types['Type_Name'].'</li>', ['restaurant-filter', 'groupArea'=>$groupArea ,'rfilter'=>$types['ID']])."&nbsp;&nbsp;";
            endforeach; ?>
          </ul>
        </div>
      </div>
    </div>
<br>
    <?php if ($mode == 2)
    {
      $restauranttype = Restauranttype::find()->where('ID = :id', [':id'=>$rfilter])->one();
      echo "<h3>Filtering By ".$restauranttype['Type_Name']."</h3>";
    }
    elseif ($mode == 3)
    {
      echo "<h3>Showing results similar to ".$keyword."</h3>";
    }
    elseif ($mode == 4)
    {
      $restauranttype = Restauranttype::find()->where('ID = :id', [':id'=>$rfilter])->one();
      echo "<h3>Showing results similar to ".$keyword." with filter ".$restauranttype['Type_Name']."</h3>";
    }
    ?>
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
</div>