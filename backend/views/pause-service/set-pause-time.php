<?php

/* @var $this yii\web\View */
use dosamigos\chartjs\ChartJs;
use kartik\date\DatePicker;
use kartik\widgets\Select2;
use kartik\widgets\ActiveForm;
use yii\helpers\Html;

$this->title = 'Set Pause Condition';
?>
<div class="site-index">
    <div class="container">
        <div class="col-sm-8">
            <?php $form = ActiveForm::begin();?>
                <?= Html::label('When'); ?>
                <?= Html::dropDownList('date', $date_format, $date_format, ['class' => 'form-control']); ?>

                <?= Html::label('is'); ?>
                <?= Html::dropDownList('symbol', $symbol, $symbol, ['class' => 'form-control']); ?>

                <?= Html::label(''); ?>
                <?= $form->field($model,'time')->textInput()->label(false); ?>

                <div class="form-group">
                    <?= Html::submitButton('Create', ['class' => 'btn btn-success']) ?>
                    <?= Html::a('Back',['/pause-service/pause-time'], ['class' => 'btn btn-primary']) ?>
               </div>

            <?php ActiveForm::end();?>
        </div>
    </div>
</div>