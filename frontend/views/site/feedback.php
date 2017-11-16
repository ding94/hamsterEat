<?php
use common\models\Feedback;
use common\models\Feedbackcategory;
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
?>
<style>
.container-feedback
{
    border: 1.5px solid black;
    border-radius: 5px;
    height: 550px;
    padding-top: 20px;
    width: 700px;
    margin-left:33px;
    padding-left:14px;
}

.container-feedback-1
{
    border: 1.5px solid black;
    border-radius: 5px;
    height: 450px;
    padding-top: 20px;
    width: 700px;
    margin-left:33px;
    padding-left:14px;
}
 #feedback_category .form-control
  {
    width:350px
 }
 #feedback_email .form-control
  {
    width:350px
 }
  #feedback_message .form-control
  {
    width:670px;
    height:180px;
 }
   #feedback_button .btn.btn-primary
  {
    margin-left: 40%; 
    margin-top: 4%;
 }
textarea
{
    resize: none;
}
  @media(max-width: 480px)
    {
        #feedback_category .form-control
        {
            width:270px;
        }
        #feedback_message .form-control
        {
            width:270px;
        }
        #feedback_email .form-control
        {
            width:270px;
        }
        
        #feedback_button .btn.btn-primary
        {
        margin-left: 23%; 
        margin-top: 4%;
        }
    }
</style>
<?php if (Yii::$app->user->isGuest)
{ ?>
    <div class = "container-feedback">
<?php }else{ ?>
    <div class = "container-feedback-1">
<?php } ?>

    <?php $form = ActiveForm::begin(['id' => 'form-feedback']); ?>
    <div id="feedback_category"> 
    <?= $form->field($feedback, 'Feedback_Category')->dropDownList($categoryarray)->label('Category'); ?>
</div>
<div id="feedback_email"> 
    <?php if (Yii::$app->user->isGuest)
    {
        echo $form->field($feedback, 'User_Username')->textInput(['placeholder' => "Enter your email address here..."])->label('Email');
    } ?>
    </div>
     <div id="feedback_message">               
    <?= $form->field($feedback, 'Feedback_Message')->textArea(['placeholder' => "Enter your feedback here..."])->label('Message'); ?>
</div>
    <?= $form->field($feedback, 'Feedback_PicPath')->fileInput()->label('Provide a Screenshot') ?>
    
    <div id="feedback_button">
        <?= Html::submitButton('Submit Feedback', ['class' => 'btn btn-primary', 'name' => 'feedback-button']); ?>
        <?php ActiveForm::end(); ?>
    </div>
</div>