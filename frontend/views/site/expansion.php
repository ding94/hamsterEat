<?php
use common\models\Expansion;
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;

$this->title = "Expansion";
?>
<div class = "text">
    <h2><center>Let us know your location and we might expand our services!</h2>
</div>
<?php if (!Yii::$app->user->isGuest)
{ ?>
    <div class = "container">
<?php }
else
{ ?>
    <div class = "container" >
<?php } ?>
	 <div class="col-lg-6 col-lg-offset-3">
    <?php $form = ActiveForm::begin(['id' => 'form-expansion']); ?>

    <?php if (Yii::$app->user->isGuest)
    { ?>
    <div >
        <?= $form->field($expansion, 'User_Username')->textInput(['autofocus' => true,'placeholder' => "Enter email..."])->label('Email Address'); ?>
        </div>
        
        <?= $form->field($expansion, 'Expansion_Postcode')->textInput(['placeholder' => "Enter postcode..."]) ?>
       
    <?php } else { ?>
         
    <?= $form->field($expansion, 'Expansion_Postcode')->textInput(['autofocus' => true,'placeholder' => "Enter postcode..."]) ?>

    <?php } ?>
    <?= $form->field($expansion, 'Expansion_Area')->textInput(['placeholder' => "Enter area..."]) ?>

    <div id="expansion_button" class="form-group">
        <?= Html::submitButton('Submit Request', ['class' => 'btn btn-primary', 'name' => 'expansion-button']) ?>                
    </div>
    <?php ActiveForm::end(); ?>
</div>
</div>