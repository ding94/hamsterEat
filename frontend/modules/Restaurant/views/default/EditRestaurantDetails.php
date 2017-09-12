<?php

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model \frontend\models\SignupForm */

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;

$this->title = "Edit Restaurant's Details";
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="site-signup">
    <h1><?= Html::encode($this->title) ?></h1>

    <p>Please fill out the following fields to details:</p>

    <div class="row">
        <div class="col-lg-5">
            <?php $form = ActiveForm::begin(['id' => 'form-signup']); ?>

                <?= $form->field($restaurantdetails, 'Restaurant_Name')->textInput()->label('Restaurant Name') ?>

                <?= $form->field($restaurantdetails, 'Restaurant_LicenseNo')->textInput()->label('Restaurant License No') ?>

                <?= $form->field($restaurantdetails, 'Restaurant_RestaurantPicPath')->fileInput()->label('Picture') ?>

                <div class="form-group">
                    <?= Html::submitButton('Done', ['class' => 'btn btn-primary', 'name' => 'save-button']) ?>
                </div>

            <?php ActiveForm::end(); ?>
        </div>
    </div>
</div>