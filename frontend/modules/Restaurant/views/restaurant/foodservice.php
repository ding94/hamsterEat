<?php
use yii\helpers\Url;
use yii\helpers\Html;
use common\models\food\Foodstatus;
$this->title = "Manage Foods";

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

</style>
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