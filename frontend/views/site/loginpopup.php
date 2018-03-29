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
                
                <div class="form-group" style="margin-bottom:30px;">
                    <?php $authAuthChoice = yii\authclient\widgets\AuthChoice::begin([
                    'options' =>['style' => 'float:right;'],
                    'baseAuthUrl' => ['site/auth']
                    ]);?>
                    <ul class="auth-clients">
                        <?php foreach ($authAuthChoice->getClients() as $client): ?>
                            <li><?= $authAuthChoice->clientLink($client,
                                '<span class="fa fa-'.$client->getName().'"></span> Sign in with '.$client->getTitle(),
                                [
                                    'class' => 'btn btn-block btn-social btn-'.$client->getName(),
                                    ]) ?></li>
                                <?php endforeach; ?>
                            </ul>
                            <?php yii\authclient\widgets\AuthChoice::end(); ?>
                </div>

                <div id="forgotpassword" class="forgotpassword" style="color:#999;">
                     <?= Html::a(Yii::t('site','Forgot Your Password?'), ['site/request-password-reset']) ?>.
                </div>

            <?php ActiveForm::end(); ?>
        

</div>