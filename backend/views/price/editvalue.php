<?php
use yii\helpers\Html;
use kartik\widgets\ActiveForm;
use kartik\widgets\Select2;
use yii\helpers\Url;
?>

<?php $form = ActiveForm::begin(); ?>

<?= $form->field($conf, 'value')->textInput();?>
<?= $form->field($conf, 'id')->hiddenInput()->label(false);?>

<?= Html::submitButton('Edit', ['class' => 'btn btn-success']) ?>

<?php ActiveForm::end();?>