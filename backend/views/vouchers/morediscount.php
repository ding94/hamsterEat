<?php
use yii\helpers\Html;
use kartik\widgets\ActiveForm;

	$this->title = 'More discount item for Voucher: '.$voucher['code'];
	$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Voucher List'), 'url' => ['index']];
	$this->params['breadcrumbs'][] = $this->title;
?>


	<?php $form = ActiveForm::begin();?>
    <?= $form->field($model ,'discount')->textInput()?>
    <?= $form->field($model ,'discount_type')->dropDownList($type)?>
    <?= $form->field($model ,'discount_item')->dropDownList($item)?>

    	<div class="form-group">
	        <?= Html::submitButton('Add', ['class' => 'btn btn-success']) ?>
            <?= Html::a('Back', ['/vouchers/index'], ['class'=>'btn btn-primary']) ?>
	   </div>
	<?php ActiveForm::end();?>
