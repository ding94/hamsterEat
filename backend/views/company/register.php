<?php
use yii\helpers\Html;
use kartik\widgets\ActiveForm;

	$this->title = 'Register new Comapny';
	$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Company List'), 'url' => ['/company/index']];
	$this->params['breadcrumbs'][] = $this->title;
?>


	<?php $form = ActiveForm::begin();?>
    <?= $form->field($company ,'name')->textInput()->label('Company Name')?>
    <?= $form->field($company ,'username')->textInput()->label('Owner username')?>
    <?= $form->field($company ,'license_no')->textInput()?>
    <?= $form->field($company ,'address')->textInput()->label('Company address')?>
    <?= $form->field($company ,'postcode')->textInput()?>
    <?= $form->field($company ,'area')->textInput()?>

    	<div class="form-group">
	        <?= Html::submitButton('Register', ['class' => 'btn btn-success']) ?>
            <?= Html::a('Back', ['/company/index'], ['class'=>'btn btn-primary']) ?>
	   </div>
	<?php ActiveForm::end();?>
