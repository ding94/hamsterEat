<?php
use yii\helpers\Html;
use kartik\widgets\ActiveForm;
use common\widgets\Alert;
use frontend\assets\UserAsset;
/* @var $this yii\web\View */

$this->title = 'Change Password';
UserAsset::register($this);
?>

<div id="userprofile" class="row">
   <div class="userprofile-header">
        <div class="userprofile-header-title"><?php echo Html::encode($this->title)?></div>
    </div>
    <div class="userprofile-detail">
        <div class="col-sm-2 ">
           <ul class="nav nav-pills nav-stacked">
                <li role="presentation" ><?php echo Html::a("Edit User Profile",['/user/userdetails'],['class'=>'btn-block userprofile-edit-left-nav'])?></li>
                <li role="presentation" class="active"><a href="#" class="btn-block userprofile-edit-left-nav">Change password</a></li>
            </ul>
        </div>
        <div class="col-sm-8 userprofile-edit-input">
            <?php $form = ActiveForm::begin(); ?>
            
                <?php $form = ActiveForm::begin();?>
		<?= $form->field($model, 'old_password')->passwordInput() ?>
		<?= $form->field($model, 'new_password')->passwordInput() ?>
		<?= $form->field($model, 'repeat_password')->passwordInput()->label("Confirm New Password") ?>
    	<div class="form-group">
	        <?= Html::submitButton('Update', ['class' => 'btn btn-success']) ?>
	        <?php echo Html::a("Back" ,['/user/user-profile'],['class'=>'btn btn-primary'])?>
	   </div>
	<?php ActiveForm::end();?>


            <?php ActiveForm::end(); ?>
        </div>
    </div>
</div>