   <?php
use yii\helpers\Html;
$this->title = "View Restaurant";
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
  grid-template-columns: 1fr;
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
  border-bottom:1px solid grey;
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

  float:left;
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
</style>

<body>

<div class = "container" ><h1>Restaurant</h1>
 <div class="outer-container">
    <div class="menu-container">
            <?php foreach($restaurant as $restaurant): ?>
        <a href=" <?php echo yii\helpers\Url::to(['restaurant-details','rid'=>$restaurant['Restaurant_ID']]); ?> " id="modelButton">
        <div class="item">
		<div class="img"><?php echo Html::img('@web/imageLocation/'.$restaurant['Restaurant_RestaurantPicPath']) ?></div>
            <div class="inner-item">
            <span><?php echo $restaurant['Restaurant_Name']; ?></span>

            <p><?php echo $restaurant['Restaurant_UnitNo'].','.$restaurant['Restaurant_Street'].','.$restaurant['Restaurant_Area'].', '.$restaurant['Restaurant_Postcode'] ?></p>     
        </div>
        </a>
    </div>
	 <?php endforeach; ?>
    </div>
	</div>
	    </div>