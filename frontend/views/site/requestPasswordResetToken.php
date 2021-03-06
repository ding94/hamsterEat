<?php

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model \frontend\models\PasswordResetRequestForm */

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;

$this->title = Yii::t('site','Request password reset');
?>
<div class="site-request-password-reset">
 <div class="col-lg-6 col-lg-offset-3" style="text-align:center">
    <h1><?= Html::encode($this->title) ?></h1>

    <p><?= Yii::t('site','Please fill out your email.') ?> <?=Yii::t('site','A link to reset password will be sent there.') ?></p>
 </div>
    <div class="container">
    <div class="col-lg-6 col-lg-offset-3">
            <?php $form = ActiveForm::begin(['id' => 'request-password-reset-form']); ?>

                <?= $form->field($model, 'email')->textInput(['autofocus' => true]) ?>

                <div class="form-group">
                    <?= Html::submitButton(Yii::t('site','Send'), ['class' => 'raised-btn main-btn']) ?>
                </div>

            <?php ActiveForm::end(); ?>
        </div>
    </div>
</div>
