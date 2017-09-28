<?php

/* @var $this yii\web\View */
use yii\bootstrap\ActiveForm;
use yii\helpers\Html;

$this->title = 'hamsterEat';
?>

<body>
<div class="site-index">

    
        <div >

        <div class="mySlides">
            <img src="SysImg/Img1-1200x400.jpg" >
        </div>
    
         </div>

        <div id="SSCrow2" class="container">
      <!--    <div class="col-xs-12 col-sm-12 col-md-6 col-lg-10 col-xs-offset-0 col-sm-offset-0 col-md-offset-3 col-lg-offset-3 " >
              -->
        <div class="col-md-8 col-md-offset-2">  
		
            <div class="loginform">
        <h1>Select Your Location</h1>
        <?php if($postcode['detectArea'] == 0) :?>
        <?php $form = ActiveForm::begin(['id' => 'area']); ?>
        <?php else :?>
        <?php $form = ActiveForm::begin(['action' =>['site/search-restaurant-by-area'],'id' => 'area']); ?>
        <?php endif ;?>
        <?= $form->field($postcode, 'Area_Postcode')->textInput(['autofocus' => true])->label('Postcode') ?>
        <?php if( $postcode['detectArea'] == 1) :?>
        <?= $form->field($postcode, 'Area_Area')->dropDownList($list) ?>
        <?php endif ;?>
        <?= Html::submitButton('Proceed', ['class' => 'btn btn-primary', 'name' => 'proceed-button']) ?>
        </div>
        </div>
        <!-- </div> -->
        </div>
        
        <?php ActiveForm::end(); ?>
    </div>
    
<div id="DescContainer" class="container">
    <div class="body-content">

        <div class="row">
            <div class="col-lg-4" style="text-align:center">
              <span class="fa-stack fa-4x">
                        <i class="fa fa-circle fa-stack-2x text-primary" style="color:orange;"></i>
                         <i class="fa fa-cutlery fa-stack-1x fa-inverse"></i>
                    </span>
                    <h4 class="service-heading">Convenient</h4>  
                <p><a class="btn btn-default" href="../web/index.php?r=site%2Fabout">Find Out More &raquo;</a></p>
            </div>
            <div class="col-lg-4" style="text-align:center">
			<span class="fa-stack fa-4x">
                        <i class="fa fa-circle fa-stack-2x text-primary" style="color:orange;"></i>
                         <i class="fa fa-credit-card fa-stack-1x fa-inverse"></i>
                    </span>
                    <h4 class="service-heading">Easy Payment</h4> 
                <p><a class="btn btn-default" href="../web/index.php?r=site%2Fabout">Find Out More &raquo;</a></p>
             </div>
            <div class="col-lg-4" style="text-align:center">
			<span class="fa-stack fa-4x">
                        <i class="fa fa-circle fa-stack-2x text-primary" style="color:orange;"></i>
                         <i class="fa fa-clock-o fa-stack-1x fa-inverse"></i>
                    </span>
                    <h4 class="service-heading">High Efficiency</h4> 
               <p><a class="btn btn-default" href="../web/index.php?r=site%2Fabout">Find Out More &raquo;</a></p>
            </div>
        </div>

    </div>
    </div>
</div>
