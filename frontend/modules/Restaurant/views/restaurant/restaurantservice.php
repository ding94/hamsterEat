<?php
use yii\helpers\Url;
use yii\helpers\Html;
use common\models\Restaurant;
$this->title = "Owned/Manage Restaurants";

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
  min-height: 160px;
  border-bottom:1px solid #FFDA00;
  padding-right: 20px;
  cursor:pointer;
}

.item p{
  font-size:16px;
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
	margin-top:15px;
    width:130px;
  height:130px;
  
}

.menu-container :hover{
   background-color: #fffbe5;
}
.menu-container a:hover,.menu-container p:hover {
   /* color: #fffbe5; */
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

#res{
	margin-top: 10%;
}
#res:hover{
	 background-color: #e67300; 
}

#food{
	margin-top: 10%;
}
#food:hover{
	 background-color: #e67300; 
}

</style>
<body>
<div class = "container" ><h1>Owned/Manage Restaurants</h1>
 <div class="outer-container" id="outer" >
    <div class="menu-container" id="menucon">
      <?php foreach($restaurant as $k => $res ){?>
      <a href=" <?php echo yii\helpers\Url::to(['default/restaurant-details','rid'=>$res['Restaurant_ID']]); ?> " style="display:block" >

      <div class="item">
        <div class="img"><?php echo Html::img('@web/imageLocation/'.$res['Restaurant_RestaurantPicPath']) ?></div>
        <div class="inner-item">
          <span><?php echo $res['Restaurant_Name']; ?></span>

          <p><?php echo $res['Restaurant_UnitNo'].','.$res['Restaurant_Street'].','.$res['Restaurant_Area'].', '.$res['Restaurant_Postcode'] ?></p>
    	</div>
		    <span class="small-text pull-right stars" alt="<?php echo $res['Restaurant_Rating']; ?>"><?php echo $res['Restaurant_Rating']; ?></span>
		    <span class="small-text">
		    	<?php if ($res['Restaurant_Status'] == "Closed"): ?>
		    		<span class="small-text"><button class="btn btn-warning" id="food">Food Operation</button></span>
			    	<?=Html::a('Resume Operate', Url::to(['restaurant/active', 'id'=>$res['Restaurant_ID']]), ['id'=>'res','data-confirm'=>"Do you want to Resume Operate?",'class'=>'btn btn-warning'])?>
			    <?php elseif($res['Restaurant_Status'] == "Operating"): ?>
		    		<span class="small-text"><button class="btn btn-warning" id="food">Food Operation</button></span>
			    	<?=Html::a('Pause Operate', Url::to(['restaurant/deactive', 'id'=>$res['Restaurant_ID']]), ['id'=>'res','data-confirm'=>"Do you want to Pause Operate?",'class'=>'btn btn-warning'])?>
		    	<?php endif ?>
		    </span>
      </div>
  		</a>
      <?php } ?>
    </div>
	</div>
</div>
</body>