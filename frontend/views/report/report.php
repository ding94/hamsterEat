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
		    'options' => ['placeholder' => Yii::t('common','Select a category ...')],
		    'pluginOptions' => [
		        'allowClear' => true
		    ],
		])->label(Yii::t('common','Category')); ?>
		<?= $form->field($report, 'Report_Reason')->textArea(['rows'=>5,'cols'=>5])->label(Yii::t('common','Reason')); ?>
		<?= Html::submitButton(Yii::t('common','Report'), ['class' => 'raised-btn main-btn pull-right']) ?>
		<?php ActiveForm::end(); ?>
	</div>
</div>