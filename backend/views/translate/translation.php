<?php
use yii\helpers\Html;
use kartik\widgets\ActiveForm;

	$this->title = '';
	//$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Voucher List'), 'url' => ['index']];
	//$this->params['breadcrumbs'][] = $this->title;
?>

	<?php $form = ActiveForm::begin();?>
    <?= $form->field($sen ,'translation')->textArea()?>
    	<div class="form-group">
	        <?= Html::submitButton('Add', ['class' => 'btn btn-success']) ?>
	   </div>
	<?php ActiveForm::end();?>
