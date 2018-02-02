<?php
use yii\helpers\Url;
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;

?>
<div>
	<?php $form = ActiveForm::begin(); ?>
	<?= $form->field($reason, 'reason')->dropDownList($list)->label(Yii::t('common','Reason')) ?>
	<?= $form->field($reason, 'description')->textArea()->label(Yii::t('common','Description')) ?>
	<?= Html::submitButton(Yii::t('common','Submit'), ['class' => 'raised-btn main-btn', 'name' => 'contact-button']) ?>
	<?php ActiveForm::end(); ?>
</div>