<?php
use yii\bootstrap\ActiveForm;
use yii\helpers\Html;
use kartik\widgets\DateTimePicker;
?>

<?php $form = ActiveForm::begin(); ?>

<?= $form->field($upload, 'imageFile')->fileInput() ?>

<?= $form->field($model, 'title')->textInput() ?>

<?= $form->field($model, 'redirectUrl')->textInput() ?>

<?= $form->field($model, 'startTime')->widget(DateTimePicker::classname(), [
		    'options' => ['placeholder' => 'Enter start date and time ...'],
		    'pluginOptions' => [
		    	'format' => 'yyyy-mm-dd hh:ii:ss',
		        'autoclose'=>true,
		        'startDate' => date('Y-m-d h:i:s'),
		    ]
		]) ?>
<?= $form->field($model, 'endTime')->widget(DateTimePicker::classname(), [
    'options' => ['placeholder' => 'Enter end date and time ...'],
    'pluginOptions' => [
    	'format' => 'yyyy-mm-dd hh:ii:ss',
        'autoclose'=>true,
        'startDate' => date('Y-m-d h:i:s'), 
    ]
]) ?>
    <div class="form-group">
        <?= Html::submitButton('Upload', ['class' => 'btn btn-primary']) ?>
    </div>

<?php ActiveForm::end(); ?>