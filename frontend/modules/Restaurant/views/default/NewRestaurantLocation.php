<?php

/* @var $this yii\web\View */
use yii\bootstrap\ActiveForm;
use yii\helpers\Html;

$this->title = 'Restaurant Location';
?>
<div class="site-index">

    <div class="jumbotron">
        <h1>Enter Your Restaurant's Location</h1>
        <?php if($postcode['detectArea'] == 0) :?>
        <?php $form = ActiveForm::begin(['id' => 'area']); ?>
        <?php else :?>
        <?php $form = ActiveForm::begin(['action' =>['default/new-restaurant-details'],'id' => 'area']); ?>
        <?php endif ;?>
        <?= $form->field($postcode, 'Area_Postcode')->textInput(['autofocus' => true])->label('Postcode') ?>
        <?php if( $postcode['detectArea'] == 1) :?>
        <?= $form->field($postcode, 'Area_Area')->dropDownList($list) ?>
        <?php endif ;?>
        <?= Html::submitButton('Proceed', ['class' => 'btn btn-primary', 'name' => 'proceed-button']) ?>

        <?php ActiveForm::end(); ?>

    </div>
</div>
