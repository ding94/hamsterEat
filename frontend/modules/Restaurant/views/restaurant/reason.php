<?php
use yii\helpers\Url;
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;

?>
<div>
	<?php $form = ActiveForm::begin(); ?>
	<?= $form->field($reason, 'reason')->dropDownList($list) ?>
	<?= $form->field($reason, 'description')->textArea() ?>
	<?= Html::submitButton('Submit', ['class' => 'btn btn-primary', 'name' => 'contact-button']) ?>

	<?php ActiveForm::end(); ?>
</div>