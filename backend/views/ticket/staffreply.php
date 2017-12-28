<?php

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model \frontend\models\ContactForm */

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use yii\captcha\Captcha;

$this->title = 'Reply to '.$name;
$this->params['breadcrumbs'][] = $this->title;
?>
     <div>
  	   	<div>
  	   		<H3>Subject : <?php echo $model->Ticket_Subject; ?> </H3>
  	   	</div>
        <table class="table table-inverse">
          <tr>
      	   <th> <?php echo $name; ?>	</th> 
           <th><?php echo $model->Ticket_Content; ?> </th> 
           <th><?php if(!empty($model->Ticket_PicPath)){ echo Html::a('Picture',Yii::$app->params['backend-submitticket-pic'].$model->Ticket_PicPath,['target'=>'_blank']); }?></th>
  	   	</tr>
        
          <?php 
          foreach ($chat as $chatt) { 
            echo '<tr><td>'.$chatt->Replies_ReplyPerson.' </td><td>'.$chatt->Replies_ReplyContent.'</td><td>';
            if(!empty($chatt->Replies_PicPath)){ echo Html::a('Picture',Yii::$app->params['replyticket-pic'].$chatt->Replies_PicPath,['target'=>'_blank']).'</td></tr>'; }
         }?>
         </table>
     </div>
  
<br>

   <div class="row">
        <div class="col-lg-5">
            <?php $form = ActiveForm::begin(['id' => 'login-form']); ?>

                <?= $form->field($reply, 'Replies_ReplyContent')->textArea(['rows' => 4 , 'autofocus' => true]) ?>
                 <?= $form->field($upload, 'imageFile')->fileInput() ?>
                <div class="form-group">
                    <?= Html::submitButton('Reply', ['class' => 'btn btn-primary', 'name' => 'Reply-button']) ?>
                    <?= Html::a('Back',['/ticket/index'], ['class'=>'btn btn-primary']) ?>
                </div>

            <?php ActiveForm::end(); ?>
        </div>
    </div>