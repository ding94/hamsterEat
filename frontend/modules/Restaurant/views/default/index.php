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
use frontend\controllers\CommonController;

$this->title = Yii::t('m-restaurant',"Available Restaurants");
StarsAsset::register($this);
RestaurantDefaultIndexAsset::register($this);

Modal::begin([
      'options' => [
        'id' => 'add-modal',
        'tabindex' => false // important for Select2 to work properly
      ],
      'header' => '<h2 class="modal-title">'.Yii::t('m-restaurant','Please choose delivery place').'</h2>',
      'size'   => 'modal-md',
]);

/*
* NEED FOR SELECT2 widget display in pop up modal
*/
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
            'header' => '<h2 class="modal-title">'.Yii::t('layouts','Placed Orders').'</h2>',
            'id'     => 'add-modal2',
            'size'   => 'modal-lg',
            'footer' => '<a href="#" class="btn btn-primary" data-dismiss="modal">'.Yii::t('common','Close').'</a>',
    ]);
    
    Modal::end()

?>

<div class="container" id="group-area-index">
    <h1><?= Yii::t('m-restaurant','Order Food for Delivery')?></h1>   
	 <?php  $cookies = Yii::$app->request->cookies;
            $halal = $cookies->getValue('halal');
			$session = Yii::$app->session;
     ?>      
	  
     <?php echo Html::a(Yii::t('m-restaurant','Change to').": ". $name = $halal == 0 ? 'Halal' : 'Non-halal',['/Restaurant/default/changecookie','type'=>$halal == 0 ? 1 : 0],['class'=>'hl a-change'])?>
        <span class="s" style="float:right;padding-left:10px;padding-right:10px;">|</span>
		<?php echo Html::a(Yii::t('m-restaurant','Change Place'), ['/Restaurant/default/addsession','page'=>'index2'], ['id'=>'cp','class'=>'a-change','data-toggle'=>'modal','data-target'=>'#add-modal ','style'=>'font-size:14px']); ?>  
			 <span class="area" style="float:right;padding-right:8px;"> <?php echo $session['area'] ?></span>
      
	<input type="checkbox" id="sidebartoggler" name="" value="">
    <div class="page-wrap">
      <!-- <label for="sidebartoggler" class="toggle"><i class="fa fa-sliders" aria-hidden="true">&nbsp;Filter</i></label>-->
	  <div class="tm">
      <div id="menu">
        <ul>
          <li> <a class="toggle"><?= Yii::t('m-restaurant','Filter')?></a></li>
				  <li><?php echo Html::a(Yii::t('m-restaurant','Cahnge Place'), ['/Restaurant/default/addsession','page'=>'index2'], ['data-toggle'=>'modal','data-target'=>'#add-modal']); ?></li>
          <li>
            <?php $cookies = Yii::$app->request->cookies;
                  $halal = $cookies->getValue('halal');
            ?>           
            <?php echo Html::a(Yii::t('m-restaurant','Change to').": ". $name = $halal == 0 ? 'Halal' : 'Non-halal',['/Restaurant/default/changecookie','type'=>$halal == 0 ? 1 : 0])?>
          </li>
         
        </ul>
      </div>
	  </div>
	  <a href="#top" class="scrollToTop"></a>
      <div class="filter">
        <div id="restaurant-food-switch" class="btn-group btn-group-justified" role="group">
          <div class="btn-group" role="group">
            <?php echo Html::a('<i class="fa fa-home">'.Yii::t('m-restaurant','Restaurant').'</i>', ['index'], ['type'=>'button','class'=>'btn btn-default','style'=>'background-color:#FFDA00;pointer-events: none;']); ?>
          </div>
          <div class="btn-group" role="group">
            <?php echo Html::a('<i class="fa fa-thumbs-up">'.Yii::t('m-restaurant','Food').'</i>', ['show-by-food'], ['type'=>'button','class'=>'btn btn-default']); ?>
          </div>
        </div>

        <?php $form = ActiveForm::begin(['id' => 'form-searchrestaurant','method' => 'get']) ?>
          <div class="input-group">
            <input id="food-nickname" class="form-control" name="filter" placeholder="Search Restaurant" type="text"><span class="input-group-btn"><button type="submit" class="btn btn-default icon-button"><i class="fa fa-search"></i>
            </button></span>
          </div>
        <?php ActiveForm::end(); ?>

        <div id="accordion">
          <div class="panel panel-default">
            <div class="panel-heading" id="headingOne">
              <a class="btn" data-toggle="collapse" data-target="#filter-box" aria-expanded="true" aria-controls="filter-box">
                <i class="fa fa-sliders"></i>
                <?php echo Yii::t('m-restaurant','Filter') ?>
                <i class="fa fa-plus"></i>
              </a>
            </div>
            <div id="filter-box" class="collapse" data-parent="#accordion">
              <div class="panel-body">
                <ul class ="filter-list">
                <div class="btn main-btn" onclick="uncheckAll()"><?php echo Yii::t('common','Clear Filter') ?></div>
                  <?php foreach ($allrestauranttype as $i=> $data) : ?>
                    <?php if(empty($filter)) :?>
                    <li>
                      <div class="checkbox">
                        <input id="<?php echo $data ?>" type="checkbox" name="<?php echo $data ?>" value="<?php echo $data ?>" class="checkbox-custom type">
                        <label for="<?php echo $data ?>" class="type-label"><span class="checkbox-custom-label"></span><span><?php echo $data ?></span></label>
                      </div>
                    </li>
                    <?php else :?>
                    <li>
                      <div class="checkbox">
                        <input id="<?php echo $data ?>" type="checkbox" name="<?php echo $data ?>" value="<?php echo $data ?>" class="checkbox-custom type">
                        <label for="<?php echo $data ?>" class="type-label"><span class="checkbox-custom-label"></span><span><?php echo $data ?></span></label>
                      </div>
                    </li>
                    <?php endif ;?>
                  <?php endforeach; ?>
                </ul>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  <br>
  
  <?php if(!empty($filter) && !empty($type)) : ?>
      <div class="filter-info-text">
        <span>Showing results similar to <?php echo $filter ?> with filter <?php echo $allrestauranttype[$type]?></span>
      </div>
    <?php elseif(!empty($type)) : ?>
      <div class="filter-info-text">
        <span>Filter By <?php echo $allrestauranttype[$type]?></span>
      </div>
    <?php elseif(!empty($filter)) :?>
      <div class="filter-info-text">
        <span>Showing results similar to <?php echo $filter ?></span>
      </div>
    <?php endif ;?>
  
    <div class="outer-container">
      <div class="item-na-container hideItem"><span style="text-align: center;">No Restaurant Available For This Category ....</span></div>
      <div class="menu-container">
          <a href="<?php echo yii\helpers\Url::to(['#']); ?>">
            <div class="list" data-type="loren,ipsum,">
              <div class="page-img">
                  <?php echo Html::img(Yii::getAlias('@web').'/'.Yii::$app->params['defaultRestaurantImg'], ['class' => 'img'])?>
              </div>
              <div class="inner-item">
                <div class="restaurant-name">
                  <span class="name">
                    ???
                  </span>
                </div>
              </div>        
            </div>
          </a>
        <?php foreach($restaurant as $data) :?>
          <?php 
          $string = '';
          foreach($data['restaurantType'] as $k):
            $string .= $k['Type_Name'].',';
          endforeach;
           ?>
          <a href="<?php echo yii\helpers\Url::to(['restaurant-details','rid'=>$data['Restaurant_ID']]); ?>">
            <div class="list" data-type="<?php echo $string; ?>">
              <div class="page-img">
                  <?php echo Html::img($data->img, ['class' => 'img'])?>
              </div>
              <div class="inner-item">
                <div class="restaurant-name">
                  <span class="name">
                    <?php echo CommonController::getRestaurantName($data['Restaurant_ID']); ?>
                  </span>
                  <span class="small-text stars">
                    <?php echo $data['Restaurant_Rating']; ?>
                  </span>
                </div>
                <div class="tag-div">
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
              </div>        
            </div>
          </a>
        <?php endforeach; ?>
      </div>
    </div>
    <div class="container">
      <?php echo LinkPager::widget([
          'pagination' => $pagination,
    ]); ?>
    </div>
</div>