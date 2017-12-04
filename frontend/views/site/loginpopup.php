<?php

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model \common\models\LoginForm */

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;

$this->title = 'Login';

?>

<div class="container-login">

            <?php $form = ActiveForm::begin(); ?>

                <?= $form->field($model, 'username')->textInput(['autofocus' => true])->label('Username or Email') ?>
                
                <?= $form->field($model, 'password')->passwordInput()->label('Password') ?>

                <?= $form->field($model, 'rememberMe')->checkbox() ?>

                <div id="form-group" class="form-group">
                    <?= Html::submitButton('Login', ['class' => 'raised-btn main-btn', 'name' => 'login-button']) ?>
                </div>

                <div id="forgotpassword" class="forgotpassword" style="color:#999;">
                     <?= Html::a('Forgot Your Password?', ['site/request-password-reset']) ?>.
                </div>

            <?php ActiveForm::end(); ?>
        

</div>