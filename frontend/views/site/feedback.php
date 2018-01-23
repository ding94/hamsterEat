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
        <?= $form->field($feedback, 'Feedback_Category')->widget(Select2::classname(), ['data' => $categoryarray])->label(Yii::t('common','Category'));?>
    </div>

    <div id="feedback_email"> 
        <?php if (Yii::$app->user->isGuest) {
            echo $form->field($feedback, 'User_Username')->textInput(['placeholder' => "Enter your email address here..."])->label(Yii::t('common','Email'));
        } ?>
    </div>

    <div id="feedback_message">               
        <?= $form->field($feedback, 'Feedback_Message')->textArea(['placeholder' => "Enter your feedback here..."])->label(Yii::t('site','Message')); ?>
    </div>

    <?= $form->field($feedback, 'Feedback_PicPath')->fileInput()->label(Yii::t('site','Provide a Screenshot')) ?>
        
    <div id="feedback_button">
        <?= Html::submitButton(Yii::t('site','Submit Feedback'), ['class' => 'raised-btn main-btn', 'name' => 'feedback-button']); ?>
    </div>

    <?php ActiveForm::end(); ?>
</div>