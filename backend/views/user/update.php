<?php

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;

	$this->title = "Edit User";
	$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'User'), 'url' => ['index']];
	$this->params['breadcrumbs'][] = $this->title;
?>

	<?php $form = ActiveForm::begin();?>
    	<?= $form->field($model, 'username')->textInput() ?>
    	<?= $form->field($model, 'email')->textInput() ?>
        <?= $form->field($model,'role')->dropDownlist($list) ?>
    	
    	<div class="form-group">
	        <?= Html::submitButton($model->isNewRecord ? Yii::t('app', 'Add') : Yii::t('app', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
	   </div>
	<?php ActiveForm::end();?>