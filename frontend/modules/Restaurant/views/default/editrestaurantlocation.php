<?php

/* @var $this yii\web\View */
use kartik\widgets\ActiveForm;
use kartik\widgets\Select2;
use yii\helpers\Url;
use yii\helpers\Html;


$rid = $restaurantdetails['Restaurant_ID'];
?>
<style>
/* Edit location css */
#select-postcode
{
    width:250px;
}

#select-area
{
    width:250px;
}

</style>

<div class="container">
        <?php $form = ActiveForm::begin(); ?>
        <div id="select-postcode">
        <?= $form->field($postcode, 'Area_Area')->widget(Select2::classname(), [
        'data' => $postcodeArray,
        'options' => ['placeholder' => 'Select an area ...','id'=>'postcode-select-edit']])->label('Area');
        ?>
        </div>
        <?= Html::submitButton('Save', ['class' => 'button-three', 'style'=>'margin-left:43px;']) ?>
        <?php ActiveForm::end(); ?>
</div>
