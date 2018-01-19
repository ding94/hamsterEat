<?php

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model \common\models\LoginForm */

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;

$this->title = Yii::t('common','Login');
?>
<style>
/* Login page css */


</style>
<div class="container-login">
  <div class="col-lg-6 col-lg-offset-3">
          <h1 id = "login"><?= Yii::t('common','Login') ?></h1>
            <?php $form = ActiveForm::begin(['id' => 'login-form']); ?>

                <?= $form->field($model, 'username')->textInput(['autofocus' => true])->label(Yii::t('site','Username or Email')) ?>
                <br>
                
                <?= $form->field($model, 'password')->passwordInput()->label(Yii::t('site','Password')) ?>

                <?= $form->field($model, 'rememberMe')->checkbox() ?>

                <div class="form-group">
                    <?= Html::submitButton(Yii::t('site','Login'), ['class' => 'raised-btn main-btn', 'name' => 'login-button']) ?>
                </div>

                <div class="forgotpassword pull-right" style="color:#999;">
                     <?= Html::a(Yii::t('site','Forgot Your Password?'), ['site/request-password-reset']) ?>.
                </div>

            <?php ActiveForm::end(); ?>
		  </div>
</div>