<?php
use yii\helpers\Html;
use common\models\Restauranttype;
use kartik\widgets\ActiveForm;
$this->title = "Available Restaurants";

?>
<style>
.outer-container{
  margin-top: 40px;
  display:flex;
  align-items: center;
  justify-content:center;
}
.menu-container{
  display: grid;
  width:775px;
  grid-template-columns: 1fr 1fr 1fr;
  grid-column-gap: 15px;
  grid-row-gap: 15px;
  margin-bottom: 50px;
  align-items: center;
  justify-content:center;
  
}

.list{
  font-size: 18px;
  color: black;
  background-color: white;
  min-width: 298px;
  min-height: 298px;
  font-weight: 700;
  border: 1px solid grey;
 
}

.list .name{
  margin: 0px 0px 0px 20px;
}

.list ul{
  margin: 10px 10px 10px 0px;
}

.tag{
    margin-left:-20px;
}

.tag li{
    float:left;
    margin-right: 20px;
    font-size: 13px;
    color: grey;
}
.tag li.none{
   list-style-type: none;
   margin-left:-20px;
}

.list a:hover{
    text-decoration:none;
}
.menu-container a:hover{
    box-shadow: 0px 0px 20px -2px grey;
}
.filter{
    height:auto;
    width:230px;
    float:left;
   margin-left:-10px;
}
.filter.container{
     border: 1px solid #e5e5e5;
    padding: 20px;
    margin-top:26px;
    
}
.filter-list{
    list-style: none;
    font-size:13px;
    letter-spacing: .5px;
    line-height:80%;
    padding-left:20px;
}
.filter.name p{
    margin-left:11px;
    letter-spacing: .5px;
    font-size:15px;
}
.filter.list a:hover{
     text-decoration:none;
}
.input-group{
        position: relative;
    display: table;
    border-collapse: separate;
}
</style>
<div class="container" id="index">
    <h1>Order Food for Delivery</h1>
    <div class="filter">
    <?php echo Html::a('<i class="fa fa-cutlery"> Food</i>', ['show-by-food', 'groupArea'=>$groupArea], ['class'=>'btn btn-default']); ?>
    <div class="filter container">
    <div class="input-group">
    <?php $form = ActiveForm::begin(['id' => 'form-searchrestaurant']) ?>
    <?= $form->field($search, 'Nickname',['addon'=>['append'=>['content'=>Html::submitButton('<i class="fa fa-search"></i>', ['class' => 'btn btn-default', 'name' => 'search-button2']),'asButton'=>true]]])->textInput(['placeholder' => "Search Restaurant"])->label(''); ?>
    <?php ActiveForm::end(); ?>
    </div>
      <div class ="filter name">
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
              <span class="small-text pull-right stars">
                <?php echo $data['Restaurant_Rating']; ?>
              </span>
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