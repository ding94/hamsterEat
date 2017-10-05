<?php

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model \frontend\models\ContactForm */

use yii\helpers\Html;
use yii\grid\GridView;
use yii\grid\ActionColumn;

$this->title = 'Delivery Sign In';
?>

<div class="col-md-2">
    <div>Today Task!!</div>
    <div>Empty</div>
</div>
<div class="col-md-9" >
    <?php if($record->result == 1):?>
        <?=Html::a('Alreay Sign In',['daily-sign-in/signin'],['class' => 'btn btn-primary btn-lg col-md-offset-9', 'disabled' =>"true"]);?>
    <?php else :?>
      <?=Html::a('Sign In',['daily-sign-in/signin'],['class' => 'btn btn-primary btn-lg col-md-offset-9']);?>
    <?php endif ;?>
</div>
