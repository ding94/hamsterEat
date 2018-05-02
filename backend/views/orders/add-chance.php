<?php

/* @var $this yii\web\View */
use yii\helpers\Html;
use yii\helpers\Url;
use yii\bootstrap\ActiveForm;
use kartik\widgets\Select2;
use yii\web\JsExpression;
use kartik\widgets\DateTimePicker;

    $this->title = 'Add Order Chance';
    $this->params['breadcrumbs'][] = $this->title;
?>

<div class="row">
    <div class="col-lg-5">
        <?php $form = ActiveForm::begin(); ?>

            <?= $form->field($model, 'uid')->widget(Select2::classname(), [
		    	'options' => ['placeholder' => 'Search for an user ...'],
		    	'pluginOptions' => [
		        	'allowClear' => true,
		        	'language' => [
		            	'errorLoading' => new JsExpression("function () { return 'Waiting for results...'; }"),
		        	],
		        	'ajax' => [
		            	'url' => $url,
		            	'dataType' => 'json',
		            	'data' => new JsExpression('function(params) { return {q:params.term}; }')
		        	],
		        	'escapeMarkup' => new JsExpression('function (markup) { return markup; }'),
		        	'templateResult' => new JsExpression('function(user) { return user.username; }'),
		        	'templateSelection' => new JsExpression('function (user) { return user.username; }'),
		    	],
			])->label('Username');  ?>

            <?= $form->field($model, 'cancel_did')->textInput() ?>
            <?= $form->field($model, 'chances')->textInput() ?>

            <?= $form->field($model, 'start_time')->widget(DateTimePicker::classname(), [
			    'options' => ['placeholder' => 'Enter start date and time ...'],
			    'pluginOptions' => [
			    	'format' => 'yyyy-mm-dd hh'. ":00",
			    	'minView' => 1,
			        'autoclose'=>true,
			        'startDate' => date('Y-m-d'),
			    ]
			]) ?>

			<?= $form->field($model, 'end_time')->widget(DateTimePicker::classname(), [
			    'options' => ['placeholder' => 'Enter end date and time ...'],
			    'pluginOptions' => [
			    	'format' => 'yyyy-mm-dd hh'. ":00",
			    	'minView' => 1,
			        'autoclose'=>true,
			        'startDate' => date('Y-m-d'), 
			    ]
			]) ?>

            <div class="form-group">
                <?= Html::submitButton('Add', ['class' => 'btn btn-success']) ?>
                <?= Html::a('Back',['/orders/place-order-chance'], ['class'=>'btn btn-primary']) ?>
            </div>

        <?php ActiveForm::end(); ?>
    </div>
</div>