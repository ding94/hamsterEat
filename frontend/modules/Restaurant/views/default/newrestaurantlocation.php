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

        <?= $form->field($postcode, 'Area_Postcode')->widget(Select2::classname(), [
        'data' => $postcodeArray,
        'options' => ['placeholder' => 'Select a postcode ...','id'=>'postcode-select-edit']])->label('Postcode');
        ?>
        <?= $form->field($postcode,'Area_Area')->widget(DepDrop::classname(), [
            'type'=>DepDrop::TYPE_SELECT2,
            'options' => ['id'=>'area-select-edit','placeholder' => 'Select an area ...'],
            'pluginOptions'=>[
                'depends'=>['postcode-select-edit'],
                'url'=>Url::to(['/Restaurant/default/get-area'])
            ],
            ]); ?>

        <?= Html::submitButton('Proceed', ['class' => 'button-three']) ?>
        <?php ActiveForm::end(); ?>
    </div>
</div>
