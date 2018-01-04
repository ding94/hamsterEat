<?php

/* @var $this yii\web\View */
use kartik\widgets\ActiveForm;
use yii\helpers\Html;
use kartik\widgets\Select2;
use kartik\widgets\DepDrop;
use yii\helpers\Url;
use yii\bootstrap\Modal;
use frontend\assets\PhotoSliderAsset;

PhotoSliderAsset::register($this);
$this->title = 'Delivery Food In Medini | HamsterEat';
?>

<style>

.expansion
{
	float:right;
	margin-top:10px;
}

</style>
<body>
<!--<div class="site-index">--> 
<header class="intro-header">

<div id="SlideShowContainer" class="container-fluid">
		<div id="SSCrow1" class="row">
			<?php
			  foreach ($banner as $k => $banners) {
			  ?>
			    <a href="<?php echo $banners['redirectUrl'] ?>" target="_blank"><?= Html::img('@web/'.$banners['name'], ['class'=>'mySlides', 'title' => $banners['title']]);?></a>
			  <?php
			    }
			  ?>
			<div class="dotContainer w3-display-bottommiddle" >
			<?php foreach ($banner as $k => $banners) {
			    $k += 1;
			?>
			<span id="dot<?php echo $k ?>" class="dot" onclick="currentSlide('<?php echo $k ?>')"></span>
			<?php } ?>
		</div> 
    </div>
</div>
</header>
	<div id="SSCrow2" class="container">
        <div class="form">
	<!--	<h1>Light up your taste buds!</h1><br>-->
        <h3 id="h3"><b><center>Order Lunch Delivery</center></b></h3>
		<h5 id="h5"><center>Exclusively catered for office users in Medini 6/7!</center></h5><br>
        <?php //$form = ActiveForm::begin(); ?>
        <?php //echo  Select2::widget([
		    
		    // 'name' => 'area',
		    // 'data' => $postcodeArray,
		    // 'options' => ['placeholder' => 'Select an Area ...'],
		    // 'pluginOptions' => [
		        // 'allowClear' => true
		    // ],
		// ]); ?>

        <?php //Html::submitButton('Find Restaurants', ['class' => 'button-three']) ?>
		<!-- <div class ="expansion"> -->
			<?php //Html::a('<u>I don&#39;t see my area..</u>', ['request-area', ['style'=>'margin-left:2000px;']]) ?>
		<!-- </div> -->
		
		<?php //ActiveForm::end(); ?>
		<?php $form = ActiveForm::begin(); ?>
			<?= Html::submitButton('Show Restaurants', ['class' => 'button-three']); ?>
		<?php ActiveForm::end(); ?>
	   	</div>
	</div>
<!--<div id="DescContainer" class="container">-->
	<div id="SSCrow4">
		<div class="container">
			<div id="box1" class="index-content">
				<div class="graphic">
					Graphic Content 1
				</div>	
				<div class="textcontainer">
					<p>Find food from your favourite restaurants around Bukit Indah.</p>
				</div>
				<button class="raised-btn main-btn" onclick="moveToBox2()">
		        	<svg viewBox="0 0 64 64" width="24px" height="24px">
		        		<path fill-rule="evenodd" clip-rule="evenodd" d="M59.927 31.985l.073.076-16.233 17.072-3.247-3.593L51.324 34H4v-4h47.394L40.52 18.407l3.247-3.494L60 31.946l-.073.039z"></path>
		        	</svg>
		      	</button>
			</div>
			<div id="box2" class="index-content">
				<div class="graphic">
					Graphic Content 2
				</div>	
				<div class="textcontainer">
					<p>Add food to cart, enter delivery details and place order.</p>
				</div>
				<button class="raised-btn main-btn" onclick="moveToBox3()">
		        	<svg viewBox="0 0 64 64" width="24px" height="24px">
		        		<path fill-rule="evenodd" clip-rule="evenodd" d="M59.927 31.985l.073.076-16.233 17.072-3.247-3.593L51.324 34H4v-4h47.394L40.52 18.407l3.247-3.494L60 31.946l-.073.039z"></path>
		        	</svg>
		      	</button>
			</div>
			<div id="box3" class="index-content">
				<div class="graphic">
					Graphic Content 3
				</div>	
				<div class="textcontainer">
					<p>Food is prepared and delivered to you during lunch time.</p>
				</div>
				<button class="raised-btn main-btn" onclick="moveToBox1()">
		        	<svg viewBox="0 0 64 64" width="24px" height="24px">
		        		<path fill-rule="evenodd" clip-rule="evenodd" d="M59.927 31.985l.073.076-16.233 17.072-3.247-3.593L51.324 34H4v-4h47.394L40.52 18.407l3.247-3.494L60 31.946l-.073.039z"></path>
		        	</svg>
		      	</button>
			</div>
		</div>
	</div>
<div id="SSCrow3">
   <div class="container">
		
			<div class="boxs">				
				<div class="col-md-4">
					<div class="wow bounceIn" data-wow-offset="0" data-wow-delay="1.3s">
						<div class="align-center">
							<h4>About Us</h4>					
							<div class="icon">
								<i class="fa fa-cutlery fa-3x"></i>
							</div>
							<p>
							Let's explore with HamsterEat! Wanna know more? Click below!
							</p>
							<div class="ficon">
								<p><?= Html::a('View', ['site/about'],['class' => "button-one"]) ?> </p>
							</div>
						</div>
					</div>
				</div>
				<div class="col-md-4">
					<div class="wow bounceIn" data-wow-offset="0" data-wow-delay="1.3s">
						<div class="align-center">
							<h4>Guide</h4>				
							<div class="icon">
								<i class="fa fa-question fa-3x" aria-hidden="true" ></i>
							</div>
							<p>
							 Not sure what to do? No worry! Kindly view more for details!
							</p>
							<div class="ficon">
								<p><?= Html::a('View', ['/site/faq'],['class' => "button-one"]) ?> </p>
							</div>
						</div>
					</div>
				</div>
				<div class="col-md-4">
					<div class="wow bounceIn" data-wow-offset="0" data-wow-delay="1.3s">
						<div class="align-center">
							<h4>Help</h4>					
							<div class="icon">
								<i class="fa fa-thumbs-o-up fa-3x"></i>
							</div>
							<p>
							 Need help? We provide you the best services!
							</p>
							<div class="ficon">
								<p><?= Html::a('View', ['site/about'],['class' => "button-one"]) ?> </p>
							</div>
						</div>
					</div>
				</div>
				
			</div>
		</div>
	</div>