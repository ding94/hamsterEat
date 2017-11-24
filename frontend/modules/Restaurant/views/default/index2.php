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
<style>
#toggle {
  display: block;
  width: 28px;
  height: 30px;
  margin: 30px auto 10px;
}

#toggle span:after,
#toggle span:before {
  content: "";
  position: absolute;
  left: 0;
  top: -9px;
}
#toggle span:after{
  top: 9px;
}
#toggle span {
  position: relative;
  display: block;
}

#toggle span,
#toggle span:after,
#toggle span:before {
  width: 100%;
  height: 5px;
  background-color: #888;
  transition: all 0.3s;
  backface-visibility: hidden;
  border-radius: 2px;
}

/* on activation */
#toggle.on span {
  background-color: transparent;
}
#toggle.on span:before {
  transform: rotate(45deg) translate(5px, 5px);
}
#toggle.on span:after {
  transform: rotate(-45deg) translate(7px, -8px);
}
#toggle.on + #menu {
  opacity: 1;
  visibility: visible;
}

/* menu appearance*/
#menu {
  position: relative;
  color: #999;
  width: 100px;
  padding: 10px;
  margin: auto;
  font-family: "Segoe UI", Candara, "Bitstream Vera Sans", "DejaVu Sans", "Bitstream Vera Sans", "Trebuchet MS", Verdana, "Verdana Ref", sans-serif;
  text-align: center;
  border-radius: 4px;
  background: white;
  box-shadow: 0 1px 8px rgba(0,0,0,0.05);
  /* just for this demo */
  opacity: 0;
  visibility: hidden;
  transition: opacity .4s;
  float:right;
}
#menu:after {
  position: absolute;
  top: -15px;
  left: 95px;
  content: "";
  display: block;
  border-left: 15px solid transparent;
  border-right: 15px solid transparent;
  border-bottom: 20px solid white;
}
ul, li, li a {
  list-style: none;
  display: block;
  margin: 0;
  padding: 0;
}
li a {
  padding: 5px;
  color: #888;
  text-decoration: none;
  transition: all .2s;
}
li a:hover,
li a:focus {
  background: #1ABC9C;
  color: #fff;
}


/* demo styles */
body { 
margin-top: 3em; 
background: #eee; 
color: #555; 
font-family: "Open Sans", "Segoe UI", Helvetica, Arial, sans-serif; }
p, p a { 
font-size: 12px;
text-align: center; 
color: #888; }
</style>
 <script src="https://code.jquery.com/jquery-1.10.2.js"></script>
<div class="container" id="group-area-index2">
    <h1 style="padding-top:10px;">Order Food for Delivery <?php echo Html::a('Change Place', ['/Restaurant/default/addsession','page'=>'index2'], ['class'=>'btn btn-default','data-toggle'=>'modal','data-target'=>'#add-modal']); ?></h1>
  
        <?php echo Html::a('<i class="fa fa-home"> Restaurant</i>', ['index', 'groupArea'=>$groupArea], ['class'=>'btn btn-default']);?>
		<?php echo Html::a('<i class="fa fa-cutlery"> Food</i>', ['show-by-food', 'groupArea'=>$groupArea], ['class'=>'btn btn-default','style'=>'background-color:#FFDA00;pointer-events: none;']); ?>       
	   <input type="checkbox" id="sidebartoggler" name="" value="">
        <div class="page-wrap">

	   <label for="sidebartoggler" class="toggle"><!--<i class="fa fa-sliders" aria-hidden="true">&nbsp;Filter</i>-->
		<!--<a href="#top" title="Go to top of page"><span><i class="fa fa-chevron-up fa-2x" aria-hidden="true"></i></span>-->
<a href="#menu" id="toggle"><span></span></a>

<div id="menu">
  <ul>
    <li> <input type="checkbox" id="sidebartoggler" name="" value=""><i class="fa fa-sliders" aria-hidden="true">&nbsp;Filter</i></li>
    <li>About</li>
    <li>Contact</li>
  </ul>
</div>
</label>
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



<script>
var theToggle = document.getElementById('toggle');

// based on Todd Motto functions
// https://toddmotto.com/labs/reusable-js/

// hasClass
function hasClass(elem, className) {
	return new RegExp(' ' + className + ' ').test(' ' + elem.className + ' ');
}
// addClass
function addClass(elem, className) {
    if (!hasClass(elem, className)) {
    	elem.className += ' ' + className;
    }
}
// removeClass
function removeClass(elem, className) {
	var newClass = ' ' + elem.className.replace( /[\t\r\n]/g, ' ') + ' ';
	if (hasClass(elem, className)) {
        while (newClass.indexOf(' ' + className + ' ') >= 0 ) {
            newClass = newClass.replace(' ' + className + ' ', ' ');
        }
        elem.className = newClass.replace(/^\s+|\s+$/g, '');
    }
}
// toggleClass
function toggleClass(elem, className) {
	var newClass = ' ' + elem.className.replace( /[\t\r\n]/g, " " ) + ' ';
    if (hasClass(elem, className)) {
        while (newClass.indexOf(" " + className + " ") >= 0 ) {
            newClass = newClass.replace( " " + className + " " , " " );
        }
        elem.className = newClass.replace(/^\s+|\s+$/g, '');
    } else {
        elem.className += ' ' + className;
    }
}

theToggle.onclick = function() {
   toggleClass(this, 'on');
   return false;
}
</script>