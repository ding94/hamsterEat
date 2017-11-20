<?php
use common\models\Expansion;
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;

$this->title = "Expansion";
?>
<style>
    /* expansion css */
.container
{
    border: 1.5px solid black;
    border-radius: 5px;
    height: 400px;
    padding-top: 80px;
    padding-left:5%;
    margin-top: 20px;
    width: 500px;
}

.container-1
{
    border: 1.5px solid black;
    border-radius: 5px;
    height: 450px;
    padding-top: 40px;
    padding-left: 5%;
    margin-top: 20px;
    width: 500px;
    margin-left: 500px;
}

.text
{
    margin-bottom:3%;
}
#expansion_button .btn.btn-primary
{
margin-left: 24%;
 margin-top: 2%;
}
#expansion_email .form-control{
     width:350px;
}
#expansion_postcode .form-control{
    width:350px;
}
#expansion_postcode_1 .form-control{
    width:350px;
}
#expansion_area .form-control{
    width:350px;
}
/* Expansion mobile css */
@media(max-width: 480px){
    .container{
        margin:auto;
        width:27em;
    }
    .container-1{
        margin:auto;
        width:27em;
    }
.text{
    margin:auto;
}
#expansion_email .form-control{
    width:340px;
}
#expansion_postcode .form-control{
    width:340px;
}
#expansion_postcode_1 .form-control{
    width:340px;
}
#expansion_area .form-control{
    width:340px;
}
#expansion_button .btn.btn-primary
{
margin-left: 29%;
 margin-top: 5%;
}
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
    <div id="expansion_email">
        <?= $form->field($expansion, 'User_Username')->textInput(['autofocus' => true,'placeholder' => "Enter email..."])->label('Email Address'); ?>
        </div>
        <div id="expansion_postcode">
        <?= $form->field($expansion, 'Expansion_Postcode')->textInput(['placeholder' => "Enter postcode..."]) ?>
        </div>
    <?php } else { ?>
          <div id="expansion_postcode_1">
    <?= $form->field($expansion, 'Expansion_Postcode')->textInput(['autofocus' => true,'placeholder' => "Enter postcode..."]) ?>
</div>
    <?php } ?>
<div id="expansion_area">
    <?= $form->field($expansion, 'Expansion_Area')->textInput(['placeholder' => "Enter area..."]) ?>
</div>
    <div id="expansion_button" class="form-group">
        <?= Html::submitButton('Submit Request', ['class' => 'btn btn-primary', 'name' => 'expansion-button']) ?>                
    </div>
    <?php ActiveForm::end(); ?>
</div>