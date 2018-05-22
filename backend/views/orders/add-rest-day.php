<?php

/* @var $this yii\web\View */
use yii\helpers\Html;
use yii\helpers\Url;
use yii\bootstrap\ActiveForm;
use kartik\widgets\Select2;
use yii\web\JsExpression;
use kartik\widgets\DateTimePicker;
use kartik\widgets\DatePicker;

    $this->title = 'Add Rest Day';
    $this->params['breadcrumbs'][] = $this->title;
?>

<div class="row">
    <div class="col-lg-5">
        <?php $form = ActiveForm::begin(); ?>

            <?= $form->field($model, 'rest_day_name')->textInput() ?>
            
            <?= $form->field($model, 'start_time')->widget(DatePicker::classname(), [
                'options' => ['placeholder' => 'Date voucher active to use'],
                'pluginOptions' => [
                'format' => 'yyyy-mm-dd',
                'todayHighlight' => true,
                'todayBtn' => true,]]) 
            ?>

            <?= $form->field($model, 'end_time' )->widget(DatePicker::classname(), [
                'options' => ['placeholder' => 'Date voucher deactivated (default 30 days after start date)'],
                'pluginOptions' => [
                'format' => 'yyyy-mm-dd',
                'todayHighlight' => true,
                'todayBtn' => true,]]) 
            ?>

            <div class="form-group">
                <?= Html::submitButton('Add', ['class' => 'btn btn-success']) ?>
                <?= Html::a('Back',['/orders/rest-days'], ['class'=>'btn btn-primary']) ?>
            </div>

        <?php ActiveForm::end(); ?>
    </div>
</div>