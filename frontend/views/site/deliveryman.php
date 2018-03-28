<?php

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model \frontend\models\SignupForm */

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;

$this->title = Yii::t('site','Delivery Man Signup');
?>
<div class="site-signup">
<div class="col-lg-6 col-lg-offset-3" style="text-align:center">
    <h1><?= Html::encode($this->title) ?></h1>

    <p><?= Yii::t('site','Please fill out the following fields to signup') ?>:</p>
 </div>
    <div class="container">
       <div class="col-lg-6 col-lg-offset-3">
            <?php $form = ActiveForm::begin(['id' => 'form-signup']); 

                echo $form->field($model, 'username')->textInput(['autofocus' => true]); 
                
                echo $form->field($model, 'password')->passwordInput();
               
                echo $form->field($model, 'email');

                echo $form->field($model1, 'DeliveryMan_CarPlate')->label('Car Plate');

                echo $form->field($model1, 'DeliveryMan_LicenseNo')->label('License     Number');

                echo $form->field($model1, 'DeliveryMan_VehicleType')->dropdownList([ 'Motorcycle'=>'Motorcycle', 'Car'=>'Car', 'Van'=>'Van'],['prompt' => 'Select Vehicle Type'])->label(Yii::t('site','Vehicle Type'));

                echo $form->field($model1, 'aid')->dropdownList($area,['prompt'=>'Select An Area']);
            ?>
      <div class="form-group">
                    <?= Html::submitButton('Signup', ['class' => 'raised-btn main-btn', 'name' => 'signup-button']) ?>
                </div>
            <?php ActiveForm::end(); ?>
        </div>
    </div>
</div>
