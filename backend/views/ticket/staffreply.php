<?php

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model \frontend\models\ContactForm */

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use yii\captcha\Captcha;

$this->title = 'Reply to '.$model->User_Username;
$this->params['breadcrumbs'][] = $this->title;
?>


<div class="row">
        <div class="col-lg-5">
            <?php $form = ActiveForm::begin(['id' => 'login-form']); ?>

                <?= $form->field($reply, 'Replies_ReplyContent')->textArea(['rows' => 4 , 'autofocus' => true]) ?>
                 <?= $form->field($upload, 'imageFile')->fileInput() ?>
                <div class="form-group">
                    <?= Html::submitButton('Reply', ['class' => 'btn btn-primary', 'name' => 'Reply-button']) ?>
                    
                </div>

            <?php ActiveForm::end(); ?>
        </div>
    </div>

   <br>

   <div>
	   	<div>
	   		<H3>Subject : <?php echo $model->Ticket_Subject; ?> </H3>
	   	</div>
	   	<div>
	   		<p><?php echo $model->User_Username; ?>	 :  <?php echo $model->Ticket_Content; ?></p>
	   	</div>
   </div>