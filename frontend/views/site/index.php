<?php

/* @var $this yii\web\View */
use kartik\widgets\ActiveForm;
use yii\helpers\Html;
use kartik\widgets\Select2;

$this->title = 'hamsterEat';
?>

<body>
<link href="css/style.css" rel="stylesheet">	
<!--<div class="site-index">-->      
 <header class="intro-header">
 <div id="SlideShowContainer" class="container-fluid">
		<div id="SSCrow1" class="row">
			<div class="mySlides">

			<img src="SysImg/1.jpg" style="width:100%;height:670px !important;">

			</div>

			<div class="mySlides">

			<img src="SysImg/2.jpg" style="width:100%; height:670px !important;">

			</div>

			<div class="mySlides">

			<img src="SysImg/3.jpg" style="width:100%;height:670px !important;">

			</div>

			<div class="mySlides">

			<img src="SysImg/4.jpg" style="width:100%;height:670px !important;">

			</div>
			
			<div class="dotContainer w3-display-bottommiddle" >
			<span id="dot1" class="dot" onclick="currentSlide(1)"></span>
			<span id="dot2"class="dot" onclick="currentSlide(2)"></span>
			<span id="dot3"class="dot" onclick="currentSlide(3)"></span>
			<span id="dot4" class="dot" onclick="currentSlide(4)"></span>
			</div> 
		
 <!--<div class="container"> -->
      <!-- <div class="col-xs-12 col-sm-12 col-md-6 col-lg-10 col-xs-offset-0 col-sm-offset-0 col-md-offset-3 col-lg-offset-3 " >-->
      <!--  <div class="col-md-8 col-md-offset-2"> -->
        <div class="form">
        <h1>Select Your Location</h1>
        <?php if($postcode['detectArea'] == 0) :?>
        <?php $form = ActiveForm::begin(['id' => 'area']); ?>
        <?php else :?>
        <?php $form = ActiveForm::begin(['action' =>['site/search-restaurant-by-area'],'id' => 'area']); ?>
        <?php endif ;?>
        <?= $form->field($postcode, 'Area_Postcode')->widget(Select2::classname(), [
	    'data' => $postcodeArray,
	    'options' => ['placeholder' => 'Select a postcode ...'],
	    'pluginOptions' => [
	        'allowClear' => true
	    ],
	]); ?>
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
	</div>
      </header>
	  
<!--<div id="DescContainer" class="container">-->
	<div id="SSCrow2" class="row">
   <div class="container">
		
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
								<a href="site%2Fabout" alt="">View more</a> 
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
								<a href="site%2Fabout" alt="">View more</a> 
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
								<a href="site%2Fabout" alt="">View more</a> 
							</div>
						</div>
					</div>
				</div>
				
			</div>
		</div>
	</div>


   <!-- </div> -->
   
