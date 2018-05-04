<?php

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;

    $this->title = 'Update Order Status';
	$this->params['breadcrumbs'][] = ['label' =>'All Order Status', 'url' => ['orderstatus']];
    $this->params['breadcrumbs'][] = $this->title;
?>


    <h2>Delivery ID : <b><?= $model->Delivery_ID ?></b></h2>


	<?php $form = ActiveForm::begin();?>

    	<?= $form->field($model,'Orders_Status')->dropDownList($list) ?>

        <?php 
            if($model->Orders_Status == 1){
                echo $form->field($model,'Orders_PaymentMethod')->dropDownList(['Online Banking' => 'Online Banking', 'User Balance' => 'User Balance', 'Cash on Delivery'=> 'Cash on Delivery'],['prompt'=>'<--Select Payment Method-->']);
            }
        ?> 
        <br>
    	
    	<div class="form-group">
	        <?= Html::submitButton('Update', ['class' =>'btn btn-primary d']) ?>
             <?= Html::a('Back',['orderstatus'],['class'=>'btn btn-primary']) ?>
	   </div>
	<?php ActiveForm::end();?>
