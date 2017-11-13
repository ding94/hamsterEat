<?php

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model \common\models\LoginForm */

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;

$this->title = 'Login';
?>
<style>

.container-login
{
    padding-top:20px;
    padding-left:22px;
}

.forgotpassword
{
    width:300px;
    margin-left:200px;
}

</style>
<div class="container-login">

            <?php $form = ActiveForm::begin(['id' => 'login-form']); ?>

                <?= $form->field($model, 'username')->textInput(['autofocus' => true, 'style'=>'width:320px;'])->label('Username or Email') ?>
                
                <?= $form->field($model, 'password')->passwordInput(['style'=>'width:320px;'])->label('Password') ?>

                <?= $form->field($model, 'rememberMe')->checkbox() ?>

                <div class="form-group">
                    <?= Html::submitButton('Login', ['class' => 'btn btn-primary', 'name' => 'login-button', 'style'=>'margin-left:112px; width:100px;']) ?>
                </div>

                <div class="forgotpassword" style="color:#999;">
                     <?= Html::a('Forgot Your Password?', ['site/request-password-reset']) ?>.
                </div>

            <?php ActiveForm::end(); ?>
        

</div>