<?php

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;

	$this->title = $model->adminTittle;
	$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Admin'), 'url' => ['index']];
	$this->params['breadcrumbs'][] = $this->title;
?>

	<?php $form = ActiveForm::begin();?>
    	<?= $form->field($model, 'adminname')->textInput() ?>
    	<?= $form->field($model, 'email')->textInput() ?>
    	<?php if($model->passwordOff == 1):?>
    		<?= $form->field($model, 'password')->passwordInput() ?>
    		<?= $form->field($model ,'role')->dropDownList($list,['prompt' => ' -- Select Role --'])?>
    	<?php else :?>
    		<?= $form->field($model ,'role')->dropDownList($list)?>
    	<?php endif ;?>

    	
    	<div class="form-group">
	        <?= Html::submitButton($model->isNewRecord ? Yii::t('app', 'Add') : Yii::t('app', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
	   </div>
	<?php ActiveForm::end();?>