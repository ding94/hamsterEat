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
        <h2> Enter Your Restaurant's Location</h2>
        <?php $form = ActiveForm::begin(); ?>

        <?= $form->field($postcode, 'Area_Area')->widget(Select2::classname(), [
        'data' => $postcodeArray,
        'options' => ['placeholder' => 'Select an area ...']])->label('Area');
        ?>

        <?= Html::submitButton('Proceed', ['class' => 'button-three']) ?>
        <?php ActiveForm::end(); ?>
    </div>
</div>
