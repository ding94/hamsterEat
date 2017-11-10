<?php
/* @var $this yii\web\View */

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use common\models\MonthlyUnix;
use frontend\controllers\CartController;
use kartik\widgets\Select2;
use frontend\assets\RestaurantEarningsAsset;

$this->title = "Restaurant Monthly Earnings";
RestaurantEarningsAsset::register($this);
?>
<div id="restaurant-earnings-container" class = "container">
    <div class="restaurant-earnings-header">
        <div class="restaurant-earnings-header-title"><?= Html::encode($this->title) ?></div>
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
                 <ul id="restaurant-earnings-nav" class="nav nav-pills nav-stacked">
                    <?php if ($staff['RmanagerLevel_Level'] == 'Owner'){ ?>
                        <li role="presentation" class="active"><?php echo Html::a("View Earnings",['show-monthly-earnings', 'rid'=>$rid],['class'=>'btn-block'])?></li>
                    <?php }
                    if ($staff['RmanagerLevel_Level'] == 'Owner' || $staff['RmanagerLevel_Level'] == 'Manager') { ?>
                        <li role="presentation"><?php echo Html::a("Edit Details",['edit-restaurant-details', 'rid'=>$rid, 'restArea'=>$restaurant['Restaurant_AreaGroup'], 'areachosen'=>$restaurant['Restaurant_Area'], 'postcodechosen'=>$restaurant['Restaurant_Postcode']],['class'=>'btn-block'])?></li>
                        <li role="presentation"><?php echo Html::a("Manage Staffs",['manage-restaurant-staff', 'rid'=>$rid],['class'=>'btn-block'])?></li>
                        <li role="presentation"><?php echo Html::a("Restaurants Orders",['/order/restaurant-orders', 'rid'=>$rid],['class'=>'btn-block'])?></li>
                        <li role="presentation"><?php echo Html::a("Restaurants Orders History",['/order/restaurant-order-history', 'rid'=>$rid],['class'=>'btn-block'])?></li>
                        <li role="presentation"><?php echo Html::a("Manage Menu",['/food/menu', 'rid'=>$rid,'page'=>'menu'],['class'=>'btn-block'])?></li>
                    <?php } elseif ($staff['RmanagerLevel_Level'] == 'Operator'){ ?>
                        <li role="presentation"><?php echo Html::a("Restaurants Orders",['/order/restaurant-orders', 'rid'=>$rid],['class'=>'btn-block'])?></li>
                        <li role="presentation"><?php echo Html::a("Restaurants Orders History",['/order/restaurant-order-history', 'rid'=>$rid],['class'=>'btn-block'])?></li>
                    <?php } ?>
                </ul>
            </div>
           
        </div>
    </div>
    <div id="restaurant-earnings-content" class = "col-sm-10">
    <?php $form = ActiveForm::begin(); ?>
        <div class="row earning-left">
            <div class="col-xs-12">
                 <label>Filter by Month</label>
            </div>
            <?php if($mode == 1) :?>
            <div id="month" class="col-xs-2">
                 <?= $form->field($selected, 'Month')->dropDownList($months, [ 'value'=>$currentmonth])->label('');?>
            </div>
            <div id="year" class="col-xs-2">
                 <?= $form->field($selected, 'Year')->dropDownList($year, ['value'=>$currentyear])->label(''); ?>
            </div>
            <div id="filter-button" class="col-xs-2">
                  <?= Html::submitButton('Filter', ['class' => 'btn btn-primary', 'name' => 'filter-button']); ?>   
            </div>
       <?php else :?>
            <div id="month" class="col-xs-2">
                  <?= $form->field($selected, 'Month')->dropDownList($months, [ 'value'=>$selectedmonth])->label(''); ?>
            </div>
            <div id="year" class="col-xs-2">
                  <?= $form->field($selected, 'Year')->dropDownList($year, ['value'=>$selectedyear])->label(''); ?>
            </div>
            <div id="filter-button" class="col-xs-2">
                   <?= Html::submitButton('Filter', ['class' => 'btn btn-primary', 'name' => 'filter-button']); ?>
            </div>
        <?php endif?>
        </div> 
	<?php  activeForm::end(); ?>
        
            <br>
            <table class= "table table-user-info col-sm-8" style= "border:1px solid black;">
                <tr>
                    <th><center> Month </th>
                    <th><center> Amount (RM) </th>
                </tr>
            <?php
                if ($mode == 1)
                { ?>
                    <tr>
                        <td><center><?php echo $currentmonth; ?></td>
                        <td><center><?php echo CartController::actionRoundoff1decimal($totalearnings); ?></td>
                    </tr>
              <?php  }
                else
                { ?>
                    <tr>
                        <td><center><?php echo $selectedmonth; ?></td>
                        <td><center><?php echo CartController::actionRoundoff1decimal($totalearnings); ?></td>
                    </tr>
              <?php  } ?>
            </table>
    </div>
</div>