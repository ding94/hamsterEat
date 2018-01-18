<?php
use yii\helpers\Html;
use yii\helpers\Url;
use yii\bootstrap\Modal;
use kartik\widgets\ActiveForm;
use yii\widgets\LinkPager;
use frontend\assets\StarsAsset;
use frontend\assets\CartAsset;
use kartik\widgets\Select2;
use frontend\assets\RestaurantDefaultIndex2Asset;


$this->title = "Available Food";

StarsAsset::register($this);
RestaurantDefaultIndex2Asset::register($this);


Modal::begin([
    'options' => [
        'id' => 'add-modal',
        'tabindex' => false // important for Select2 to work properly
    ],
    'header' => '<h2 class="modal-title">Please choose delivery place</h2>',
    'id'     => 'add-modal',
    'size'   => 'modal-md',
]);
echo Select2::widget([
    'name' => 'state_40',
    'data' => [1=>1],
    'options' => ['placeholder' => 'Select a state ...'],
    'pluginOptions' => [
        'allowClear' => true
    ],
]);
Modal::end();

Modal::begin([
    'id'     => 'foodDetail',
    'size'   => 'modal-lg',
                // 'footer' => '<a href="#" class="btn btn-primary" data-dismiss="modal">Close</a>',
    ]);
                    
Modal::end(); 
?>

<div class="container" id="group-area-index2">
    <h1 style="padding-top:10px;">Order Food for Delivery </h1>

  
        <?php echo Html::a('<i class="fa fa-home"> Restaurant</i>', ['index'], ['class'=>'raised-btn']);?>
        <?php echo Html::a('<i class="fa fa-thumbs-up"> Food</i>', ['show-by-food'], ['class'=>'raised-btn','style'=>'background-color:#FFDA00;pointer-events: none;']); ?>       
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
       <div class="tm">
            <div id="menu">
              <ul>
                <li> <a class="toggle">Filter</a></li>
                <li><?php echo Html::a('Change Place', ['/Restaurant/default/addsession','page'=>'index2'], ['data-toggle'=>'modal','data-target'=>'#add-modal']); ?></li>
                <li>
                  <?php  $cookies = Yii::$app->request->cookies;
                        $halal = $cookies->getValue('halal');
                  ?>           
                  <?php echo Html::a("Change to: ". $name = $halal == 0 ? 'Halal' : 'Non-halal',['/Restaurant/default/changecookie','type'=>$halal == 0 ? 1 : 0])?>
                </li>
                <li><?php echo Html::a('<span class="glyphicon glyphicon-log-out"> Logout',['/site/logout'],['data-method'=>'post']);?></li>
              </ul>
            </div>
      </div>
        <!--<a href="#top" title="Go to top of page"><span><i class="fa fa-chevron-up fa-2x" aria-hidden="true"></i></span>-->
    <a href="#top" class="scrollToTop"></a>
    
    <br>
    <?php echo Html::hiddenInput('moreFood', $moreFood);?>
    <div class="outer-container">
        <div class="menu-container">
        <?php 
            foreach($food as $k=>$fooddata) : 
             echo Yii::$app->controller->renderPartial('_food',['fooddata'=>$fooddata]);    
            endforeach; 
        ?>
      </div>
    </div>
    <div class="ajax-load text-center" style="display:none">
        <p><img src="http://demo.itsolutionstuff.com/plugin/loader.gif">Loading More Food</p>
    </div>
</div>


    
