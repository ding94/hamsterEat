<?php

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model \common\models\LoginForm */

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;

$this->title = 'Login';
?>
<style>
/* Login page css */


</style>
<div class="container-login">
  <div class="col-lg-6 col-lg-offset-3">
          <h1 id = "login">Login</h1>
            <?php $form = ActiveForm::begin(['id' => 'login-form']); ?>

                <?= $form->field($model, 'username')->textInput(['autofocus' => true])->label('Username or Email') ?>
                <br>
                
                <?= $form->field($model, 'password')->passwordInput()->label('Password') ?>

                <?= $form->field($model, 'rememberMe')->checkbox() ?>

                <div class="form-group">
                    <?= Html::submitButton('Login', ['class' => 'btn btn-primary', 'name' => 'login-button']) ?>
                </div>

                <div class="forgotpassword pull-right" style="color:#999;">
                     <?= Html::a('Forgot Your Password?', ['site/request-password-reset']) ?>.
                </div>

            <?php ActiveForm::end(); ?>
		  </div>
</div>