<?php

/* @var $this yii\web\View */
use kartik\widgets\ActiveForm;
use kartik\widgets\Select2;
use kartik\widgets\DepDrop;
use yii\helpers\Url;
use yii\helpers\Html;


$rid = $restaurantdetails['Restaurant_ID'];
?>
<style>

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
        <?= $form->field($postcode, 'Area_Postcode')->widget(Select2::classname(), [
        'data' => $postcodeArray,
        'options' => ['placeholder' => 'Select a postcode ...','id'=>'postcode-select-edit']])->label('Postcode');
        ?>
        </div>
        <div id ="select-area">
        <?= $form->field($postcode,'Area_Area')->widget(DepDrop::classname(), [
            'type'=>DepDrop::TYPE_SELECT2,
            'options' => ['id'=>'area-select-edit','placeholder' => 'Select an area ...'],
            'pluginOptions'=>[
                'depends'=>['postcode-select-edit'],
                'url'=>Url::to(['/Restaurant/default/get-area'])
            ],
            ]); ?>
        </div>
        <?= Html::submitButton('Save', ['class' => 'button-three', 'style'=>'margin-left:43px;']) ?>
        <?php ActiveForm::end(); ?>
</div>
