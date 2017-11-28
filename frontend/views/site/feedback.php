<?php
use common\models\Feedback;
use common\models\Feedbackcategory;
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use kartik\widgets\Select2;
use frontend\assets\FeedbackAsset;

FeedbackAsset::register($this);
?>

<div class = "container-feedback">
    <?php $form = ActiveForm::begin(['id' => 'form-feedback']); ?>

    <div id="feedback_category"> 
        <?= $form->field($feedback, 'Feedback_Category')->widget(Select2::classname(), ['data' => $categoryarray])->label('Category');?>
    </div>

    <div id="feedback_email"> 
        <?php if (Yii::$app->user->isGuest) {
            echo $form->field($feedback, 'User_Username')->textInput(['placeholder' => "Enter your email address here..."])->label('Email');
        } ?>
    </div>

    <div id="feedback_message">               
        <?= $form->field($feedback, 'Feedback_Message')->textArea(['placeholder' => "Enter your feedback here..."])->label('Message'); ?>
    </div>

    <?= $form->field($feedback, 'Feedback_PicPath')->fileInput()->label('Provide a Screenshot') ?>
        
    <div id="feedback_button">
        <?= Html::submitButton('Submit Feedback', ['class' => 'btn btn-primary', 'name' => 'feedback-button']); ?>
    </div>

    <?php ActiveForm::end(); ?>
</div>