<?php

/* @var $this yii\web\View */
use yii\bootstrap\ActiveForm;
use yii\helpers\Html;

$this->title = 'hamsterEat';
?>

<body>
<link href="css/style.css" rel="stylesheet">	
<!--<div class="site-index">-->      
 <header class="intro-header">
 <!--<div class="container"> -->
      <!-- <div class="col-xs-12 col-sm-12 col-md-6 col-lg-10 col-xs-offset-0 col-sm-offset-0 col-md-offset-3 col-lg-offset-3 " >-->
      <!--  <div class="col-md-8 col-md-offset-2"> -->
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
        <!-- </div> -->
        <!-- </div> -->
		<!-- </div> -->
       
	   <?php ActiveForm::end(); ?>
    </div>
      </header>
	  
<!--<div id="DescContainer" class="container">-->
   <div class="container">
		<div class="row">
			<div class="boxs">				
				<div class="col-md-4">
					<div class="wow bounceIn" data-wow-offset="0" data-wow-delay="0.8s">
						<div class="align-center">
							<h4>Convenient</h4>					
							<div class="icon">
								<i class="fa fa-cutlery fa-3x"></i>
							</div>
							<p>
							You can order food whenever and wherever you are!
							</p>
							<div class="ficon">
								<a href="../web/index.php?r=site%2Fabout" alt="">View more</a> 
							</div>
						</div>
					</div>
				</div>
				<div class="col-md-4">
					<div class="wow bounceIn" data-wow-offset="0" data-wow-delay="1.3s">
						<div class="align-center">
							<h4>Easy Payment</h4>				
							<div class="icon">
								<i class="fa fa-credit-card fa-3x"></i>
							</div>
							<p>
							 You can select your favourite payment method!
							</p>
							<div class="ficon">
								<a href="../web/index.php?r=site%2Fabout" alt="">View more</a> 
							</div>
						</div>
					</div>
				</div>
				<div class="col-md-4">
					<div class="wow bounceIn" data-wow-offset="0" data-wow-delay="1.3s">
						<div class="align-center">
							<h4>High Efficiency</h4>					
							<div class="icon">
								<i class="fa fa-thumbs-o-up fa-3x"></i>
							</div>
							<p>
							 We provide you the best services!
							</p>
							<div class="ficon">
								<a href="../web/index.php?r=site%2Fabout" alt="">View more</a> 
							</div>
						</div>
					</div>
				</div>
				
			</div>
		</div>
	</div>

   <!-- </div> -->
