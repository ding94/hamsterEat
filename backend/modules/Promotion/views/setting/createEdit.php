<?php

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */

use yii\helpers\Html;
use kartik\widgets\ActiveForm;
use kartik\daterange\DateRangePicker;

$this->title = $model->isNewRecord ? "Generate New Promotion" : "Edit Promotion ".$model->id;
$this->params['breadcrumbs'][] = ['label' => 'Promotion Index', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;

$form = ActiveForm::begin();
	echo $form->field($model, 'type_promotion')->dropDownList($array['type'],['prompt'=>'-- Select One --']);
	
	echo $form->field($model, 'type_discount')->dropDownList($array['discount'],['prompt'=>'-- Select One --']);
	
	echo $form->field($model,'discount');
	echo $form->field($model, 'enable_selection')->dropDownList($array['selection'],['prompt'=>'-- Select One --']);
?>
	<label>Date Range</label>
	<div class="input-group drp-container">
	<span class="input-group-addon">
	    <i class="glyphicon glyphicon-calendar"></i>
	</span>
	<?php 
		if($model->isNewRecord)
		{
			$model->start_date = date("Y-m-d",strtotime("next week"));
			$model->end_date = date("Y-m-d",strtotime($model->start_date)+60*60*144);
		}
		
		echo DateRangePicker::widget([
	    'model'=>$model,
	    'attribute' => 'date',
	    'useWithAddon'=>true,
	    'convertFormat'=>true,
	    'startAttribute' => 'start_date',
	    'endAttribute' => 'end_date',
	    'pluginOptions'=>[
	        'locale'=>['format' => 'Y-m-d'], 
	    ],

	]);?>
	</div>
	<br>
	<?php echo  Html::submitButton($model->isNewRecord ? Yii::t('app', 'Add') : Yii::t('app', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']);
ActiveForm::end();
?>