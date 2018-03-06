<?php

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model \frontend\models\SignupForm */

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use kartik\widgets\Select2;
use frontend\assets\NewRestaurantAsset;

$this->title = Yii::t('m-restaurant','New Restaurant');
NewRestaurantAsset::register($this);
?>
<div class="site-signup">
    <div class="col-lg-8 col-lg-offset-4">
        <h1><?= Html::encode($this->title) ?></h1>

        <p><?= Yii::t('m-restaurant','Please enter the details of your Restaurant')?>:</p>
    </div>
    <div class="container">
        <div class="col-lg-6 col-lg-offset-3">
            <?php $form = ActiveForm::begin(['id' => 'form-newrestaurant']); ?>
            <div class="row">
                <div class="col-md-6">
                    <?= $form->field($resname, 'en_name')->textInput(['autofocus' => true])->label(Yii::t('common','English Name')) ?>
                </div>
                <div class="col-md-6">
                    <?= $form->field($resname, 'zh_name')->textInput(['autofocus' => true])->label(Yii::t('common','Mandarin Name')) ?>
                </div>
            </div>
            
            <?= $form->field($restaurant, 'Restaurant_UnitNo')->label(Yii::t('m-restaurant','Restaurant Unit No')) ?>

            <?= $form->field($restaurant, 'Restaurant_Street')->label(Yii::t('m-restaurant','Restaurant Street')) ?> <br>

            <?php echo "<strong>".Yii::t('m-restaurant',"Restaurant Area"); ?> </strong> <br> <?php echo $area; ?> <br> <br>

            <?= $form->field($foodjunction, 'Type_ID')->inline(true)->radioList(["22"=>'Halal',"23"=>'Non-Halal'])->label('<strong>'.Yii::t('common','Type').'</strong>') ?>

            <div class="five">
                <?php 
                        echo Select2::widget([
                            'name' => 'Type_ID',
                            'data' => $type,
                            'showToggleAll' => false,
                            'options' => ['placeholder' => Yii::t('m-restaurant','Select another two types ...'), 'multiple' => true],
                            'pluginOptions' => [
                                'tags' => true,
                                'maximumInputLength' => 10,
                                'maximumSelectionLength' => 2,
                            ],
                        ]);
                ?>
                <br>
                <br>
            </div>
            <?= $form->field($restaurant, 'Restaurant_Pricing')->inline(true)->radioList([
            "1"=>Yii::t('m-restaurant','Less than').' RM 10',
            "2"=>Yii::t('m-restaurant','More than').' RM 10', 
            "3"=>Yii::t('m-restaurant','More than').' RM 100'])->label(Yii::t('m-restaurant','Average Food Prices')) ?>

            <?= $form->field($restaurant, 'Restaurant_LicenseNo')->label(Yii::t('m-restaurant','Restaurant License No')) ?>

            <?= $form->field($restaurant, 'Restaurant_RestaurantPicPath')->fileInput()->label(Yii::t('m-restaurant','Restaurant Picture')) ?>
            
            <div class="form-group">
                <?= Html::submitButton(Yii::t('m-restaurant','Create Restaurant'), ['class' => 'raised-btn main-btn', 'name' => 'newrestaurant-button']) ?>                
            </div>
            <?php ActiveForm::end(); ?>
        </div>
    </div>
</div>