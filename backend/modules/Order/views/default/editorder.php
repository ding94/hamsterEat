<?php

/* @var $this yii\web\View */
use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\GridView;
use yii\grid\ActionColumn;
use yii\db\ActiveRecord;
use kartik\widgets\ActiveForm;


  $this->title = 'Order Detail';
  $this->params['breadcrumbs'][] = $this->title;
  
?>


	<?php $form = ActiveForm::begin();?>

	<?= $form->field($order, 'Delivery_ID')->textInput(['readonly'=>true]) ?>
	<?= $form->field($order, 'Orders_TotalPrice')->textInput(['readonly'=>true]) ?>
	<?= $form->field($delivery, 'location')->textInput() ?>
	<?= $form->field($delivery, 'postcode')->textInput() ?>
	<?= $form->field($delivery, 'area')->textInput() ?>

	<?= Html::submitButton('Edit',  [
        'class' => 'btn btn-warning', 
        'data' => [
                'method' => 'post',
        ]]);?>
    <?= Html::a('Back', ['/order/default/index'], ['class'=>'btn btn-primary']) ?>


   <?php ActiveForm::end();?> 