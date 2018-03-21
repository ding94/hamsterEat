<?php

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;

$this->title = "Edit Setting";
$this->params['breadcrumbs'][] = ['label' => 'Notification Setting', 'url' => ['setting']];
$this->params['breadcrumbs'][] = $this->title;

	$form = ActiveForm::begin();
		echo $form->field($model, 'description')->textInput();
		echo $form->field($model,'enable')->dropDownList(['-1'=>'Force Off','0'=>'Off','1'=>'On','2'=>'Force On']);
		echo Html::submitButton('Update',['class' =>  'btn btn-primary']);
	ActiveForm::end();
?>