<?php

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model \frontend\models\ContactForm */

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use yii\captcha\Captcha;

$this->title = 'Submit Ticket';
?>
<div class="container" id="userprofile">
	 <div class="userprofile-header">
        <div class="userprofile-header-title"><?php echo Html::encode($this->title)?></div>
    </div>
   <div class="userprofile-detail">
        <div class="col-sm-2">
           <ul class="nav nav-pills nav-stacked">
                <li role="presentation"><?php echo Html::a("All",['/ticket/index'],['class'=>'btn-block userprofile-edit-left-nav'])?></li>
                <li role="presentation" class="active"><a href="#" class="btn-block userprofile-edit-left-nav">Submit Ticket</a></li>
				<li role="presentation"><?php echo Html::a("Completed Ticket",['/ticket/completed'],['class'=>'btn-block userprofile-edit-left-nav'])?></li>
            </ul>
        </div>
 </div>
    <div class="container">
     <div class="col-sm-8 userprofile-edit-input">
	  <p style="text-align:center;">
        If you have business inquiries or other questions, please fill out the following form to contact us. Thank you.
    <br></p>
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
