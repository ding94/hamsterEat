<?php

/* @var $this yii\web\View */
use yii\bootstrap\ActiveForm;
use yii\helpers\Html;

$this->title = 'Restaurant Location';
?>
<div class="site-index">

    <div class="container">
		<div class="tab-content col-md-6 col-md-offset-3" >
		<table class="table table-user-information"> <h1>Enter Your Restaurant's Location</h1>
        <?php if($postcode['detectArea'] == 0) :?>
		
        <tr> <?php $form = ActiveForm::begin(['id' => 'area']); ?></tr>
        <?php else :?>
       <?php $form = ActiveForm::begin(['action' =>['default/new-restaurant-details'],'id' => 'area']); ?>
        <?php endif ;?>
        <tr> <?= $form->field($postcode, 'Area_Postcode')->textInput(['autofocus' => true])->label('Postcode') ?></tr>
        <?php if( $postcode['detectArea'] == 1) :?>
        <?= $form->field($postcode, 'Area_Area')->dropDownList($list) ?>
        <?php endif ;?>
        <tr> <?= Html::submitButton('Proceed', ['class' => 'btn btn-primary', 'name' => 'proceed-button']) ?> </tr>

        <?php ActiveForm::end(); ?>
 </table>
            </div>
            </div>
    </div>
</div>
