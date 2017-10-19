<?php
use yii\helpers\Html;
use common\models\food\Food;
use yii\bootstrap\Modal;
use common\models\food\Foodtype;
use kartik\widgets\ActiveForm;
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
.filter.list{
    list-style: none;
    font-size:13px;
    letter-spacing: .5px;
    line-height:80%;
    padding-left:28px;
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
.input-group-btn {
    position: relative;
    font-size: 0;
    white-space: nowrap;
}
</style>
<div class="container" id="index">
    <h1>Order Food for Delivery</h1>
    <div class="filter">
   
    <?php echo Html::a('<i class="fa fa-home"> Restaurant</i>', ['index', 'groupArea'=>$groupArea], ['class'=>'btn btn-default']);?>
    <div class="filter container">
    <div class="input-group">
    <?php $form = ActiveForm::begin(['id' => 'form-searchfood']) ?>
    <?= $form->field($search, 'Nickname',['addon'=>['append'=>['content'=>Html::submitButton('<i class="fa fa-search"></i>', ['class' => 'btn btn-default', 'name' => 'search-button2']),'asButton'=>true]]])->textInput(['placeholder' => "Search"])->label(''); ?>
    <?php ActiveForm::end(); ?>
    </div>
    <div class ="filter name">
        <p><i class="fa fa-sliders"> Filter By</i></p>
     </div>
    <ul class ="filter list">
   <?php echo Html::a('<li>All</li>', ['show-by-food', 'groupArea'=>$groupArea])."&nbsp;&nbsp;"; ?>  
    <?php foreach ($types as $types) :
            echo Html::a('<li>'.$types['Type_Desc'].'</li>', ['food-filter', 'groupArea'=>$groupArea ,'typefilter'=>$types['ID']])."&nbsp;&nbsp;";
          endforeach; ?>
          </ul>
          
         
          </div>
          </div>
    <br>
    <br>
    <br>
    <?php if ($mode == 2)
    {
        $foodtype = Foodtype::find()->where('ID = :id', [':id'=>$filter])->one();
        echo "<h3>Filtering By ".$foodtype['Type_Desc']."</h3>";
    }
    elseif ($mode == 3)
    {
        echo "<h3>Showing results similar to ".$keyword."</h3>";
    }
    elseif ($mode == 4)
    {
        $foodtype = Foodtype::find()->where('ID = :id', [':id'=>$filter])->one();
        echo "<h3>Showing results similar to ".$keyword." with filter ".$foodtype['Type_Desc']."</h3>";
    }

    ?>
    <div class="outer-container">
        <div class="menu-container">
        <?php foreach($restaurant as $data) : 
        if ($mode == 1)
        {
            $fooddata=food::find()->where('Restaurant_ID=:id and Status = :status', [':id' => $data['Restaurant_ID'], ':status'=> 1])->innerJoinWith('foodType',true)->innerJoinWith('foodStatus',true)->all(); 
        }
        elseif ($mode == 2)
        {
            $fooddata=food::find()->where('Restaurant_ID=:id and Status = :status and Type_ID = :tid', [':id' => $data['Restaurant_ID'], ':status'=> 1, ':tid'=>$filter])->innerJoinWith('foodType',true)->innerJoinWith('foodStatus',true)->all();
        }
        elseif ($mode == 3)
        {
            $fooddata=food::find()->where('Restaurant_ID=:id and Status = :status', [':id' => $data['Restaurant_ID'], ':status'=> 1])->andWhere(['like', 'Name', $keyword])->innerJoinWith('foodType',true)->innerJoinWith('foodStatus',true)->all();
        }
        elseif ($mode == 4)
        {
            $fooddata=food::find()->where('Restaurant_ID=:id and Status = :status and Type_ID = :tid', [':id' => $data['Restaurant_ID'], ':status'=> 1, ':tid'=>$filter])->andWhere(['like', 'Name', $keyword])->innerJoinWith('foodType',true)->innerJoinWith('foodStatus',true)->all();
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
                        <span style="overflow: hidden; text-overflow: ellipsis; width: 100%; white-space: nowrap;"><?php echo $fooddata['Name']; ?></span>
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
                <?php endforeach; ?>
        </div>
    </div>
</div>