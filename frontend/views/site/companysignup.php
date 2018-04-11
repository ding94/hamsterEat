<?php

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model \frontend\models\CompanySignUpForm */

use yii\helpers\Html;
use yii\widgets\ActiveForm;

$this->title ='Company Signup';
?>
<div class="container">
  <div class="col-lg-6 col-lg-offset-3" style="text-align:center">
    <h1><?= Html::encode($this->title) ?></h1>

    <p><?php echo 'Please fill out the following fields to signup' ?></p>
  </div>
    <div class="container" >
        <div class="container">
  		  	<div class="col-lg-6 col-lg-offset-3">
		        <?php $form = ActiveForm::begin(['id' => 'form-companysignup']); ?>
	             	
   				    <fieldset>
       				 	<legend>User Details</legend>
        					<?= $form->field($model, 'username')->textInput(['autofocus' => true])->label(Yii::t('common','Username')) ?>
			                <?= $form->field($model, 'email')->label('Email') ?>

			                <?= $form->field($model, 'password')->passwordInput()->label('Password') ?>	 
 				    </fieldset>
 				    <br>
	              	<fieldset>
        				<legend>Company Details</legend>
		        			<?= $form->field($model, 'name')->label('Company Name') ?>	

			               	<?= $form->field($model, 'address')->label('Address') ?>	

			               	<?= $form->field($model, 'postcode')->label('Postcode') ?>	

			               	<?= $form->field($model, 'area')->dropDownList($area,['prompt'=>'Select Company area'])->label('Company Area')?>
   					</fieldset>
	               
	                <div class="form-group">
	                    <?= Html::submitButton(Yii::t('common','Signup'), ['class' => 'raised-btn main-btn', 'name' => 'signup-button']) ?> <br><br>
	                </div>
	            <?php ActiveForm::end(); ?>
	        </div>
	    </div>
  	</div>
</div>