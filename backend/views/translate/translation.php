<?php
use yii\helpers\Html;
use kartik\widgets\ActiveForm;

	$this->title = '';
	//$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Voucher List'), 'url' => ['index']];
	//$this->params['breadcrumbs'][] = $this->title;
?>
	<h2><?= $sen['id0']['message'] ?></h2>
	<?php $form = ActiveForm::begin();?>
    <?= $form->field($sen ,'translation')->textArea()?>
    	<div class="form-group">
	        <?= Html::submitButton('Add', ['class' => 'btn btn-success']) ?>
	   </div>
	<?php ActiveForm::end();?>
