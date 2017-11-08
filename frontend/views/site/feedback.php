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
    height: 450px;
    padding-top: 20px;
    width: 700px;
    margin-left:33px;
    padding-left:14px;
}

</style>
<div class = "container-feedback">
    <?php $form = ActiveForm::begin(['id' => 'form-feedback']); ?>

    <?= $form->field($feedback, 'Feedback_Category')->dropDownList($categoryarray, ['style'=>'width:350px'])->label('Category'); ?>
                    
    <?= $form->field($feedback, 'Feedback_Message')->textInput(['style'=>'width:670px; height:180px;', 'placeholder' => "Enter your feedback here..."])->label('Message'); ?>
    
    <div>
        <?= Html::submitButton('Submit Feedback', ['class' => 'btn btn-primary', 'name' => 'feedback-button', 'style'=>'margin-left: 40%; margin-top: 2%;']); ?>
        <?php ActiveForm::end(); ?>
    </div>
</div>

