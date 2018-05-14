<?php

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model \frontend\models\SignupForm */

use yii\helpers\Html;
use yii\helpers\Url;
use kartik\widgets\Select2;
use yii\bootstrap\ActiveForm;
use frontend\assets\UserAsset;

$this->title = Yii::t('user','Phone details');
UserAsset::register($this);
?>
<?= Html::hiddenInput('url',Url::toRoute(['/phone/validate']))?>
<div class="profile">
    <div id="userprofile" class="row">
   <div class="userprofile-header">
        <div class="userprofile-header-title"><?php echo Html::encode($this->title)?></div>
    </div>
    <div class="userprofile-detail">
        <div class="col-sm-2">
            <div class="dropdown-url">
                <?php 
                    echo Select2::widget([
                        'name' => 'url-redirect',
                        'hideSearch' => true,
                        'data' => $link,
                        'options' => [
                            'placeholder' => Yii::t('common','Go To ...'),
                            'multiple' => false,

                        ],
                        'pluginEvents' => [
                             "change" => 'function (e){
                                location.href =this.value;
                            }',
                        ]
                    ])
                ;?>
            </div>
            <div class="nav-url">
                <ul class="nav nav-pills nav-stacked">
                    <li role="presentation"><?php echo Html::a(Yii::t('user','Edit User Profile'),['/user/userdetails'],['class'=>'btn-block userprofile-edit-left-nav'])?></li>
                    <li role="presentation" class="active"><a href="#" class="btn-block userprofile-edit-left-nav"><?=Yii::t('user','Change Contact Number')?></a></li>
                    <li role="presentation"><?php echo Html::a(Yii::t('user','Update Email'),['/user/email-detail'],['class'=>'btn-block userprofile-edit-left-nav'])?></li>
                    <li role="presentation"><?php echo Html::a(Yii::t('user','Change Password'),['/user/changepassword'],['class'=>'btn-block userprofile-edit-left-nav'])?></li>
                </ul>
            </div>
        </div>
    </div>
        <div class="col-sm-8 userprofile-edit-input">
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