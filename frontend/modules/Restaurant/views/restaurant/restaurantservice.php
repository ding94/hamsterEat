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

.outer-item{
  border: 1px solid #e5e5e5;
}

.item-no-border{
  font-size: 24px;
  color: black;
  background-color: white;
  min-width: 300px;
  min-height: 160px;
  border-bottom:1px solid #FFDA00;
  padding-right: 20px;
  cursor:pointer;
}

.item-no-border p{
  font-size:16px;
  color:grey;
}

.item-no-border .small-text{
   font-size:15px;
  color:grey; 
  margin-top: 10px;
}

.item-no-border .price{
    font-size: 17px;
    color: black;
}

.item-no-border .inner-item{
  margin:10px 0px 10px 30px;
  float:left;
  width: 50%;
}

.item-no-border .tag{
    font-size: 13px;
    color: grey;
}

.item-no-border .img{

  float:left;
}

.item-no-border img{
	margin-top:15px;
    width:130px;
  height:130px;
  
}

.outer-item :hover{
   background-color: #fffbe5;
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
  margin: 10px 10px 10px 10px;
  float: right;
}
#res:hover{
	 background-color: #e67300; 
}

#food{
  margin: 10px 10px 10px 10px;
  float: right;
}
#food:hover{
   background-color: #e67300; 
}

#resume{
  margin: 10px 10px 10px 10px;
  float: right;
}
#resume:hover{
   background-color: #1a651a; 
}

#pause{
  margin: 10px 10px 10px 10px;
  float: right;
}
#pause:hover{
   background-color: #ff1a1a; 
}

</style>
<body>
<div class ="container" ><h1>Owned/Manage Restaurants</h1>
 <div class="outer-container" id="outer" >
    <div class="menu-container" id="menucon">
      <?php foreach($restaurant as $k => $res ){?>
      <div class="outer-item">
      <a href=" <?php echo yii\helpers\Url::to(['default/restaurant-details','rid'=>$res['Restaurant_ID']]); ?> ">
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