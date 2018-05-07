<?php

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model \frontend\models\SignupForm */

use yii\helpers\Html;
use yii\helpers\Url;
use yii\bootstrap\ActiveForm;
use frontend\assets\SignupAsset;
SignupAsset::register($this);

$this->title = Yii::t('site','User Signup');
?>
<div class="site-signup">
     <div class="col-lg-6 col-lg-offset-3" style="text-align:center">
    <h1><?= Html::encode($this->title) ?></h1>
    <?= Html::hiddenInput('url',Url::toRoute(['/phone/validate']))?>
    <p><?= Yii::t('site','Please fill out the following fields to signup') ?>:</p>
  </div>
    <div class="container">
   <div class="col-lg-6 col-lg-offset-3">
            <?php $form = ActiveForm::begin(['id' => 'form-signup']); ?>

                <?= $form->field($model, 'username')->textInput(['autofocus' => true])->label(Yii::t('common','Username')) ?>

                <?php //echo $form->field($model, 'email')->label(Yii::t('common','Email')) ?>

                <?= $form->field($userdetail, 'User_ContactNo')?>
                           
                <div class="col-lg-6">
                    <div clas="form-group">
                            <label class="control-label"></label>
                            <?= Html::a("Send Code",'#' ,['id'=>'signup-phone-validate','class' => 'raised-btn secondery-btn width-100']) ?> 
                        <div class="help-block"></div>
                    </div>
                </div>
                <div class="col-lg-6">
                    <div clas="form-group">
                        <?= $form->field($model, 'validate_code')->label('');?>
                    </div>
                </div>
                <?= $form->field($model, 'password')->passwordInput()->label(Yii::t('common','Password')) ?>

                <?= $form->field($employee, 'cid')->dropDownList($company,['prompt'=>'Please Choose Your Company'])->label(Yii::t('layouts','Company'))?>

                <div class="form-group">
                    <?= Html::submitButton(Yii::t('common','Signup'), ['class' => 'raised-btn main-btn', 'name' => 'signup-button']) ?> <br><br>
                </div>
            <?php ActiveForm::end(); ?>
        </div>
    </div>
</div>
