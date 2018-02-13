<?php
use yii\helpers\Html;
use kartik\widgets\ActiveForm;
use common\widgets\Alert;
use kartik\widgets\Select2;
use frontend\assets\UserAsset;
/* @var $this yii\web\View */

$this->title = Yii::t('user','Change Password');
UserAsset::register($this);
?>

<div class="profile">
<div id="userprofile" class="row">
   <div class="userprofile-header">
        <div class="userprofile-header-title"><?php echo Html::encode($this->title)?></div>
    </div>
    <div class="userprofile-detail">
        <div class="col-sm-2 ">
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
                    <li role="presentation" ><?php echo Html::a(Yii::t('user','Edit User Profile'),['/user/userdetails'],['class'=>'btn-block userprofile-edit-left-nav'])?></li>
                    <li role="presentation" class="active"><a href="#" class="btn-block userprofile-edit-left-nav"><?= Yii::t('user','Change Password') ?></a></li>
                </ul>
            </div>
        </div>
        </div>
        <div class="container">
        <div class="col-sm-8 userprofile-edit-input">
            <?php $form = ActiveForm::begin(); ?>
            
                <?php $form = ActiveForm::begin();?>
        <?= $form->field($model, 'old_password')->passwordInput()->label(Yii::t('user','Old Password')) ?>
        <?= $form->field($model, 'new_password')->passwordInput()->label(Yii::t('user','New Password')) ?>
        <?= $form->field($model, 'repeat_password')->passwordInput()->label(Yii::t('user','Confirm New Password')) ?>
        <div class="form-group">
            <?= Html::submitButton(Yii::t('user','Update'), ['class' => 'raised-btn main-btn change-password-resize-btn']) ?>
            <?php echo Html::a(Yii::t('common','Back'),['/user/user-profile'],['class'=>'raised-btn secondary-btn change-password-resize-btn'])?>
       </div>
    <?php ActiveForm::end();?>


            <?php ActiveForm::end(); ?>
        </div>
    </div>
</div>
</div>