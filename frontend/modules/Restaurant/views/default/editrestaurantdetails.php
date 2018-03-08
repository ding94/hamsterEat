<?php

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model \frontend\models\SignupForm */

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use kartik\widgets\Select2;
use frontend\assets\EditRestaurantDetailsAsset;
use yii\bootstrap\Modal;
use kartik\widgets\FileInput;

$this->title = Yii::t('common','Edit')." ".$resname[$lan].' '.Yii::t('common',"'s ").Yii::t('common','Details');
EditRestaurantDetailsAsset::register($this); 
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
                            'placeholder' => Yii::t('common','Go To ...'),
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
                    <?php foreach($link as $url=>$name):?>
                        <li role="presentation" class=<?php echo $name=="Edit Details" ? "active" :"" ?>>
                            <a class="btn-block" href=<?php echo $url?>><?php echo Yii::t('m-restaurant',$name)?></a>
                        </li>   
                    <?php endforeach ;?>
                </ul>
            </div>
        </div>
        <div id="edit-restaurant-details-content" class="col-sm-10">
            <strong><?= Yii::t('m-restaurant','Restaurant Name') ?></strong><br>
            <div class="row">
                <?php foreach ($resname as $k => $value): ?>
                    <div class="col-md-5">
                        <?php if($k!='en') : ?>
                            <?php echo Yii::t('common','Mandarin'); ?> 
                        <?php else : ?>
                            <?php echo Yii::t('common','English'); ?> 
                        <?php endif;?>
                         : <?php echo $value; ?>
                    </div>
                <?php endforeach;?>
            </div>
            

            <br><br>
            <strong><?= Yii::t('m-restaurant','Restaurant License No')?></strong><br><?php echo $restaurantdetails['Restaurant_LicenseNo']; ?><br><br>
            <strong><?= Yii::t('m-restaurant','Restaurant Area')?></strong><br><?php echo $restaurantdetails['Restaurant_Area']; ?><br><br>

            <?php $form = ActiveForm::begin(['id' => 'form-signup']); ?>
            <?= $form->field($foodjunction, 'Type_ID')->inline(true)->radioList([$halal['ID']=>$halal['Type_Name'],$nonhalal['ID']=>$nonhalal['Type_Name']])->label('<strong>'.Yii::t('common','Type').'</strong>') ?>
                <?php echo Select2::widget([
                            'name' => 'Type_ID',
                            'value' => $chosen,
                            'data' => $type,
                            'showToggleAll' => false,
                            'options' => ['placeholder' => Yii::t('m-restaurant','Select a type ...'), 'multiple' => true],
                            'pluginOptions' => [
                                'tags' => true,
                                'maximumInputLength' => 10,
                                'maximumSelectionLength' => 2,
                            ],
                        ]);
                ?>
                <br>

                <?= $form->field($restaurantdetails, 'Restaurant_Pricing')->radioList(["1"=>Yii::t('m-restaurant','Less than').' RM 10',"2"=>Yii::t('m-restaurant','More than').' RM 10', "3"=>Yii::t('m-restaurant','More than').' RM 100'])->label(Yii::t('m-restaurant','Average Food Prices')) ?>
                
                <?php 
                    echo $form->field($upload, 'imageFile')->widget(FileInput::classname(), [
                        'options' => ['accept' => 'image/*'],
                       
                    ])->label(Yii::t('common','Upload Image'));
                ?>
              

                <div class="form-group">
                    <?= Html::submitButton(Yii::t('common','Save'), ['class' => 'raised-btn main-btn', 'name' => 'save-button']) ?>
                </div>
                <p style="color: red;"><?= Yii::t('m-restaurant','res-edit-info')?></p>
            <?php ActiveForm::end(); ?>
        </div>
    </div>
</div>