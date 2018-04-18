<?php

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model \frontend\models\CompanySignUpForm */

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\Url;
use frontend\assets\CompanyRegisterAsset;
CompanyRegisterAsset::register($this);

$this->title ='Company Signup';
?>
<div class="container">
  <div class="col-lg-6 col-lg-offset-3" style="text-align:center">
    <h1><?= Html::encode($this->title) ?></h1>
    <?= Html::hiddenInput('url',Url::toRoute(['/phone/validate']))?>
    <p><?php echo 'Please fill out the following fields to signup' ?></p>
  </div>
    <div class="container" >
        <div class="container">
  		  	<div class="col-lg-6 col-lg-offset-3">
		        <?php $form = ActiveForm::begin(['id' => 'form-companysignup']); ?>
	             	
   				    <fieldset>
       				 	<legend>User Details</legend>
       				 		<div class="col-lg-5">
        					<?= $form->field($model, 'username')->textInput(['autofocus' => true])->label(Yii::t('common','Username')) ?>
        					</div>
        					<div class="col-lg-7">
			                <?= $form->field($model, 'email')->label('Email') ?>
			            	</div>
			            	<div class="col-lg-12">
			                <?= $form->field($model, 'password')->passwordInput()->label('Password') ?>	 
			            	</div>
 				    </fieldset>
 				    <br>
	              	<fieldset>
        				<legend>Company Details</legend>
        					<div class="col-lg-12">
		        				<?= $form->field($model, 'name')->label('Company Name') ?>	
		        			</div>
		        			<div class="col-lg-6">
			               		<?= $form->field($model, 'contact_name')->label('Contact Name') ?>	
			               	</div>
			               	<div class="col-lg-6">
			               		<?= $form->field($model, 'contact_number')->label('Contact No')?>
			               	</div>
			               
			               	<div class="col-lg-6">
			               		<div clas="form-group">
			               			<label class="control-label"></label>
			               			  <?= Html::a("Send Code",'#' ,['id'=>'phone-validate','class' => 'raised-btn secondery-btn width-100']) ?> 
			               			<div class="help-block"></div>
			               		</div>
			               		
			               		
			               	</div>

			               	<div class="col-lg-6">
			               		<?= $form->field($model, 'validate_code');?>
			               	</div>
		        			<div class="col-lg-12">
			               		<?= $form->field($model, 'address')->label('Address') ?>	
			               	</div>
			            	<div class="col-lg-5">
			               		<?= $form->field($model, 'postcode')->label('Postcode') ?>	
			               	</div>
			            	<div class="col-lg-7">
			               		<?= $form->field($model, 'area')->dropDownList($area,['prompt'=>'Select Company area'])->label('Company Area')?>
			               </div>
   					</fieldset>
	               
	                <div class="form-group">

	                    <?= Html::submitButton(Yii::t('common','Signup'), ['class' => 'raised-btn main-btn', 'name' => 'signup-button']) ?> <br><br>
	                </div>
	            <?php ActiveForm::end(); ?>
	        </div>
	    </div>
  	</div>
</div>