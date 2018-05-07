<?php

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model \frontend\models\SignupForm */

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use kartik\widgets\Select2;
use frontend\assets\UserAsset;
use kartik\widgets\FileInput;

$this->title = Yii::t('user','Edit details');
UserAsset::register($this);
?>

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
                    <li role="presentation" class="active"><a href="#" class="btn-block userprofile-edit-left-nav"><?= Yii::t('user','Edit User Profile') ?></a></li>
                    <li role="presentation"><?php echo Html::a(Yii::t('user','Change Password'),['/user/changepassword'],['class'=>'btn-block userprofile-edit-left-nav'])?></li>
                </ul>
            </div>
        </div>
    </div>
        <div class="col-sm-8 userprofile-edit-input">
            <?php $form = ActiveForm::begin(['id' => 'form-signup']); ?>
                
                <?php 
                    echo $form->field($upload, 'imageFile')->widget(FileInput::classname(), [
                        'options' => ['accept' => 'image/*'],
                    ])->label(Yii::t('common','Upload Image'));
                ?>
             
                <div class="row">
                    <div class="col-sm-6">
                          <?= $form->field($detail, 'User_FirstName')->textInput()->label(Yii::t('user','First Name')) ?>
                    </div> 
                    <div class="col-sm-6">
                            <?= $form->field($detail, 'User_LastName')->textInput()->label(Yii::t('user','Last Name')) ?>
                    </div>
                </div>

                <?= $form->field($detail, 'User_ContactNo')->textInput(['readOnly'=> true])->label(Yii::t('user','Contact Number')) ?>

                <div class="form-group">
                    <?= Html::submitButton(Yii::t('user','Update Profile'), ['class' => 'raised-btn main-btn change-password-resize-btn', 'name' => 'signup-button']) ?>
                    <?php echo Html::a(Yii::t('common','Back'),['/user/user-profile'],['class'=>'raised-btn secondary-btn change-password-resize-btn'])?>
                </div>

            <?php ActiveForm::end(); ?>
        </div>
    </div>
</div>