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
            <div class="filter">
                <div class="filter-container">
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
                    <?php echo Html::a('<li>All</li>', ['show-by-food'])."&nbsp;&nbsp;"; ?>  
                        <?php foreach ($allfoodtype as $i=> $data) : ?>
                            <?php if(empty($filter)) :?>
                                <?php echo Html::a('<li>'.$data.'</li>', ['/Restaurant/default/show-by-food','type'=>$i])."&nbsp;&nbsp;"; ?>
                            <?php else :?>
                                <?php echo Html::a('<li>'.$data.'</li>', ['/Restaurant/default/show-by-food','type'=>$i ,'filter'=>$filter])."&nbsp;&nbsp;"; ?>
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
        <?php foreach($food as $k=>$fooddata) : 

            Modal::begin([
                'id'     => 'foodDetail',
                'size'   => 'modal-lg',
                // 'footer' => '<a href="#" class="btn btn-primary" data-dismiss="modal">Close</a>',
            ]);
                    
            Modal::end(); ?>

                <?php $imgdata = empty($fooddata->img) ? [Yii::getAlias('@web').'/imageLocation/DefaultRestaurant.jpg'] : $fooddata->img?>
                <a href="<?php echo yii\helpers\Url::to(['/food/food-details','id'=>$fooddata['Food_ID'],'rid'=>$fooddata['Restaurant_ID']]); ?>" data-backdrop-limit="1" data-toggle="modal" data-target="#foodDetail"  data-img=<?php echo json_encode($imgdata) ?>>
                    <div class="item">
                        <div class="page-img">
                            <img  class="img" src=<?php echo $fooddata->img[0]?> alt="">
                        </div>
                        <div class="inner-item">
                            <div class="foodName-div"><span class="foodName"><?php echo $fooddata['Name']; ?></span><span class="small-text stars" alt="<?php echo $fooddata['Rating']; ?>"><?php echo $fooddata['Rating']; ?></span></div>
                            <div class="price-div"><span class="price"><?php echo 'RM '.$fooddata['Price']; ?></span></div>
                            <div class="rname-div"><span class="rname"><?php echo $fooddata['restaurant']['Restaurant_Name']; ?></span></div>
                            <div class="foodDesc"><span class="foodDesc"><?php echo $fooddata['Description']; ?></span></div>
                            <div class="tag-div">
                            <?php foreach($fooddata['foodType']as $type): ?>
                            <span class="tag"><?php echo $type['Type_Desc'].'&nbsp;&nbsp;&nbsp;'; ?></span>
                            <?php endforeach; ?>
                            </div>
                        </div>
                    </div>
                </a>
           
        <?php endforeach; ?>
         <div class="grid-footer">
        <?php echo LinkPager::widget([
        'pagination' => $pagination,
    
    ]); ?>
      </div>
    </div>
</div>


    
