<?php
use yii\helpers\Html;
use yii\helpers\Url;
use yii\bootstrap\Modal;
use common\models\Rmanagerlevel;
$this->title = $id['Restaurant_Name'];
?>
<style>
.outer-container{
  display:flex;
  align-items: center;
  justify-content:center;
}

.menu-container{
  display: grid;
  width:1200px;
  grid-template-columns: 1fr 1fr;
  grid-column-gap: 15px;
  grid-row-gap: 15px;
  margin-bottom: 50px;
  align-items: center;
  justify-content:center;
}

.item{
  font-size: 24px;
  color: black;
  background-color: white;
  min-width: 300px;
  min-height: 170px;
  border: 1px solid #e5e5e5;
}

.item p{
  font-size:15px;
  color:grey;
}

.item .small-text{
   font-size:15px;
  color:grey; 
  margin-top: 10px;
}

.item .price{
    font-size: 17px;
    color: black;
}

.item .inner-item{
  margin:10px 0px 10px 30px;
  float:left;
  width: 50%;
}

.item .tag{
    font-size: 13px;
    color: grey;
}

.item .img{

  float:right;
}

.item img{
    width:168px;
  height:168px;
}

.menu-container a:hover{
    box-shadow: 0px 0px 20px -2px grey;
}

span.stars, span.stars span {
    display: block;
    background: url(imageLocation/stars.png) 0 -16px repeat-x;
    width: 80px;
    height: 16px;
}

span.stars span {
    background-position: 0 0;
}

.info li{
  float: left;
  margin-right: 20px;
  color: grey;
}

.info li.none{
   list-style-type: none;
   margin-left:-20px;
}

.restaurant-info-container{
  margin-bottom: 150px;
}

.item .foodName{
  display: block;
  width: 220px;
  overflow: hidden;
  text-overflow: ellipsis;
  white-space: nowrap;
}

.item .foodDesc{
  display: block;
  width: 220px;
  overflow: hidden;
  text-overflow: ellipsis;
  white-space: nowrap;
}
</style>
<body>
  <?php foreach($rowfood as $data):
  Modal::begin([
            'header' => '<h2 class="modal-title">Food Details</h2>',
            'id'     => 'modal'.$data['Food_ID'],
            'size'   => 'modal-lg',
            //'footer' => '<a href="#" class="btn btn-primary" data-dismiss="modal">Close</a>',
    ]);
    
    echo "<div id='modelContent".$data['Food_ID']."'></div>";
    
    Modal::end() ?>
  <?php endforeach; ?>
  <?php Modal::begin([
            'header' => '<h2 class="modal-title">Report</h2>',
            'id'     => 'modal',
            'size'   => 'modal-sm',
            'footer' => '<a href="#" class="btn btn-primary" data-dismiss="modal">Close</a>',
    ]);
    
    echo "<div id='modelContent'></div>";
    
    Modal::end() ?>
