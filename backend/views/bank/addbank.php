<?php
use yii\bootstrap\ActiveForm;
use yii\helpers\Html;
?>

<?php $form = ActiveForm::begin(); ?>

<?= $form->field($upload, 'imageFile')->fileInput() ?>

<?= $form->field($model, 'Bank_Name')->textInput() ?>
<?= $form->field($model, 'Bank_AccNo')->textInput() ?>
<?= $form->field($model, 'redirectUrl')->textInput() ?>


    <div class="form-group">
        <?= Html::submitButton('Upload', ['class' => 'btn btn-primary']) ?>
    </div>

<?php ActiveForm::end(); ?>