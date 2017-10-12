<?php
use yii\helpers\Html;
use kartik\widgets\ActiveForm;
use kartik\widgets\Select2;

?>

<div class="container-fluid">
	<div class="row">
		<?php $form = ActiveForm::begin(); ?>
		<?= $form->field($report, 'Report_Category')->widget(Select2::classname(), [
	    'data' => $categoryArray,
	    'options' => ['placeholder' => 'Select a category ...'],
	    'pluginOptions' => [
	        'allowClear' => true
	    ],
	]); ?>
		<?= $form->field($report, 'Report_Reason')->textArea(['rows'=>5,'cols'=>5]); ?>
		<?= $form->field($report, 'Report_PersonReported')->textInput(); ?>
		<?= Html::submitButton('Report', ['class' => 'btn btn-primary pull-right']) ?>
		<?php ActiveForm::end(); ?>
	</div>
</div>