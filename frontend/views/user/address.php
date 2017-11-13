<?php
use yii\helpers\Html;
use yii\bootstrap\Modal;
use yii\helpers\Url;
use frontend\assets\UserAsset;
use kartik\widgets\ActiveForm;

UserAsset::register($this);
?>
    <?php $form = ActiveForm::begin(['id' => 'dynamic-form']); ?>
      <?= $form->field($model, 'recipient')->textInput() ?>
      <?= $form->field($model, 'contactno')->textInput() ?>
      <?= $form->field($model, 'address')->textArea() ?>
      <?= $form->field($model, 'city')->textInput() ?>
      <?= $form->field($model, 'postcode')->textInput() ?>
      <?= $form->field($model, 'level')->checkbox(['label' => 'Set as Primary Address','checked'=>1])->label(false); ?>
      <?= Html::submitButton($this->title, ['class' => 'btn btn-primary', 'name' => 'insert-button']) ?>
    <?php ActiveForm::end(); ?>

