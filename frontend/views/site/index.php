<?php

/* @var $this yii\web\View */
use kartik\widgets\ActiveForm;
use yii\helpers\Html;
use kartik\widgets\Select2;
use kartik\widgets\DepDrop;
use yii\helpers\Url;

$this->title = 'hamsterEat';
?>

<body>
<link href="css/style.css" rel="stylesheet">
<script src="//ajax.googleapis.com/ajax/libs/jquery/1.11.0/jquery.min.js"></script>
<!--<div class="site-index">--> 
 <header class="intro-header">
 <div id="SlideShowContainer" class="container-fluid">
		<div id="SSCrow1" class="row">
			<div class="mySlides">

			<img src="SysImg/15.jpeg" style="width:100%;height:670px !important;">

			</div>

			<div class="mySlides">

			<img src="SysImg/7.jpeg" style="width:100%; height:670px !important;">

			</div>

			<div class="mySlides">

			<img src="SysImg/14.jpeg" style="width:100%;height:670px !important;">

			</div>

			<div class="mySlides">

			<img src="SysImg/13.jpeg" style="width:100%;height:670px !important;">

			</div>
			
			<div class="mySlides">

			<img src="SysImg/11.jpeg" style="width:100%;height:670px !important;">

			</div>
			
			<div class="dotContainer w3-display-bottommiddle" >
			<span id="dot1" class="dot" onclick="currentSlide(1)"></span>
			<span id="dot2"class="dot" onclick="currentSlide(2)"></span>
			<span id="dot3"class="dot" onclick="currentSlide(3)"></span>
			<span id="dot4" class="dot" onclick="currentSlide(4)"></span>
			<span id="dot5" class="dot" onclick="currentSlide(5)"></span>
			</div> 
		

        <div class="form">
		<h1>Light up your taste buds!</h1><br>
        <h3><b>Select Your Location</b></h3>
		<h5>To Better Serve You, let us know where you are by selecting your postal code & area!</h5><br>


        <?php $form = ActiveForm::begin(['type' => ActiveForm::TYPE_HORIZONTAL]); ?>

        <?= $form->field($postcode, 'Area_Postcode')->widget(Select2::classname(), [
	    'data' => $postcodeArray,
	    'options' => ['placeholder' => 'Select a postcode ...','id'=>'postcode-select']])->label(false); 
	    ?>
		<?= $form->field($postcode,'Area_Area')->widget(DepDrop::classname(), [
			'type'=>DepDrop::TYPE_SELECT2,
			'options' => ['id'=>'area-select'],
			'pluginOptions'=>[
				'depends'=>['postcode-select'],
				'url'=>Url::to(['/site/get-area'])
			],
			])->label(false); ?>

        <?= Html::submitButton('Find Restaurants', ['class' => 'button-three', 'name' => 'proceed-button']) ?>
        </div>
       
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
								<p><?= Html::a('View more', ['site/about'],['class' => "button-one"]) ?> </p>
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
								<p><?= Html::a('View more', ['site/about'],['class' => "button-one"]) ?> </p>
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
								<p><?= Html::a('View more', ['site/about'],['class' => "button-one"]) ?> </p>
							</div>
						</div>
					</div>
				</div>
				
			</div>
		</div>
	</div>


   <!-- </div> -->
   
