<?php

/* @var $this yii\web\View */
use dosamigos\chartjs\ChartJs;
use kartik\date\DatePicker;
use kartik\widgets\Select2;
use kartik\widgets\ActiveForm;
use yii\helpers\Html;

$this->title = 'Change Password';
?>
<div class="site-index">
    <div class="container">
        <div class="col-sm-8 userprofile-edit-input">
            <?php $form = ActiveForm::begin();?>
                <?= $form->field($model, 'old_password')->passwordInput()?>
                <?= $form->field($model, 'new_password')->passwordInput()?>
                <?= $form->field($model, 'repeat_password')->passwordInput() ?>
                <div class="form-group">
                    <?= Html::submitButton('Update', ['class' => 'raised-btn main-btn change-password-resize-btn']) ?>
               </div>
            <?php ActiveForm::end();?>
        </div>
    </div>
</div>