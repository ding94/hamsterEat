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

$this->title = $rname."'s"." Menu";
FoodMenuAsset::register($this);
StarsAsset::register($this);
FoodServiceAsset::register($this);
CartAsset::register($this);

Modal::begin([
      'header' => '<h2 class="modal-title">Please Provide Reason</h2>',
      'id'     => 'add-session-modal',
      'size'   => 'modal-md',
      'footer' => '<a href="#" class="btn btn-primary" data-dismiss="modal">Close</a>',
]);
Modal::end();
?>

<div id="food-menu-container" class="container">
    <div class="food-menu-header">
        <div class="food-menu-header-title"><?= Html::encode($this->title) ?></div>
    </div>
    <div class="content">
        <div class="col-sm-2">
            <ul id="food-menu-nav" class="nav nav-pills nav-stacked">
                <?php if ($staff['RmanagerLevel_Level'] == 'Owner'){ ?>
                    <li role="presentation"><?php echo Html::a("View Earnings",['Restaurant/default/show-monthly-earnings', 'rid'=>$rid],['class'=>'btn-block'])?></li>
                <?php }
                    if ($staff['RmanagerLevel_Level'] == 'Owner' || $staff['RmanagerLevel_Level'] == 'Manager') { ?>
                    <li role="presentation"><?php echo Html::a("Edit Details",['Restaurant/default/edit-restaurant-details', 'rid'=>$rid, 'restArea'=>$restaurant['Restaurant_AreaGroup'], 'areachosen'=>$restaurant['Restaurant_Area'], 'postcodechosen'=>$restaurant['Restaurant_Postcode']],['class'=>'btn-block'])?></li>
                    <li role="presentation"><?php echo Html::a("Manage Staffs",['Restaurant/default/manage-restaurant-staff', 'rid'=>$rid],['class'=>'btn-block'])?></li>
                    <li role="presentation"><?php echo Html::a("Restaurants Orders",['/order/restaurant-orders', 'rid'=>$rid],['class'=>'btn-block'])?></li>
                    <li role="presentation"><?php echo Html::a("Restaurants Orders History",['/order/restaurant-order-history', 'rid'=>$rid],['class'=>'btn-block'])?></li>
                    <li role="presentation" class="active"><?php echo Html::a("Manage Menu",['/food/menu', 'rid'=>$rid,'page'=>'menu'],['class'=>'btn-block'])?></li>
                <?php } elseif ($staff['RmanagerLevel_Level'] == 'Operator'){ ?>
                    <li role="presentation"><?php echo Html::a("Restaurants Orders",['/order/restaurant-orders', 'rid'=>$rid],['class'=>'btn-block'])?></li>
                    <li role="presentation"><?php echo Html::a("Restaurants Orders History",['/order/restaurant-order-history', 'rid'=>$rid],['class'=>'btn-block'])?></li>
                <?php } ?>
            </ul>
        </div>
        <div id="food-menu-content" class="col-sm-10">
          <?php echo Html::a('Insert Food', ['/food/insert-food','rid'=>$rid], ['class'=>'btn btn-primary']); ?>
          <?php echo \yii\widgets\LinkPager::widget([
          'pagination' => $pagination,
          ]); ?>
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
                  echo Html::a('', ['/food/edit-food','id'=>$menu['Food_ID']], ['class'=>'btn-lg btn-primary fa fa-pencil edit-button']); 
                  if (!empty($status)) :
                      if ($status['Status'] == 0) :
                        echo Html::a('Resume Food Service', Url::to(['/Restaurant/restaurant/active', 'id'=>$menu['Food_ID'],'item'=>2]), ['id'=>'res','data-confirm'=>"Do you want to Resume Operate?",'class'=>'btn btn-success']);
                      elseif ($status['Status'] == 1) :
                        echo Html::a('Pause Food Service', Url::to(['/Restaurant/restaurant/providereason', 'id'=>$menu['Food_ID'],'rid'=>$rid,'item'=>2]), ['id'=>'res','class'=>'btn btn-danger','data-toggle'=>'modal','data-target'=>'#add-session-modal']);
                      endif;
                  endif;
                ?>
              </div>
              <?php 
              endif;
                } ?>
            </div>
          </div>
        </div>
    </div>
</div>