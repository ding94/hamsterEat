<?php

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model \frontend\models\SignupForm */

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;

$this->title = 'Signup';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="site-signup">
    <h1><?= Html::encode($this->title) ?></h1>

    <p>Please fill out the following fields to signup:</p>

    <div class="row">
        <div class="col-lg-5">
            <?php $form = ActiveForm::begin(['id' => 'form-signup']); ?>

                <?= $form->field($model, 'username')->textInput(['autofocus' => true]) ?>
                
                <?= $form->field($model, 'password')->passwordInput() ?>
               
                <?= $form->field($model, 'email') ?>

                <?= $form->field($model1, 'DeliveryMan_CarPlate')->label('Car Plate')?>

                <?= $form->field($model1, 'DeliveryMan_LicenseNo')->label('License Number')?>

                <?= $form->field($model1, 'DeliveryMan_VehicleType')->dropdownList([ 'M'=>'Motorcycle', 'C'=>'Car', 'V'=>'Van'],['prompt' => 'Select Vehicle Type'])->label('Vehicle Type')?>

                

                

            

                <div class="form-group">
                    <?= Html::submitButton('Signup', ['class' => 'btn btn-primary', 'name' => 'signup-button']) ?>
                </div>
                

            <?php ActiveForm::end(); ?>
        </div>
    </div>
</div>
