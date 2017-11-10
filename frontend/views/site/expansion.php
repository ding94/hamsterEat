<?php
use common\models\Expansion;
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;

$this->title = "Expansion";
?>
<style>
.container
{
    border: 1.5px solid black;
    border-radius: 5px;
    height: 400px;
    padding-top: 80px;
    padding-left: 17%;
    margin-top: 20px;
    width: 1000px;
}

.container-1
{
    border: 1.5px solid black;
    border-radius: 5px;
    height: 450px;
    padding-top: 40px;
    padding-left: 17%;
    margin-top: 20px;
    width: 1000px;
    margin-left: 450px;
}

.text
{
    margin-bottom:3%;
}

</style>
<div class = "text">
    <h2><center>Let us know your location and we might expand our services!</h2>
</div>
<?php if (!Yii::$app->user->isGuest)
{ ?>
    <div class = "container">
<?php }
else
{ ?>
    <div class = "container-1">
<?php } ?>

    <?php $form = ActiveForm::begin(['id' => 'form-expansion']); ?>

    <?php if (Yii::$app->user->isGuest)
    { ?>
        <?= $form->field($expansion, 'User_Username')->textInput(['autofocus' => true, 'style'=>'width:350px', 'placeholder' => "Enter email..."])->label('Email Address'); ?>
        <?= $form->field($expansion, 'Expansion_Postcode')->textInput(['style'=>'width:350px', 'placeholder' => "Enter postcode..."]) ?>
    <?php } else { ?>
         
    <?= $form->field($expansion, 'Expansion_Postcode')->textInput(['autofocus' => true, 'style'=>'width:350px', 'placeholder' => "Enter postcode..."]) ?>

    <?php } ?>

    <?= $form->field($expansion, 'Expansion_Area')->textInput(['style'=>'width:350px', 'placeholder' => "Enter area..."]) ?>

    <div class="form-group">
        <?= Html::submitButton('Submit Request', ['class' => 'btn btn-primary', 'name' => 'expansion-button', 'style'=>'margin-left: 15%; margin-top: 2%;']) ?>                
    </div>
    <?php ActiveForm::end(); ?>
</div>