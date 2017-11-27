<?php
use yii\helpers\Html;
use yii\helpers\Url;
use common\models\food\Food;
use yii\bootstrap\Modal;
use common\models\food\Foodtype;
use kartik\widgets\ActiveForm;
use yii\widgets\LinkPager;
use frontend\assets\StarsAsset;
use frontend\assets\CartAsset;
use frontend\assets\RestaurantDefaultIndex2Asset;

$this->title = "Available Restaurants";

StarsAsset::register($this);
RestaurantDefaultIndex2Asset::register($this);
CartAsset::register($this);


Modal::begin([
      'header' => '<h2 class="modal-title">Please choose delivery place</h2>',
      'id'     => 'add-modal',
      'size'   => 'modal-md',
      'footer' => '<a href="#" class="btn btn-primary" data-dismiss="modal">Close</a>',
]);
Modal::end();
?>

 <script src="https://code.jquery.com/jquery-1.10.2.js"></script>
<div class="container" id="group-area-index2">
    <h1 style="padding-top:10px;">Order Food for Delivery </h1>

  
        <?php echo Html::a('<i class="fa fa-home"> Restaurant</i>', ['index', 'groupArea'=>$groupArea], ['class'=>'btn btn-default']);?>
		<?php echo Html::a('<i class="fa fa-thumbs-up"> Food</i>', ['show-by-food', 'groupArea'=>$groupArea], ['class'=>'btn btn-default','style'=>'background-color:#FFDA00;pointer-events: none;']); ?>       
		<?php echo Html::a('Change Place', ['/Restaurant/default/addsession','page'=>'index2'], ['id'=>'cp','data-toggle'=>'modal','data-target'=>'#add-modal','style'=>'color:red;font-size:12px;float:right;']); ?>  
	 <input type="checkbox" id="sidebartoggler" name="" value="">
  
	   <div class="page-wrap">
	   <div class="tm">
            <a href="#menu" id="toggle-menu"><span></span></a>

            <div id="menu">
              <ul>
                <li> <a><label for="sidebartoggler" class="toggle">Filter</label></li></a>
				<li><?php echo Html::a('Change Place', ['/Restaurant/default/addsession','page'=>'index2'], ['data-toggle'=>'modal','data-target'=>'#add-modal']); ?></li>
                <li>
                    <?php  $cookies = Yii::$app->request->cookies;
                            $halal = $cookies->getValue('halal');
                    ?>           
                    <?php echo Html::a("Change :". $name = $halal == 0 ? 'Halal' : 'Non-halal',['/Restaurant/default/changecookie','type'=>$halal == 0 ? 1 : 0])?>
                </li>
              </ul>
            </div>
	  </div>
		<!--<a href="#top" title="Go to top of page"><span><i class="fa fa-chevron-up fa-2x" aria-hidden="true"></i></span>-->
	<a href="#top" class="scrollToTop"></a>
            <div class="filter">
                <div class="filter container">
                    <div class="input-group">
                    <?php $form = ActiveForm::begin(['id' => 'form-searchfood','method'=>'get']) ?>
                       <div class="input-group"><input id="food-nickname" class="form-control" name="filter" placeholder="Search Food" type="text">
                            <span class="input-group-btn">
                                <button type="submit" class="btn btn-default icon-button"><i class="fa fa-search"></i></button>
                            </span>
                        </div>
                    <?php ActiveForm::end(); ?>
                    </div>
                    <div class ="filter-name">
                        <p><i class="fa fa-sliders"> Filter By</i></p>
                    </div>
                    <ul class ="filter-list">
                    <?php echo Html::a('<li>All</li>', ['show-by-food', 'groupArea'=>$groupArea])."&nbsp;&nbsp;"; ?>  
                        <?php foreach ($allfoodtype as $i=> $data) : ?>
                            <?php if(empty($filter)) :?>
                                <?php echo Html::a('<li>'.$data.'</li>', ['/Restaurant/default/show-by-food', 'groupArea'=>$groupArea ,'type'=>$i])."&nbsp;&nbsp;"; ?>
                            <?php else :?>
                                <?php echo Html::a('<li>'.$data.'</li>', ['/Restaurant/default/show-by-food', 'groupArea'=>$groupArea ,'type'=>$i ,'filter'=>$filter])."&nbsp;&nbsp;"; ?>
                            <?php endif ;?>
                        <?php endforeach; ?>
                    </ul> 
                </div>
            </div>
        </div>
       
    
    <br>
   
    <?php if(!empty($filter) && !empty($type)) : ?>

        <h3>Showing results similar to <?php echo $filter ?> with filter <?php echo $allfoodtype[$type]?></h3>
    <?php elseif(!empty($type)) : ?>
        <h3>Filter By <?php echo $allfoodtype[$type]?></h3>
    <?php elseif(!empty($filter)) :?>
        <h3>Showing results similar to <?php echo $filter ?></h3>
    <?php endif ;?>
   
    <div class="outer-container">
        <div class="menu-container">
        <?php foreach($food as $fooddata) : 

            Modal::begin([
                'id'     => 'foodDetail',
                'size'   => 'modal-lg',
                // 'footer' => '<a href="#" class="btn btn-primary" data-dismiss="modal">Close</a>',
            ]);
                    
            Modal::end(); ?>

          
                <a href="<?php echo yii\helpers\Url::to(['/food/food-details','id'=>$fooddata['Food_ID'],'rid'=>$fooddata['Restaurant_ID']]); ?>" data-toggle="modal" data-target="#foodDetail"  data-img="<?php echo $fooddata['PicPath'];?>">
                    <div class="item">
                        <div class="img"><?php echo Html::img('@web/imageLocation/foodImg/'.$fooddata['PicPath']) ?></div>
                        <div class="inner-item">
                            <span class="foodName"><?php echo $fooddata['Name']; ?></span>
                            <span class="small-text pull-right stars" alt="<?php echo $fooddata['Rating']; ?>"><?php echo $fooddata['Rating']; ?></span>
                            <span><p class="price"><?php echo 'RM '.$fooddata['Price']; ?></p></span>
                            <span><p class="rname"><?php echo $fooddata['restaurant']['Restaurant_Name']; ?></p></span>
                            <p class="foodDesc"><?php echo $fooddata['Description']; ?></p>
                            <?php foreach($fooddata['foodType']as $type): ?>
                            <span class="tag"><?php echo $type['Type_Desc'].'&nbsp;&nbsp;&nbsp;'; ?></span>
                            <?php endforeach; ?>
                        </div>
                       
                    </div>
                </a>
           
        <?php endforeach; ?>
        </div>
        <?php echo LinkPager::widget([
          'pagination' => $pagination,
          ]); ?>
    </div>
    
</div>

