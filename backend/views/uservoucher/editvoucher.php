<?php

/* @var $this yii\web\View */
use yii\helpers\Html;
use yii\grid\GridView;
use kartik\widgets\ActiveForm;
use kartik\widgets\DatePicker;

	$this->title = $voucher['code'];
    
?>

	<?php $form = ActiveForm::begin();?>

	<?= $form->field($voucher, 'code')->textInput(['readonly'=>true]) ?>
	<?= $form->field($voucher, 'discount')->textInput() ?>
	<?= $form->field($voucher, 'discount_type')->dropDownList($type) ?>
	<?= $form->field($voucher, 'discount_item')->dropDownList($item) ?>
	<?= $form->field($voucher, 'endDate')->widget(DatePicker::classname(), [
            'options' => ['placeholder' => 'Date voucher deactived'],
            'pluginOptions' => [
                'format' => 'yyyy-mm-dd',
                'todayHighlight' => true,
                'startDate' => date('Y-m-d h:i:s'), 
                'todayBtn' => true,
            ]]) 
        ?>


	<?= Html::submitButton('Edit',  [
        'class' => 'btn btn-warning', 
        'data' => [
                'method' => 'post',
        ]]);?>
    <?= Html::a('Back', Yii::$app->request->Referrer, ['class'=>'btn btn-primary']) ?>


   <?php ActiveForm::end();?> 