<?php
use yii\helpers\Html;
$this->title = "Available Restaurants";

?>
<style>
.outer-container{
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
  min-width: 376px;
  min-height: 357px;
 font-weight: 700;
 border: 1px solid grey;
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
span.stars, span.stars span {
    display: block;
    background: url(imageLocation/stars.png) 0 -16px repeat-x;
    width: 80px;
    height: 16px;
}
span.stars span {
    background-position: 0 0;
}

.list a:hover{
    text-decoration:none;
}
.menu-container a:hover{
    box-shadow: 0px 0px 20px -2px grey;
}

</style>
<div class="container" id="index">
    <h1>Order Food for Delivery</h1>

    <div class="outer-container">
      <div class="menu-container">
        <?php foreach($restaurant as $data) : ?>
          <a href="<?php echo yii\helpers\Url::to(['restaurant-details','rid'=>$data['Restaurant_ID']]); ?>">
            <div class="list">
              <?php $picpath = $data['Restaurant_RestaurantPicPath']; 
                if (is_null($data['Restaurant_RestaurantPicPath'])){
                  $picpath = "DefaultRestaurant.jpg";
                }
              ?>
              <th rowspan = "5">
                <?php echo Html::img('@web/imageLocation/'.$picpath, ['class' => 'img-responsive','style'=>'height:240px; width:376px; margin:auto;']) ?>
              </th>
              <table class="table table-restaurant-details">
                <tbody>
                  <tr>
                    <td>
                      <span class="name">
                        <?php echo $data['Restaurant_Name']; ?>
                      </span>
                      <span class="small-text pull-right stars">
                        <?php echo $data['Restaurant_Rating']; ?>
                      </span>
                    </td>
                  </tr>
                  <tr>
                    <td>
                      <ul class="tag">
                        <?php $tags=explode(",",$data['Restaurant_Tag']);
                         if ($data['Restaurant_Pricing'] == 1){ 
                        ?>
                        <li class="none">$</li>
                        <?php } else if ($data['Restaurant_Pricing'] == 2){ ?>
                        <li class= "none"> $ $ </li>
                        <?php } else { ?>
                        <li class= "none"> $ $ $ </li>
                        <?php } 
                          foreach ($tags as $tags) :
                        ?>
                        <li><?php echo $tags; ?></li>
                        <?php endforeach; ?>
                      </ul>
                    </td>
                  </tr>
                </tbody>
              </table>  
            </div>
          </a>
        <?php endforeach; ?>
      </div>
    </div>
  </div>