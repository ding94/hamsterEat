<?php

/* @var $this yii\web\View */
use yii\helpers\Html;
use yii\helpers\Url;
use yii\bootstrap\ActiveForm;
use kartik\widgets\Select2;
use yii\web\JsExpression;
use kartik\widgets\DateTimePicker;

    $this->title = 'Add Rest Day';
    $this->params['breadcrumbs'][] = $this->title;
?>

<div class="row">
    <div class="col-lg-5">
        <?php $form = ActiveForm::begin(); ?>

            <?= $form->field($model, 'rest_day_name')->textInput() ?>

            <?= $form->field($model, 'month')->textInput() ?>

            <?= $form->field($model, 'date')->textInput() ?>

            <div class="form-group">
                <?= Html::submitButton('Add', ['class' => 'btn btn-success']) ?>
                <?= Html::a('Back',['/orders/rest-days'], ['class'=>'btn btn-primary']) ?>
            </div>

        <?php ActiveForm::end(); ?>
    </div>
</div>