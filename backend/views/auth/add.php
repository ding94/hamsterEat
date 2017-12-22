<?php

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model \common\models\LoginForm */

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;

	$this->title = "Add Role";
	$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Role'), 'url' => ['index']];
	$this->params['breadcrumbs'][] = $this->title;
?>
	<?php $form = ActiveForm::begin();?>
    	<?= $form->field($model, 'name')->textInput() ?>
        <?= $form->field($model, 'type')->dropDownList($listOfType,['prompt'=>'-- Select Type --']) ?>
        <?= $form->field($model, 'description')->textInput() ?>
        <?= $form->field($model, 'rule_name')->textInput() ?>
    	<?= $form->field($model, 'data')->dropDownList($listOfControl) ?>
    	<div class="form-group">
	        <?= Html::submitButton(Yii::t('app', 'Add') ,['class' =>  'btn btn-success']) ?>
	   </div>
	<?php ActiveForm::end();?>