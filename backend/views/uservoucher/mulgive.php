<?php

/* @var $this yii\web\View */
use yii\helpers\Html;
use yii\grid\GridView;
use kartik\widgets\ActiveForm;
use kartik\widgets\DatePicker;
use common\models\User;

	$form = ActiveForm::begin(['action' =>['uservoucher/multigive'],'method' => 'post']);

	$name ="";
    foreach ($users as $k => $username) 
    {
    	echo '<input type="hidden" name="result[]" value="'. $users[$k]. '">';
    	$users[$k] = User::find()->where('id = :id',[':id' => $username])->one()->username;

    	if ($k >= 4) {$name =$name."...";}
    	elseif ($k >=3) {$name = $name.$users[$k];}
    	else{$name = $name.$users[$k].',';}
    }

	
    $this->title = 'Multiple Give to : '.$name;
    $this->params['breadcrumbs'][] = $this->title;($name);
?>
		<?= $form->field($voucher, 'digit')->textInput() ?>
		<?= $form->field($voucher, 'discount')->textInput() ?>
        <?= $form->field($voucher ,'discount_type')->dropDownList($type)?>
        <?= $form->field($voucher ,'discount_item')->dropDownList($item)?>
        <?= $form->field($uservoucher, 'endDate')->widget(DatePicker::classname(), [
            'options' => ['placeholder' => 'Date voucher deactived'],
            'pluginOptions' => [
                'format' => 'yyyy-mm-dd',
                'todayHighlight' => true,
                'startDate' => date('Y-m-d h:i:s'), 
                'todayBtn' => true,
            ]]) 
        ?>

		<?= Html::submitButton('Apply',  [
		        'class' => 'btn btn-success', 
		        'data' => [
		                'confirm' => 'Are you sure want to apply coupons to these users?',
		                'method' => 'post',
		            ]]);?>
		<?= Html::a('Back', ['/uservoucher/index'], ['class'=>'btn btn-primary']) ?>

		<?php ActiveForm::end();?>
