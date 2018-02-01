<?php

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model \frontend\models\SignupForm */

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;

$this->title = Yii::t('common','Signup');
?>
<div class="site-signup">
     <div class="col-lg-6 col-lg-offset-3" style="text-align:center">
    <h1><?= Html::encode($this->title) ?></h1>

    <p><?= Yii::t('site','Please select the following fields to signup') ?>:</p><br>
  </div>
    <div class="container">
  <div class="row1">
            <div class="col-lg-4" style="text-align:center">
              <span class="fa-stack fa-4x">
                        <i class="fa fa-circle fa-stack-2x text-primary" style="color:orange;"></i>
                         <i class="fa fa-user-o fa-stack-1x fa-inverse"></i>
                    </span>
                    <h4 class="service-heading"><?= Yii::t('site','Customer') ?></h4>  
                <p><?= Html::a(Yii::t('site','Sign Up').' &raquo;', ['site/signup'],['class' => "raised-btn btn-default"]) ?></p>
            </div>
            <div class="col-lg-4" style="text-align:center">
			<span class="fa-stack fa-4x">
                        <i class="fa fa-circle fa-stack-2x text-primary" style="color:orange;"></i>
                         <i class="fa fa-black-tie fa-stack-1x fa-inverse"></i>
                    </span>
                    <h4 class="service-heading"><?= Yii::t('site','Restaurant Manager') ?></h4> 
                <p><?= Html::a(Yii::t('site','Sign Up').' &raquo;', ['site/rmanager'],['class' => "raised-btn btn-default"]) ?> </p>
             </div>
            <div class="col-lg-4" style="text-align:center">
			<span class="fa-stack fa-4x">
                        <i class="fa fa-circle fa-stack-2x text-primary" style="color:orange;"></i>
                         <i class="fa fa-motorcycle fa-stack-1x fa-inverse"></i>
                    </span>
                    <h4 class="service-heading"><?= Yii::t('site','Delivery Man') ?></h4> 
               <p><?= Html::a(Yii::t('site','Sign Up').' &raquo;', ['site/deliveryman'],['class' => "raised-btn btn-default"]) ?> </p>
            </div>
        </div>

     
        </div>
    </div>

