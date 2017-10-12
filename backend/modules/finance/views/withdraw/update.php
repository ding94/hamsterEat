<?php

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model \common\models\LoginForm */

use yii\helpers\Html;
use kartik\widgets\ActiveForm;
use kartik\widgets\DatePicker;

	//var_dump($model);exit;
	$this->title = 'User Withdraw';
	$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'User Withdraw'), 'url' => ['index']];
	$this->params['breadcrumbs'][] = $this->title;
?>


	<?php $form = ActiveForm::begin();?>
	
		
  
	<?= $form->field($model, 'reason')->textInput() ?>
	
	    	
    	<div class="form-group">
	        <?= Html::submitButton('Update', ['class' => 'btn btn-primary']) ?>
			 <?= Html::a('Back', ['/finance/withdraw/index'], ['class'=>'btn btn-primary']) ?>
	   </div>
	<?php ActiveForm::end();?>