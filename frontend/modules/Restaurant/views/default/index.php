<?php
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\LinkPager;
use common\models\Restauranttype;
use kartik\widgets\ActiveForm;
use frontend\assets\StarsAsset;
use frontend\assets\RestaurantDefaultIndexAsset;
use frontend\assets\CartAsset;
use yii\bootstrap\Modal;
use kartik\widgets\Select2;

$this->title = "Available Restaurants";
StarsAsset::register($this);
RestaurantDefaultIndexAsset::register($this);
CartAsset::register($this);


Modal::begin([
      'options' => [
        'id' => 'add-modal',
        'tabindex' => false // important for Select2 to work properly
      ],
      'header' => '<h2 class="modal-title">Please choose delivery place</h2>',
      'size'   => 'modal-md',
]);

/*
* NEED FOR SELECT2 widget display in pop up modal
*/
echo Select2::widget([
    'name' => 'state_40',
    'data' => [1=>1],
    'options' => ['placeholder' => 'Select a state ...'],
    'pluginOptions' => [
        'allowClear' => true
    ],
]);
Modal::end();

?>

<div class="container" id="group-area-index">
    <h1>Order Food for Delivery </h1>
    <?php echo Html::a('<i class="fa fa-home"> Restaurant</i>', ['index'], ['class'=>'btn btn-default','style'=>'background-color:#FFDA00;pointer-events: none;']); ?>
	   <?php echo Html::a('<i class="fa fa-thumbs-up"> Food</i>', ['show-by-food'], ['class'=>'btn btn-default']); ?>
   
	 <?php  $cookies = Yii::$app->request->cookies;
            $halal = $cookies->getValue('halal');
			$session = Yii::$app->session;
     ?>          
	  
     <?php echo Html::a("Change to: ". $name = $halal == 0 ? 'Halal' : 'Non-halal',['/Restaurant/default/changecookie','type'=>$halal == 0 ? 1 : 0],['class'=>'hl','style'=>'float:right;color:red;font-style: italic;'])?>
        <span class="s" style="float:right;padding-left:10px;padding-right:10px;">|</span>
		<?php echo Html::a('Change Place', ['/Restaurant/default/addsession','page'=>'index2'], ['id'=>'cp','data-toggle'=>'modal','data-target'=>'#add-modal','style'=>'color:red;font-size:14px;float:right;font-style: italic;']); ?>  
			 <span class="area" style="float:right;padding-right:8px;"> <?php echo $session['area'] ?></span>
      
	<input type="checkbox" id="sidebartoggler" name="" value="">
    <div class="page-wrap">
      <!-- <label for="sidebartoggler" class="toggle"><i class="fa fa-sliders" aria-hidden="true">&nbsp;Filter</i></label>-->
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
                  <?php echo Html::a("Change to: ". $name = $halal == 0 ? 'Halal' : 'Non-halal',['/Restaurant/default/changecookie','type'=>$halal == 0 ? 1 : 0])?>
                </li>
              </ul>
            </div>
	  </div>
	  <a href="#top" class="scrollToTop"></a>
      <div class="filter">
        <div class="filter container">
          <div class="input-group">
            <?php $form = ActiveForm::begin(['id' => 'form-searchrestaurant','method' => 'get']) ?>
              <div class="input-group">
                <input id="food-nickname" class="form-control" name="filter" placeholder="Search Restaurant" type="text"><span class="input-group-btn"><button type="submit" class="btn btn-default icon-button"><i class="fa fa-search"></i>
                </button></span>
              </div>
           
            <?php ActiveForm::end(); ?>
          </div>
          <div class ="filter-name">
            <p><i class="fa fa-sliders"> Filter By</i></p>
          </div>
          <ul class ="filter-list">
          <?php echo Html::a('<li>All</li>', ['index'])."&nbsp;&nbsp;"; ?>
            <?php foreach ($allrestauranttype as $i=> $data) : ?>
              <?php if(empty($filter)) :?>
                <?php echo Html::a('<li>'.$data.'</li>', ['/Restaurant/default/index' ,'type'=>$i])."&nbsp;&nbsp;"; ?>
              <?php else :?>
                 <?php echo Html::a('<li>'.$data.'</li>', ['/Restaurant/default/index','type'=>$i ,'filter'=>$filter])."&nbsp;&nbsp;"; ?>
              <?php endif ;?>
            <?php endforeach; ?>
          </ul>
        </div>
      </div>
    </div>
<br>

  <?php if(!empty($filter) && !empty($type)) : ?>

        <h3>Showing results similar to <?php echo $filter ?> with filter <?php echo $allrestauranttype[$type]?></h3>
    <?php elseif(!empty($type)) : ?>
        <h3>Filter By <?php echo $allrestauranttype[$type]?></h3>
    <?php elseif(!empty($filter)) :?>
        <h3>Showing results similar to <?php echo $filter ?></h3>
    <?php endif ;?>

    <div class="outer-container">
      <div class="menu-container">
        <?php foreach($restaurant as $data) :?>
          <a href="<?php echo yii\helpers\Url::to(['restaurant-details','rid'=>$data['Restaurant_ID']]); ?>">
            <div class="list">
              <?php $picpath = $data['Restaurant_RestaurantPicPath']; 
                if (is_null($data['Restaurant_RestaurantPicPath'])){
                  $picpath = "DefaultRestaurant.jpg";
                }
              ?>
              <th rowspan = "5">
                <?php echo Html::img('@web/imageLocation/'.$picpath, ['class' => 'img-responsive img']) ?>
              </th>
              <div class="restaurant-name">
                <span class="name">
                  <?php echo $data['Restaurant_Name']; ?>
                    <div class="rating">
                      <span class="small-text stars">
                        <?php echo $data['Restaurant_Rating']; ?>
                      </span>
                    </div>
                </span>
              </div>
              <ul class="tag">
                <?php if ($data['Restaurant_Pricing'] == 1){ ?>
                <li class="none">$</li>
                <?php } else if ($data['Restaurant_Pricing'] == 2){ ?>
                <li class= "none"> $ $ </li>
                <?php } else { ?>
                <li class= "none"> $ $ $ </li>
                <?php } 
                  foreach ($data['restaurantType'] as $type) :
                ?>
                <li><?php echo $type['Type_Name']; ?></li>
                <?php endforeach; ?>
              </ul>            
            </div>
          </a>
        <?php endforeach; ?>
      </div>
    </div>
    <?php echo LinkPager::widget([
          'pagination' => $pagination,
    ]); ?>
</div>