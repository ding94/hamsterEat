<?php

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model \frontend\models\SignupForm */

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use frontend\assets\UserAsset;

$this->title = 'Edit details';
UserAsset::register($this);
?>
<div id="userprofile" class="row">
   <div class="userprofile-header">
        <div class="userprofile-header-title"><?php echo Html::encode($this->title)?></div>
    </div>
    <div class="userprofile-detail">
        <div class="col-sm-2 ">
           <ul class="nav nav-pills nav-stacked">
                <li role="presentation" class="active"><a href="#" class="btn-block userprofile-edit-left-nav">Edit User Profile</a></li>
                <li role="presentation"><?php echo Html::a("Change Password",['/user/changepassword'],['class'=>'btn-block userprofile-edit-left-nav'])?></li>
            </ul>
        </div>
        <div class="col-sm-8 userprofile-edit-input">
            <?php $form = ActiveForm::begin(['id' => 'form-signup']); ?>
            
                <?= $form->field($detail, 'User_PicPath')->fileInput()->label('Picture') ?>
                <div class="row">
                    <div class="col-sm-6">
                          <?= $form->field($detail, 'User_FirstName')->textInput()->label('First Name') ?>
                    </div> 
                    <div class="col-sm-6">
                            <?= $form->field($detail, 'User_LastName')->textInput()->label('Last Name') ?>
                    </div>
                </div>

                <?= $form->field($detail, 'User_ContactNo')->textInput()->label('Contact Number') ?>

                <div class="form-group">
                    <?= Html::submitButton('Update Profile', ['class' => 'btn btn-success', 'name' => 'signup-button']) ?>
                    <?php echo Html::a("Back" ,['/user/user-profile'],['class'=>'btn btn-primary'])?>
                </div>

            <?php ActiveForm::end(); ?>
        </div>
    </div>
</div>