<?php

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model \frontend\models\ContactForm */

use yii\helpers\Html;
use yii\helpers\Url;
use yii\bootstrap\ActiveForm;
use yii\captcha\Captcha;
use kartik\widgets\Select2;
use frontend\assets\UserAsset;
use yii\bootstrap\Modal;
use kartik\widgets\FileInput;

$this->title = Yii::t('ticket','Submit Ticket');
UserAsset::register($this);
?>
<div class="ticket">
<div class="container" id="ticketh">
	 <div class="userprofile-header">
        <div class="userprofile-header-title"><?php echo Html::encode($this->title)?></div>
    </div>
   <div class="userprofile-detail">
        <div class="col-sm-2">
            <div class="dropdown-url">
                <?php 
                    echo Select2::widget([
                        'name' => 'url-redirect',
                        'hideSearch' => true,
                        'data' => $link,
                        'options' => [
                            'placeholder' => 'Go To ...',
                            'multiple' => false,

                        ],
                        'pluginEvents' => [
                             "change" => 'function (e){
                                location.href =this.value;
                            }',
                        ]
                    ])
                ;?>
            </div>
            <div class="nav-url">
                <ul class="nav nav-pills nav-stacked">
                    <li role="presentation"><?php echo Html::a(Yii::t('common','All'),['/ticket/index'],['class'=>'btn-block userprofile-edit-left-nav'])?></li>
                    <li role="presentation" class="active"><a href="#" class="btn-block userprofile-edit-left-nav"><?= Yii::t('ticket','Submit Ticket') ?></a></li>
				    <li role="presentation"><?php echo Html::a(Yii::t('ticket','Completed Ticket'),['/ticket/completed'],['class'=>'btn-block userprofile-edit-left-nav'])?></li>
                </ul>
            </div>
        </div>
 </div>
    <div class="container">
     <div class="col-sm-8 right-side">
	 <p style="text-align:center; padding-top:20px;">
        <?= Yii::t('ticket','If you have business inquiries or other questions, please fill out the following form to contact us. Thank you.') ?>
    </p><br>
            <?php $form = ActiveForm::begin(); ?>
                <?= $form->field($model, 'Ticket_Subject')->textInput(['autofocus' => true])->label(Yii::t('ticket','Subject Title')) ?>
                <?= $form->field($model, 'Ticket_Category')->dropDownList($data)->label(Yii::t('ticket','Ticket Category')) ?>

                <?= $form->field($model, 'Ticket_Content')->textarea(['rows' => 6])->label(Yii::t('ticket','Content')) ?>

                <?php echo $form->field($upload, 'imageFile')->widget(FileInput::classname(), ['options' => ['accept' => 'image/*'],])->label(Yii::t('common','Upload Image'));?>

                <div class="form-group" id="submit-ticket">
                    <?= Html::submitButton(Yii::t('common','Submit'), ['class' => 'raised-btn main-btn submit-resize-btn', 'name' => 'contact-button']) ?>
                </div>

            <?php ActiveForm::end(); ?>
        </div>
    </div>

</div>
</div>
