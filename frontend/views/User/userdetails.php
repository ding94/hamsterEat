<?php

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model \frontend\models\SignupForm */

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;

$this->title = 'Enter details';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="site-signup">
    <h1><?= Html::encode($this->title) ?></h1>

    <p>Please fill out the following fields to details:</p>

    <div class="row">
        <div class="col-lg-5">
            <?php $form = ActiveForm::begin(['id' => 'form-signup']); ?>
            
                <?= $form->field($detail, 'User_PicPath')->fileInput()->label('Picture') ?>

                <?= $form->field($detail, 'User_FirstName')->textInput()->label('First Name') ?>

                <?= $form->field($detail, 'User_LastName')->textInput()->label('Last Name') ?>

                <?= $form->field($detail, 'User_ContactNo')->textInput()->label('Contact Number') ?>

                 <?= $form->field($address, 'User_Area1')->textInput()->label('Area') ?>

                 <?= $form->field($address, 'User_Street1')->textInput()->label('Street') ?>

                 <?= $form->field($address, 'User_HouseNo1')->textInput()->label('House Number') ?>

                 <?= $form->field($address, 'User_Postcode1')->textInput()->label('Postcode') ?>

                 <?= $form->field($address, 'User_Area2')->textInput()->label('Area (Address 2)') ?>

                 <?= $form->field($address, 'User_Street2')->textInput()->label('Street (Address 2)') ?>

                 <?= $form->field($address, 'User_HouseNo2')->textInput()->label('House Number (Address 2)') ?>

                 <?= $form->field($address, 'User_Postcode2')->textInput()->label('Postcode (Address 2)') ?>

                 <?= $form->field($address, 'User_Area3')->textInput()->label('Area (Address 3)') ?>

                 <?= $form->field($address, 'User_Street3')->textInput()->label('Street (Address 3)') ?>

                 <?= $form->field($address, 'User_HouseNo3')->textInput()->label('House Number (Address 3)') ?>

                 <?= $form->field($address, 'User_Postcode3')->textInput()->label('Postcode (Address 3)') ?>

              

                <div class="form-group">
                    <?= Html::submitButton('Done', ['class' => 'btn btn-primary', 'name' => 'signup-button']) ?>
                </div>

            <?php ActiveForm::end(); ?>
        </div>
    </div>
</div>