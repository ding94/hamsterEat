<?php

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model \common\models\LoginForm */

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;

$this->title = Yii::t('common','Login');

?>

<div class="container-login">

            <?php $form = ActiveForm::begin(); ?>

                <?= $form->field($model, 'username')->textInput(['autofocus' => true])->label(Yii::t('site','Username or Email')) ?>
                
                <?= $form->field($model, 'password')->passwordInput()->label(Yii::t('common','Password')) ?>

                <?= $form->field($model, 'rememberMe')->checkbox()->label(Yii::t('common','Remember Me')) ?>

                <div id="form-group" class="form-group">
                    <?= Html::submitButton(Yii::t('common','Login'), ['class' => 'raised-btn main-btn', 'name' => 'login-button']) ?>
                </div>

                <div id="forgotpassword" class="forgotpassword" style="color:#999;">
                     <?= Html::a(Yii::t('site','Forgot Your Password?'), ['site/request-password-reset']) ?>.
                </div>

            <?php ActiveForm::end(); ?>
        

</div>