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

textarea
{
    resize: none;
}

</style>
<?php if (Yii::$app->user->isGuest)
{ ?>
    <div class = "container-feedback">
<?php }else{ ?>
    <div class = "container-feedback-1">
<?php } ?>

    <?php $form = ActiveForm::begin(['id' => 'form-feedback']); ?>

    <?= $form->field($feedback, 'Feedback_Category')->dropDownList($categoryarray, ['style'=>'width:350px'])->label('Category'); ?>

    <?php if (Yii::$app->user->isGuest)
    {
        echo $form->field($feedback, 'User_Username')->textInput(['style'=>'width:350px;', 'placeholder' => "Enter your email address here..."])->label('Email');
    } ?>
                    
    <?= $form->field($feedback, 'Feedback_Message')->textArea(['style'=>'width:670px; height:180px;', 'placeholder' => "Enter your feedback here..."])->label('Message'); ?>

    <?= $form->field($feedback, 'Feedback_PicPath')->fileInput()->label('Provide a Screenshot') ?>
    
    <div>
        <?= Html::submitButton('Submit Feedback', ['class' => 'btn btn-primary', 'name' => 'feedback-button', 'style'=>'margin-left: 40%; margin-top: 4%;']); ?>
        <?php ActiveForm::end(); ?>
    </div>
</div>