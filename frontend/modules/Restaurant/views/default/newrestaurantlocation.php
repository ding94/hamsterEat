<?php

/* @var $this yii\web\View */
use kartik\widgets\ActiveForm;
use kartik\widgets\Select2;
use kartik\widgets\DepDrop;
use yii\helpers\Url;
use yii\helpers\Html;

$this->title = 'New Restaurant Location';

?>
<div class="container">
    <div class="jumbotron">
        <h2><?= Yii::t('m-restaurant',"Enter Your Restaurant's Location")?></h2>
        <?php $form = ActiveForm::begin(); ?>

        <?= $form->field($postcode, 'Area_Area')->widget(Select2::classname(), [
        'data' => $postcodeArray,
        'options' => ['placeholder' => Yii::t('m-restaurant','Select an area ...')]])->label(Yii::t('cart','Area'));
        ?>

        <?= Html::submitButton(Yii::t('rating','Proceed'), ['class' => 'button-three']) ?>
        <?php ActiveForm::end(); ?>
    </div>
</div>
