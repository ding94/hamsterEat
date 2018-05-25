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


$this->title = Yii::t('m-restaurant',"Available Food");

StarsAsset::register($this);
RestaurantDefaultIndex2Asset::register($this);


Modal::begin([
    'options' => [
        'id' => 'add-modal',
        'tabindex' => false // important for Select2 to work properly
    ],
    'header' => '<h2 class="modal-title">'.Yii::t('m-restaurant','Please choose delivery place').'</h2>',
    'id'     => 'add-modal',
    'size'   => 'modal-md',
]);
echo Select2::widget([
    'name' => 'state_40',
    'data' => [1=>1],
    'options' => ['placeholder' => Yii::t('m-restaurant','Select a state ...')],
    'pluginOptions' => [
        'allowClear' => true
    ],
]);
Modal::end();

Modal::begin([
    'header' => '<h2 class="modal-title">'.Yii::t('common','Nickname').'</h2>',
    'id'     => 'orderQuantity',
    'size'   => 'modal-lg',
]);
Modal::end(); 

Modal::begin([
    'id'     => 'foodDetail',
    'size'   => 'modal-lg',
                // 'footer' => '<a href="#" class="btn btn-primary" data-dismiss="modal">Close</a>',
    ]);
                    
Modal::end(); 


?>
<?php  

    $cookies = Yii::$app->request->cookies;
    $halal = $cookies->getValue('halal');
    $session = Yii::$app->session;
    
    $url = Url::to(['/Restaurant/default/changecookie']);
?> 
<div class="container" id="group-area-index2">
    <h1 style="padding-top:10px;"><?= Yii::t('m-restaurant','Order Food for Delivery') ?> </h1>
     <!--  For Phone View-->
        <div class="btn-group" id="halal-btn" data-toggle="buttons"> 
          <?php if($name = $halal == 1){ ?>
            <label class="btn btn-default btn-on-ph btn-sm active" onclick="halalstatus(1,'<?php echo $url ?>')">
              <input type="radio" >Halal</label>
            <label class="btn btn-default btn-on-ph btn-sm " onclick="halalstatus(0,'<?php echo $url ?>')">
              <input type="radio" >Non-Halal</label>
          <?php }else{ ?>
              <label class="btn btn-default btn-on-ph  btn-sm " onclick="halalstatus(1,'<?php echo $url ?>')">
                <input type="radio">Halal</label>
              <label class="btn btn-default btn-on-ph btn-sm active" onclick="halalstatus(0,'<?php echo $url ?>')">
                <input type="radio" >Non-Halal</label>
          <?php } ?>  
        </div> 
    <div class="all-status">
        <div id="restaurant-food-switch" class="btn-group " role="group">
            <?php echo Html::a('<i class="fa fa-home">'.Yii::t('m-restaurant','Restaurant').'</i>', ['index'], ['type'=>'button','class'=>'btn btn-default restaurant-food']); ?>
            <?php echo Html::a('<i class="fa fa-thumbs-up">'.Yii::t('m-restaurant','Food').'</i>', ['show-by-food'], ['type'=>'button','class'=>'btn btn-default restaurant-food','style'=>'background-color:#FFDA00;pointer-events: none;']); ?>
        </div>

        <div class="btn-group hl a-change" data-toggle="buttons"> 
          <?php if($name = $halal == 1){ ?>
            <label class="btn btn-default btn-on-pc btn-sm active" onclick="halalstatus(1,'<?php echo $url ?>')">
              <input type="radio" >Halal</label>
            <label class="btn btn-default btn-off-pc btn-sm " onclick="halalstatus(0,'<?php echo $url ?>')">
              <input type="radio" >Non-Halal</label>
          <?php }else{ ?>
              <label class="btn btn-default btn-on-pc  btn-sm " onclick="halalstatus(1,'<?php echo $url ?>')">
                <input type="radio">Halal</label>
              <label class="btn btn-default btn-off-pc btn-sm active" onclick="halalstatus(0,'<?php echo $url ?>')">
                <input type="radio" >Non-Halal</label>
          <?php } ?>
        </div>
    </div>
  
      <div class="page-wrap">

      <!-- Didn't where to use this function  -->
      <!--  <div class="tm">
            <div id="menu">
              <ul>
                <li><?php echo Html::a(Yii::t('m-restaurant','Change Place'), ['/Restaurant/default/addsession','page'=>'index2'], ['data-toggle'=>'modal','data-target'=>'#add-modal']); ?></li>
                <li>
                  <?php  $cookies = Yii::$app->request->cookies;
                        $halal = $cookies->getValue('halal');
                  ?>           
                  <?php echo Html::a(Yii::t('m-restaurant','Change to').": ". $name = $halal == 0 ? 'Halal' : 'Non-halal',['/Restaurant/default/changecookie','type'=>$halal == 0 ? 1 : 0])?>
                </li>
              </ul>
            </div>
      </div> -->
        <!--<a href="#top" title="Go to top of page"><span><i class="fa fa-chevron-up fa-2x" aria-hidden="true"></i></span>-->
    <a href="#top" class="scrollToTop"></a>
    
    <br>
    <?php echo Html::hiddenInput('moreFood', $moreFood);?>
    <?php echo Html::hiddenInput('infinite-url', Url::to(['/Restaurant/default/load-more-food']));?>
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


    
