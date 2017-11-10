<?php

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model \common\models\LoginForm */

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;

$this->title = 'Login';
?>
<div class="site-login">
    <div class="col-lg-6 col-lg-offset-3" style="text-align:center">
    <h1><?= Html::encode($this->title) ?></h1>
<br>
 </div>
    <div class="container">
  <div class="col-lg-6 col-lg-offset-3">
            <?php $form = ActiveForm::begin(['id' => 'login-form']); ?>

                <?= $form->field($model, 'username')->textInput(['autofocus' => true])->label('Username or Email') ?>

                <?= $form->field($model, 'password')->passwordInput()->label('Password') ?>

                <?= $form->field($model, 'rememberMe')->checkbox() ?>

                <div style="color:#999;margin:1em 0">
                     <?= Html::a('Forgot Your Password?', ['site/request-password-reset']) ?>.
                </div>

                <div class="form-group">
                    <?= Html::submitButton('Login', ['class' => 'btn btn-primary', 'name' => 'login-button']) ?>
                </div>

            <?php ActiveForm::end(); ?>
        </div>
		  </div>
</div>
