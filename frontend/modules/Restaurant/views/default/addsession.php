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
		    'options' => ['placeholder' => Yii::t('m-restaurant','Select an Area ...')],
		    'pluginOptions' => [
		        'allowClear' => true,
		    ],
		]); ?>
		<br>
    <?= Html::submitButton(Yii::t('common','Continue'), ['class' => 'raised-btn main-btn', 'name' => 'insert-button']) ?>
<?php ActiveForm::end(); ?>
