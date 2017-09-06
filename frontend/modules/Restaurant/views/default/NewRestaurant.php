<?php

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model \frontend\models\SignupForm */

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;

$this->title = 'New Restaurant';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="site-signup">
    <h1><?= Html::encode($this->title) ?></h1>

    <p>Please enter the details of your Restaurant:</p>

    <div class="row">
        <div class="col-lg-5">
            <?php $form = ActiveForm::begin(['id' => 'form-newrestaurant']); ?>
                
                <?= $form->field($restaurant, 'Restaurant_Name')->textInput(['autofocus' => true]) ?>

                <?= $form->field($restaurant, 'Restaurant_UnitNo') ?>

                <?= $form->field($restaurant, 'Restaurant_Street') ?>

                <?= $form->field($restaurant, 'Restaurant_Tag') ?>

                <?= $form->field($restaurant, 'Restaurant_LicenseNo') ?>

                <?= Html::hiddenInput("restArea", $restArea); ?>
                <?= Html::hiddenInput("postcodechosen", $postcodechosen); ?>
                <?= Html::hiddenInput("areachosen", $areachosen); ?>
                
                
                <div class="form-group">
                    <?= Html::submitButton('Create Restaurant', ['class' => 'btn btn-primary', 'name' => 'newrestaurant-button']) ?>                
                </div>
            <?php ActiveForm::end(); ?>
        </div>
    </div>
</div>