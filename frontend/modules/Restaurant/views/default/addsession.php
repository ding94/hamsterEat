<?php
use yii\helpers\Html;
use yii\bootstrap\Modal;
use yii\helpers\Url;
use kartik\widgets\ActiveForm;
use kartik\widgets\Select2;
?>
<?php $form = ActiveForm::begin(); ?>
    <?=   Select2::widget([
		    
		    'name' => 'area',
		    'data' => $postcodeArray,
		    'options' => ['placeholder' => 'Select an Area ...'],
		    'pluginOptions' => [
		        'allowClear' => true,
		    ],
		]); ?>
		<br>
    <?= Html::submitButton('Continue', ['class' => 'btn btn-primary', 'name' => 'insert-button']) ?>
<?php ActiveForm::end(); ?>
