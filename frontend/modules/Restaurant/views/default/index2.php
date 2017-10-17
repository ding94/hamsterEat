<?php
use yii\helpers\Html;
use common\models\food\Food;
use yii\bootstrap\Modal;
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
  min-height: 190px;
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
    width:188px;
  height:188px;
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
<div class="container" id="index">
    <h1>Order Food for Delivery</h1>
    <?php echo Html::a('Show by Restaurant', ['index', 'groupArea'=>$groupArea], ['class'=>'btn btn-primary']);
    echo Html::a('All', ['show-by-food', 'groupArea'=>$groupArea])."&nbsp;&nbsp;"; ?>
    <?php foreach ($types as $types) :
            echo Html::a($types['Type_Desc'], ['food-filter', 'groupArea'=>$groupArea ,'typefilter'=>$types['ID']])."&nbsp;&nbsp;";
          endforeach; ?>
    <br>
    <br>
    <br>
    <div class="outer-container">
        <div class="menu-container">
        <?php foreach($restaurant as $data) : 
        if ($mode == 1)
        {
            $fooddata=food::find()->where('Restaurant_ID=:id and Status = :status', [':id' => $data['Restaurant_ID'], ':status'=> 1])->innerJoinWith('foodType',true)->innerJoinWith('foodStatus',true)->all(); 
        }
        elseif ($mode == 2)
        {
            //var_dump($type);exit;
            $fooddata=food::find()->where('Restaurant_ID=:id and Status = :status and Type_ID = :tid', [':id' => $data['Restaurant_ID'], ':status'=> 1, ':tid'=>$filter])->innerJoinWith('foodType',true)->innerJoinWith('foodStatus',true)->all();
        }
                foreach($fooddata as $fooddata) : 
                    Modal::begin([
                        'header' => '<h2 class="modal-title">Food Details</h2>',
                        'id'     => 'modal'.$fooddata['Food_ID'],
                        'size'   => 'modal-lg',
                        'footer' => '<a href="#" class="btn btn-primary" data-dismiss="modal">Close</a>',
                    ]);
                
                    echo "<div id='modelContent".$fooddata['Food_ID']."'></div>";
                    
                    Modal::end();
        ?>
                <a href="<?php echo yii\helpers\Url::to(['/food/food-details','id'=>$fooddata['Food_ID'],'rid'=>$fooddata['Restaurant_ID']]); ?>" data-id="<?php echo $fooddata['Food_ID']; ?>" class="modelButton">
                <div class="item">
                    <div class="inner-item">
                        <span><?php echo $fooddata['Name']; ?></span>
                        <span class="small-text pull-right stars" alt="<?php echo $fooddata['Rating']; ?>"><?php echo $fooddata['Rating']; ?></span>
                        <span><p class="price"><?php echo 'RM '.$fooddata['Price']; ?></p></span>
                        <span><p class="rname"><?php echo $data['Restaurant_Name']; ?></p></span>
                        <p><?php echo $fooddata['Description']; ?></p>
                        <?php foreach($fooddata['foodType']as $type): ?>
                        <span class="tag"><?php echo $type['Type_Desc'].'&nbsp;&nbsp;&nbsp;'; ?></span>
                        <?php endforeach; ?>
                    </div>
                    <div class="img"><?php echo Html::img('@web/imageLocation/foodImg/'.$fooddata['PicPath']) ?></div>
                    </div>
                    </a>
                    <?php endforeach; ?>
                </div>
            <?php endforeach;
            ?>
        </div>
    </div>
</div>