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

$this->title = $rname."'s"." Menu";
FoodMenuAsset::register($this);
StarsAsset::register($this);
FoodServiceAsset::register($this);

Modal::begin([
      'header' => '<h2 class="modal-title">Please Provide Reason</h2>',
      'id'     => 'add-modal',
      'size'   => 'modal-md',
      'footer' => '<a href="#" class="raised-btn alternative-btn" data-dismiss="modal">Close</a>',
]);
Modal::end();
?>
 <script src="https://code.jquery.com/jquery-1.10.2.js"></script>
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
          <?php echo Html::a('Insert Food', ['/food/insert-food','rid'=>$rid], ['class'=>'raised-btn main-btn']); ?>
          
          <div class="outer-container" id="outer">
            <div class="menu-container" id="menucon">
              <?php foreach ($menu as $menu){ 
                $status = Foodstatus::find()->where('Food_ID=:id',[':id'=>$menu['Food_ID']])->one();
                 if ($status["Status"] >=0 ):
              ?>
              <div class="outer-item">
                <div class="item-no-border">
                  <div class="img"><?php echo Html::img('@web/imageLocation/foodImg/'.$menu['PicPath']); ?></div>
                  <div class="inner-item">
                    <span class="foodName"><?php echo $menu['Name']; ?></span>
                    <p class="foodDesc">Description: <?php echo $menu['Description']; ?></p>
                    <p>Ingredients: <?php echo $menu['Ingredient']?></p>
                    <p>Nick Name: <?php echo $menu['Nickname']?></p>
                    <span class="small-text stars" alt="<?php echo $menu['Rating']; ?>"><?php echo $menu['Rating']; ?></span>
                  </div>
                </div>
                <?php
                  echo Html::a('', ['/food/edit-food','id'=>$menu['Food_ID']], ['class'=>'raised-btn btn-lg main-btn fa fa-pencil edit-button']); 
                  if (!empty($status)) :
                      if ($status['Status'] == 0) :
                        echo Html::a('Resume Food Service', Url::to(['/Restaurant/restaurant/active', 'id'=>$menu['Food_ID'],'item'=>2]), ['id'=>'res','data-confirm'=>"Do you want to Resume Operate?",'class'=>'raised-btn btn-success']);
                      elseif ($status['Status'] == 1) :
                        echo Html::a('Pause Food Service', Url::to(['/Restaurant/restaurant/providereason', 'id'=>$menu['Food_ID'],'rid'=>$rid,'item'=>2]), ['id'=>'res','class'=>'raised-btn btn-danger','data-toggle'=>'modal','data-target'=>'#add-modal']);
                      endif;
                  endif;
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