<div class = "container">
  <div class="restaurant-info-container">
    <?php $picpath = $id['Restaurant_RestaurantPicPath'];

        if (is_null($id['Restaurant_RestaurantPicPath'])){
            $picpath = "DefaultRestaurant.jpg";
        }
         echo Html::img('@web/imageLocation/'.$picpath, ['class' => 'img-responsive pull-left', 'style'=>'height:250px; width:350px; margin:auto;']) ?> <?php echo "</th>"; ?>
    <h1 style="font-weight: bold;margin-left: 32%;"><?php echo $id['Restaurant_Name']; ?><span class="pull-right"><?php echo Html::a('Report', Url::to(['/report/report-restaurant' ,'name'=>$id['Restaurant_Name']]), ['class'=>'btn btn-primary','id' => 'reportModalButton']) ?></span></h1>
      <ul class="info" style="margin-left: 30%;">
        <?php if ($id['Restaurant_Pricing'] == 1){ ?>
        <li class="none">$</li>
        <?php } else if ($id['Restaurant_Pricing'] == 2){ ?>
        <li class= "none"> $ $ </li>
        <?php } else { ?>
        <li class= "none"> $ $ $ </li>
        <?php } 
          foreach ($id['restaurantType'] as $type):
        ?>
        <li>
          <?php echo $type['Type_Name'] ?>
        </li>
        <?php endforeach; ?>
        <li><?php echo $id['Restaurant_UnitNo'].", ".$id['Restaurant_Street'].", ".$id['Restaurant_Area'].", ".$id['Restaurant_Postcode']; ?></li>
      </ul>
      <div style="margin-left: 32%; margin-top: 3%;"><span class="small-text stars">
                        <?php echo $id['Restaurant_Rating']; ?>
                      </span></div>
    </div>
    <br>
    <br>
    <?php if (!Yii::$app->user->isGuest)
    {
        if (!empty($staff))
        {
            echo "<table class= table table-user-information style= width:100%; margin:auto;>";
            echo "<tr>";

            if ($staff['RmanagerLevel_Level'] == 'Owner' || $staff['RmanagerLevel_Level'] == 'Manager')
            {
              
              echo "<td><center>".Html::a('Edit Details', ['edit-restaurant-details', 'rid'=>$id['Restaurant_ID'], 'restArea'=>$id['Restaurant_AreaGroup'], 'areachosen'=>$id['Restaurant_Area'], 'postcodechosen'=>$id['Restaurant_Postcode']], ['class'=>'btn btn-primary'])."</td>";
              echo "<td><center>".Html::a('Manage Staffs', ['manage-restaurant-staff', 'rid'=>$id['Restaurant_ID']], ['class'=>'btn btn-primary'])."</td>";
              echo "<td><center>".Html::a('Restaurants Orders', ['/order/restaurant-orders', 'rid'=>$id['Restaurant_ID']], ['class'=>'btn btn-primary'])."</td>";
              echo "<td><center>".Html::a('Restaurants Orders History', ['/order/restaurant-order-history', 'rid'=>$id['Restaurant_ID']], ['class'=>'btn btn-primary'])."</td>";
               echo "<td><center>".Html::a('Manage Menu', ['/food/menu', 'rid'=>$id['Restaurant_ID'],'page'=>'menu'], ['class'=>'btn btn-primary'])."</td>";
               if($id['Restaurant_Status'] == "Operating"){
                echo "<td><center>".Html::a('Pause Operate', Url::to(['restaurant/deactive', 'id'=>$id['Restaurant_ID']]), ['id'=>'operate','onclick'=>"return operation()",'class'=>'btn btn-warning'])."</td>"; // onClick function add in 'return' can  used return false to stop post instead of without return.
               }
              elseif($id['Restaurant_Status'] == "Closed"){
                echo "<td><center>".Html::a('Resume Operate', Url::to(['restaurant/active', 'id'=>$id['Restaurant_ID']]), ['id'=>'operate','onclick'=>"return operation()",'class'=>'btn btn-warning'])."</td>";
               }
            }
            elseif ($staff['RmanagerLevel_Level'] == 'Operator')
            {
                echo "<table class= table table-user-information style= width:100%; margin:auto;>";
                echo "<tr>";
                    echo "<td><center>".Html::a('Restaurants Orders', ['/order/restaurant-orders', 'rid'=>$id['Restaurant_ID']], ['class'=>'btn btn-primary'])."</td>";
                    echo "<td><center>".Html::a('Restaurants Orders History', ['/order/restaurant-order-history', 'rid'=>$id['Restaurant_ID']], ['class'=>'btn btn-primary'])."</td>";
            }

            echo "</tr>";
            echo "</table>";
        }
    }
    ?>
    <hr>
    <br>

    <h2><center>Menu</h2>
    <div class = "foodItems">
    </div>
    <?php
      $rid = $id['Restaurant_ID'];
      $id = isset($_GET['foodid']) ? $_GET['foodid'] : ''; 
    ?>
    <div class="outer-container">
    <div class="menu-container">
            <?php
              foreach($rowfood as $data): 
            ?>
        <a href="<?php echo yii\helpers\Url::to(['/food/food-details','id'=>$data['Food_ID'],'rid'=>$rid]); ?>" data-id="<?php echo $data['Food_ID']; ?>" class="modelButton">
        <div class="item">
            <div class="inner-item">
            <span class="foodName"><?php echo $data['Name']; ?></span>
            <span class="small-text pull-right stars" alt="<?php echo $data['Rating']; ?>"><?php echo $data['Rating']; ?></span>
            <span><p class="price"><?php echo 'RM'.$data['Price']; ?></p></span>
            <p class="foodDesc"><?php echo $data['Description']; ?></p>
            <?php foreach($data['foodType']as $type): ?>
            <span class="tag"><?php echo $type['Type_Desc'].'&nbsp;&nbsp;&nbsp;'; ?></span>
            <?php endforeach; ?>
            </div>
            <div class="img"><?php echo Html::img('@web/imageLocation/foodImg/'.$data['PicPath']) ?></div>
        </div>
        </a>
        <?php endforeach; ?>
    </div>
    </div>
    <?php echo \yii\widgets\LinkPager::widget([
      'pagination' => $pagination,
    ]); ?>
</div>
</body>

<script>
function operation() {
  if (confirm("Do you want to "+document.getElementById("operate").innerHTML + "?")) 
  {
    return true;
  }
  else
  {
    return false;
  }

}
</script>