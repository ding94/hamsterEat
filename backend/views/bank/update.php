<?php

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model \common\models\LoginForm */

use yii\helpers\Html;
use kartik\widgets\ActiveForm;

	//var_dump($model);exit;
	$this->title = 'Bank';
	$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Bank'), 'url' => ['index']];
	$this->params['breadcrumbs'][] = $this->title;
?>


	<?php $form = ActiveForm::begin();?>
	
		
    <?= $form->field($model, 'Bank_Name')->textInput(['readonly' => true]) ?>
	<?= $form->field($model, 'Bank_AccNo')->textInput() ?>
	<?= $form->field($model, 'redirectUrl')->textInput() ?>
	
	    	
    	<div class="form-group">
	        <?= Html::submitButton('Update', ['class' => 'btn btn-primary']) ?>
	        <?= Html::a('Back',['index'],['class'=>'btn btn-primary']) ?>
	   </div>
	<?php ActiveForm::end();?>