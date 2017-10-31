<?php

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model \frontend\models\ContactForm */

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use yii\captcha\Captcha;

$this->title = 'Submit Ticket';
?>
<div class="container">
<div class="site-contact">
	  <div class="col-lg-6 col-lg-offset-1" style="text-align:center">
    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        If you have business inquiries or other questions, please fill out the following form to contact us. Thank you.
    </p>
 </div>
    <div class="container">
     <div class="col-lg-6 col-lg-offset-1">
            <?php $form = ActiveForm::begin(); ?>
                <?= $form->field($model, 'Ticket_Subject')->textInput(['autofocus' => true]) ?>
                <?= $form->field($model, 'Ticket_Category')->dropDownList($data) ?>

                <?= $form->field($model, 'Ticket_Content')->textarea(['rows' => 6]) ?>

                 <?= $form->field($upload, 'imageFile')->fileInput() ?>

                <div class="form-group" style="padding-left: 30%">
                    <?= Html::submitButton('Submit', ['class' => 'btn btn-primary', 'name' => 'contact-button']) ?>
                   <?= Html::a('Back', ['/ticket/index'], ['class'=>'btn btn-primary']) ?>
                </div>

            <?php ActiveForm::end(); ?>
        </div>
    </div>

</div>
</div>