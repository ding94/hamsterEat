<?php
use yii\bootstrap\ActiveForm;
use yii\helpers\Html;
use kartik\widgets\DateTimePicker;
use dosamigos\ckeditor\CKEditor;
use yii\helpers\Url;
?>

<?php $form = ActiveForm::begin(); ?>

<?= $form->field($en_text, 'name')->textInput()->label('English Name') ?>

<?= $form->field($en_text, 'text')->widget(CKEditor::className(), [
        'options' => ['rows' => 6],
        'preset' => 'full',
        'clientOptions' => [
                    'filebrowserUploadUrl' => Url::to(['news/upload'])
                ]
    ])->label('English Text') ?>

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
        <?= Html::a('Back',['index'], ['class' => 'btn btn-primary']) ?>
    </div>

<?php ActiveForm::end(); ?>