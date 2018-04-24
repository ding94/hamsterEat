<?php
use yii\bootstrap\ActiveForm;
use yii\helpers\Html;
use kartik\widgets\DateTimePicker;
use dosamigos\ckeditor\CKEditor;
use yii\helpers\Url;
?>

<?php $form = ActiveForm::begin(); ?>

<?= $form->field($model, 'name')->textInput()->label('Name') ?>

<?= $form->field($model, 'text')->widget(CKEditor::className(), [
        'options' => ['rows' => 6],
        'preset' => 'full',
        'clientOptions' => [
                    'filebrowserUploadUrl' => Url::to(['news/upload'])
                ]
    ])->label('Text') ?>

    <div class="form-group">
        <?= Html::submitButton('Upload', ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Back',['index'], ['class' => 'btn btn-primary']) ?>
    </div>

<?php ActiveForm::end(); ?>