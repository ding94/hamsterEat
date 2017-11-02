<?php

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model \frontend\models\SignupForm */

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;

$this->title = 'Edit details';
?>
<div class="site-signup">
  <div class="col-lg-6 col-lg-offset-1" style="text-align:center">
    <h1><?= Html::encode($this->title) ?></h1>

    <p>Please fill out the following fields to details:</p>
</div>
    <div class="container">
    <div class="col-lg-6 col-lg-offset-1">
            <?php $form = ActiveForm::begin(['id' => 'form-signup']); ?>
            
                <?= $form->field($detail, 'User_PicPath')->fileInput()->label('Picture') ?>

                <?= $form->field($detail, 'User_FirstName')->textInput()->label('First Name') ?>

                <?= $form->field($detail, 'User_LastName')->textInput()->label('Last Name') ?>

                <?= $form->field($detail, 'User_ContactNo')->textInput()->label('Contact Number') ?>

              

                <div class="form-group">
                    <?= Html::submitButton('Done', ['class' => 'btn btn-primary', 'name' => 'signup-button']) ?>
                </div>

            <?php ActiveForm::end(); ?>
        </div>
    </div>
</div>