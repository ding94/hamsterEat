<?php
use yii\helpers\Html;
use yii\bootstrap\Modal;
use yii\helpers\Url;
use frontend\assets\UserAsset;
use kartik\widgets\ActiveForm;
use kartik\widgets\Select2;
use kartik\widgets\DepDrop;

UserAsset::register($this);
?>
<?php $form = ActiveForm::begin(); ?>
    <?= $form->field($model, 'Area_Postcode')->widget(Select2::classname(), [
	    'data' => $postcodeArray,
	    'options' => ['placeholder' => 'Select a postcode ...','id'=>'postcode-select']])->label('Postcode');
    ?>

    <?= $form->field($model,'Area_Area')->widget(DepDrop::classname(), [
			'type'=>DepDrop::TYPE_SELECT2,
			'options' => ['id'=>'area-select','placeholder' => 'Select an area ...'],
			'pluginOptions'=>[
			'depends'=>['postcode-select'],
			'url'=>Url::to(['/cart/get-area'])
		],
	]); ?>
    <?= Html::submitButton('Continue', ['class' => 'btn btn-primary', 'name' => 'insert-button']) ?>
<?php ActiveForm::end(); ?>
