<?php
use yii\helpers\Html;
use yii\helpers\Url;
use frontend\controllers\CartController;
use common\models\food\Foodstatus;
use frontend\assets\FoodMenuAsset;
use frontend\assets\StarsAsset;
use frontend\assets\CartAsset;
use yii\bootstrap\Modal;
use frontend\assets\FoodServiceAsset;
use kartik\widgets\Select2;
use yii\widgets\LinkPager;
use kartik\widgets\FileInput;
use yii\widgets\ActiveForm;

$this->title = $rname."'s".' '.Yii::t('food','Menu');
FoodMenuAsset::register($this);
StarsAsset::register($this);
FoodServiceAsset::register($this);

Modal::begin([
      'header' => '<h2 class="modal-title">'.Yii::t('food','Please Provide Reason').'</h2>',
      'id'     => 'add-modal',
      'size'   => 'modal-md',
      'footer' => '<a href="#" class="raised-btn alternative-btn" data-dismiss="modal">'.Yii::t('common','Close').'</a>',
]);
Modal::end();
?>
<div id="food-menu-container" class="container">
    <div class="food-menu-header">
        <div class="food-menu-header-title"><?= Html::encode($this->title) ?></div>
    </div>
	
    <div class="content">
        <div class="col-sm-2">
            <div class="dropdown-url">
                <?php 
                    echo Select2::widget([
                        'name' => 'url-redirect',
                        'hideSearch' => true,
                        'data' => $link,
                        'options' => [
                            'placeholder' => 'Go To ...',
                            'multiple' => false,

                        ],
                        'pluginEvents' => [
                             "change" => 'function (e){
                                location.href =this.value;
                            }',
                        ]
                    ])
                ;?>
            </div>
            <div class="nav-url">
              <ul id="food-menu-nav" class="nav nav-pills nav-stacked">
                <?php foreach($link as $url=>$name):?>
                    <li role="presentation" class=<?php echo $name=="Manage Menu" ? "active" :"" ?>>
                        <a class="btn-block" href=<?php echo $url?>><?php echo $name?></a>
                    </li>   
                  <?php endforeach ;?>
              </ul>
            </div>
        </div>
		<a href="#top" class="scrollToTop"></a>
        <div id="food-menu-content" class="col-sm-10">
          <div class="top-button-div">
            <span><?php echo Html::a(Yii::t('food','Insert Food'), ['/food/insert-food','rid'=>$rid], ['class'=>'raised-btn main-btn']); ?></span>
            <span> 
              <?php if ($restaurant['Restaurant_Status'] == "Closed"): ?>
              <?=Html::a('Resume Resturant Operate', Url::to(['/Restaurant/restaurant/resume-restaurant', 'id'=>$restaurant['Restaurant_ID']]), ['id'=>'resume','data-confirm'=>"Do you want to Resume Operate?",'class'=>'resize-btn raised-btn btn-success'])?>
              <?php elseif($restaurant['Restaurant_Status'] == "Operating"): ?>
              <?=Html::a('Pause Resturant Operate', Url::to(['/Restaurant/restaurant/pauserestaurant', 'id'=>$restaurant['Restaurant_ID'],'item'=>1]), ['id'=>'pause','data-confirm'=>"Do you want to Pause Operate?",'class'=>'resize-btn raised-btn btn-danger'])?>  
              <?php endif ?>
            </span>
          </div>
          <div class="outer-container" id="outer">
            <div class="menu-container" id="menucon">
              <?php foreach ($menu as $menu){ 
                $status = Foodstatus::find()->where('Food_ID=:id',[':id'=>$menu['Food_ID']])->one();
                 if ($status["Status"] >=0 ):
              ?>
              <div class="outer-item">
                <div class="item-no-border">
                  <div class="img">
                         <img src=<?php echo $menu->singleImg ?> alt="">
                  </div>
                  <div class="inner-item">
                    <div class="foodName-div"><span class="foodName"><?php echo $menu['Name']; ?></span><span class="small-text stars" alt="<?php echo $menu['Rating']; ?>"><?php echo $menu['Rating']; ?></span></div>
                    <div class="foodDesc-div"><p class="foodDesc"><?= Yii::t('food','Description') ?>: <?php echo $menu['Description']; ?></p></div>
                    <!--<div class="ingredient-div"><p>Ingredients: <?php echo $menu['Ingredient']?></p></div> -->
                    <div class="nickname-div"><p>Nick Name: <?php echo $menu['Nickname']?></p></div>
                  </div>
                </div>
                  <?php
                  echo Html::a('', ['/food/edit-food','id'=>$menu['Food_ID']], ['class'=>'raised-btn btn-lg main-btn fa fa-pencil edit-button']); 
                  if (!empty($status)) :
                      if ($status['Status'] == 0) :
                        echo Html::a(Yii::t('food','Resume Food Service'), Url::to(['/Restaurant/restaurant/active', 'id'=>$menu['Food_ID'],'item'=>2]), ['id'=>'res','data-confirm'=>Yii::t('food','Do you want to Resume Operate?'),'class'=>'raised-btn btn-success']);?>
                        <?= Html::a(Yii::t('food','Delete').'<i class="fa fa-times" aria-hidden="true"></i>',['/food/delete','fid'=>$menu['Food_ID']],['class' => 'raised-btn delete-btn btn-danger',
                          'data' => ['confirm' => Yii::t('food','Are you sure you want to permenant delete this item?'),'method' => 'post',],]) ?>
                    <?php 
                      elseif ($status['Status'] == 1) :
                        echo Html::a(Yii::t('food','Pause Food Service'), Url::to(['/Restaurant/restaurant/providereason', 'id'=>$menu['Food_ID'],'rid'=>$rid,'item'=>2]), ['id'=>'res','class'=>'raised-btn btn-danger','data-toggle'=>'modal','data-target'=>'#add-modal']);
                      endif;
                  endif;?>

                <?php
                  
                  Modal::begin([
                    'header'=>'Food Image Uploader',
                    'toggleButton' => [
                        'label'=>'Show Image/Upload Image', 'class'=>'raised-btn upload-btn'
                    ],
                  ]);
                  
                  $form1 = ActiveForm::begin([
                    'options'=>['enctype'=>'multipart/form-data'], // important
                   
                  ]);

                  echo FileInput::widget([
                      'name' => 'foodimg',

                      'options'=>[
                          'multiple'=>true
                      ],
                      'pluginOptions' => [
                        'initialPreview' => $menu->img,
                        'initialPreviewConfig' => $menu->captionImg['data'],
                        'initialPreviewAsData'=>true,
                        'uploadUrl' => Url::to(['/food-img/upload']),
                        'uploadExtraData'=>[
                          'id' => $menu['Food_ID'],
                        ],
                        'showRemove' => false,
                        'overwriteInitial'=>$menu->captionImg['header'],
                        'maxFileCount' => 3,
                        'pluginLoading' => true,
                        'allowedFileExtensions' => ['jpg','png','jpeg'],
                      ]

                    ]);
                  ActiveForm::end();
                  Modal::end();
                  ?>
              </div>
              <?php 
              endif;
                } ?>
            </div>
          </div>
          <?php echo LinkPager::widget([
          'pagination' => $pagination,
          ]); ?>
        </div>
    </div>
</div>