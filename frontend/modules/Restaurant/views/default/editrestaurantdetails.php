<?php

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model \frontend\models\SignupForm */

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use kartik\widgets\Select2;
use frontend\assets\EditRestaurantDetailsAsset;
use yii\bootstrap\Modal;

$this->title = "Edit ".$restaurantdetails['Restaurant_Name']."'s Details";
EditRestaurantDetailsAsset::register($this); ?>
<style>
@media(max-width: 480px){
#location-modal .modal-content{
    margin:auto;
width:298px;
}
}
</style>
<body>

<?php Modal::begin([
            'header' => '<h2 class="modal-title">Edit '.$restaurantdetails['Restaurant_Name'].' Location</h2>',
            'id'     => 'location-modal',
            'size'   => 'modal-sm',
            //'footer' => '<a href="#" class="btn btn-primary" data-dismiss="modal">Close</a>',
    ]);
    
    Modal::end() ?>

</body> <?php

if (!is_null($restArea))
{
    $restaurantdetails['Restaurant_AreaGroup']=$restArea;
}

if (!is_null($postcodechosen))
{
    $restaurantdetails['Restaurant_Postcode']=$postcodechosen;
}

if (!is_null($areachosen))
{
    $restaurantdetails['Restaurant_Area']=$areachosen;
}
?>
<div id="edit-restaurant-details-container" class="container">
    <div class="edit-restaurant-details-header">
        <div class="edit-restaurant-details-header-title"><?= Html::encode($this->title) ?></div>
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
                <ul id="edit-restaurant-details-nav" class="nav nav-pills nav-stacked">
                <?php if ($staff['RmanagerLevel_Level'] == 'Owner'){ ?>
                    <li role="presentation"><?php echo Html::a("View Earnings",['show-monthly-earnings', 'rid'=>$restaurantdetails['Restaurant_ID']],['class'=>'btn-block'])?></li>
                <?php }
                    if ($staff['RmanagerLevel_Level'] == 'Owner' || $staff['RmanagerLevel_Level'] == 'Manager') { ?>
                        <li role="presentation" class="active"><?php echo Html::a("Edit Details",['edit-restaurant-details', 'rid'=>$restaurantdetails['Restaurant_ID'], 'restArea'=>$restaurantdetails['Restaurant_AreaGroup'], 'areachosen'=>$restaurantdetails['Restaurant_Area'], 'postcodechosen'=>$restaurantdetails['Restaurant_Postcode']],['class'=>'btn-block'])?></li>
                        <li role="presentation"><?php echo Html::a("Manage Staffs",['manage-restaurant-staff', 'rid'=>$restaurantdetails['Restaurant_ID']],['class'=>'btn-block'])?></li>
                        <li role="presentation"><?php echo Html::a("Restaurants Orders",['/order/restaurant-orders', 'rid'=>$restaurantdetails['Restaurant_ID']],['class'=>'btn-block'])?></li>
                        <li role="presentation"><?php echo Html::a("Restaurants Orders History",['/order/restaurant-order-history', 'rid'=>$restaurantdetails['Restaurant_ID']],['class'=>'btn-block'])?></li>
                        <li role="presentation"><?php echo Html::a("Manage Menu",['/food/menu', 'rid'=>$restaurantdetails['Restaurant_ID'],'page'=>'menu'],['class'=>'btn-block'])?></li>
                    <?php } elseif ($staff['RmanagerLevel_Level'] == 'Operator'){ ?>
                        <li role="presentation"><?php echo Html::a("Restaurants Orders",['/order/restaurant-orders', 'rid'=>$restaurantdetails['Restaurant_ID']],['class'=>'btn-block'])?></li>
                        <li role="presentation"><?php echo Html::a("Restaurants Orders History",['/order/restaurant-order-history', 'rid'=>$restaurantdetails['Restaurant_ID']],['class'=>'btn-block'])?></li>
                    <?php } ?>
                </ul>
            </div>
        </div>
        <div id="edit-restaurant-details-content" class="col-sm-10">
            <?php $form = ActiveForm::begin(['id' => 'form-signup']); ?>

                <?= $form->field($restaurantdetails, 'Restaurant_Name')->textInput()->label('Restaurant Name') ?>

                <?= $form->field($restaurantdetails, 'Restaurant_LicenseNo')->textInput()->label('Restaurant License No') ?>
                
                <strong>Restaurant Area</strong><br><?php echo $restaurantdetails['Restaurant_Area']; ?><br><br>

                <strong>Restaurant Postcode</strong><br><?php echo $restaurantdetails['Restaurant_Postcode']; ?><br><br>

                <strong>Restaurant Group Area</strong><br><?php echo $restaurantdetails['Restaurant_AreaGroup']; ?><br><br>

                <?php echo Html::a('Edit Area', ['edit-restaurant-area', 'rid'=>$restaurantdetails['Restaurant_ID']], ['class'=>'btn btn-default','data-toggle'=>'modal','data-target'=>'#location-modal']); ?><br><br>

                <?php echo '<label class="control-label">Type</label>';
                        echo Select2::widget([
                            'name' => 'Type_ID',
                            'value' => $chosen,
                            'data' => $type,
                            'showToggleAll' => false,
                            'options' => ['placeholder' => 'Select a type ...', 'multiple' => true],
                            'pluginOptions' => [
                                'tags' => true,
                                'maximumInputLength' => 10,
                                'maximumSelectionLength' => 3,
                            ],
                        ]);
                ?>
                <br>
                <br>

                <?= $form->field($restaurantdetails, 'Restaurant_Pricing')->radioList(["1"=>'Less than RM 10',"2"=>'More than RM 10', "3"=>'More Than RM 100'])->label('Average Food Prices') ?>
          
                <?= $form->field($restaurantdetails, 'Restaurant_RestaurantPicPath')->fileInput()->label('Picture') ?>

                <div class="form-group">
                    <?= Html::submitButton('Save', ['class' => 'btn btn-primary', 'name' => 'save-button']) ?>
                </div>

            <?php ActiveForm::end(); ?>
        </div>
    </div>
</div>