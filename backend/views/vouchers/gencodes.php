<?php

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model \common\models\LoginForm */

use yii\helpers\Html;
use kartik\widgets\ActiveForm;
use kartik\widgets\DatePicker;

	//var_dump($model);exit;
	$this->title = 'New Voucher';
	$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Voucher List'), 'url' => ['index']];
	$this->params['breadcrumbs'][] = $this->title;
?>


	<?php $form = ActiveForm::begin();?>
    <?= $form->field($model, 'amount')->textInput() ?>
    <?= $form->field($model, 'digit')->textInput() ?>
    <?= $form->field($model, 'discount')->textInput() ?>
    <?= $form->field($model ,'discount_type')->dropDownList($type)?>
    <?= $form->field($model ,'discount_item')->dropDownList($item)?>

    	<?= $form->field($model, 'startDate')->widget(DatePicker::classname(), [
    		'options' => ['placeholder' => 'Date voucher active to use'],
    		'pluginOptions' => [
    		'format' => 'yyyy-mm-dd',
	    	'todayHighlight' => true,
	        'todayBtn' => true,]]) 
	    ?>

        <?= $form->field($model, 'endDate' )->widget(DatePicker::classname(), [
            'options' => ['placeholder' => 'Date voucher deactivated (default 30 days after start date)'],
            'pluginOptions' => [
            'format' => 'yyyy-mm-dd',
            'todayHighlight' => true,
            'todayBtn' => true,]]) 
        ?>

    	
    	<div class="form-group">
	        <?= Html::submitButton('Generate', ['class' => 'btn btn-success']) ?>
            <?= Html::a('Back', ['/vouchers/index'], ['class'=>'btn btn-primary']) ?>
	   </div>
	<?php ActiveForm::end();?>
