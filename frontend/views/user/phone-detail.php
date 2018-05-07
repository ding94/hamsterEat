<?php

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model \frontend\models\SignupForm */

use yii\helpers\Html;
use yii\helpers\Url;
use yii\bootstrap\ActiveForm;
use frontend\assets\UserAsset;

$this->title = Yii::t('user','Phone details');
UserAsset::register($this);
?>
<?= Html::hiddenInput('url',Url::toRoute(['/phone/validate']))?>
<div class="profile col-sm-6 col-lg-offset-3">
    <div id="userprofile" class="row">
    <div class="userprofile-header">
        <div class="userprofile-header-title"><?php echo Html::encode($this->title)?></div>
    </div>
        <div>
            <?php $form = ActiveForm::begin(['id' => 'form-signup']); ?>
                
                <?= $form->field($detail, 'User_ContactNo')->textInput()->label(Yii::t('user','Contact Number')) ?>
                <div class="col-lg-6">
                    <div clas="form-group">
                            <label class="control-label"></label>
                            <?= Html::a("Send Code",'#' ,['id'=>'signup-phone-validate','class' => 'raised-btn secondery-btn width-100']) ?> 
                        <div class="help-block"></div>
                    </div>
                </div>
                <div class="col-lg-6">
                    <div clas="form-group">
                        <?= $form->field($signup, 'validate_code')->label('');?>
                    </div>
                </div>

                <div class="form-group">
                    <?= Html::submitButton(Yii::t('user','Update Profile'), ['class' => 'raised-btn main-btn change-password-resize-btn', 'name' => 'signup-button']) ?>
                </div>

            <?php ActiveForm::end(); ?>
        </div>
    </div>
</div>