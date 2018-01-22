<?php

/* @var $this yii\web\View */

use yii\helpers\Html;

$this->title = Yii::t('site','About Us').' | HamsterEat.my';
?>

<link href='https://fonts.googleapis.com/css?family=Quicksand|Asap:700' rel='stylesheet' type='text/css'>
<div class="container" id="about-container">
  <section class="header-section">
    <div class="header-img-div">
      <img src="<?php echo Yii::$app->params['sysimg'] ?>/Icon.png" alt="" style="width: 50%;">
    </div>
    <div class="header-text-div">
      <h1>HamsterEat – <?= Yii::t('site','Lunch Delivery to Offices in Medini') ?></h1>
    </div>
  </section>
  <section class="col-md-5">
    <h3><?= Yii::t('site','Who are we?') ?></h3>
    <p>
      As a young and dynamic IT team working in an e-commerce (SGshop), we always face one challenge – going out for food within one-hour lunch time. With 3 years’ experience in online shopping, we told ourselves that since we can already deliver parcels from China to Malaysia, why can’t we deliver food from restaurants to our own office? Hence HamsterEat was created.
      <?= Yii::t('site','About1') ?>
    </p>
  </section>
  <section class="col-md-5">
    <h3><?= Yii::t('site','What we do?') ?></h3>
    <p>
      HamsterEat aims to provide the easiest-to-use food delivery service in Medini City, to serve customers with delicious food during office lunch time. We want to Make Lunch Great Again in Medini, with our lunch delivery services which has already covered Malay, Mamak and Chinese cuisine, we will continue the hard work to pleasure everyone’s taste bud!
      <?= Yii::t('site','About2') ?>
    </p>
  </section>
  <section class="col-md-5">
    <h3>
      <?= Yii::t('site','About Us') ?>
    </h3>
    <p>
      <span class="secondary-header"><?= Yii::t('site','Registered Company Name') ?>:</span>
      SGshop Ecommerce Sdn Bhd
    </p>
    <p>
      <span class="secondary-header"><?= Yii::t('site','Address') ?>:</span>
      B-GF-05, Medini 6, Jalan Medini Sentral 5,
      Bandar Medini Iskandar Malaysia, 
      79250 Iskandar Puteri, Johor.
    </p>
    <p>
      <span class="secondary-header"><?= Yii::t('site','Operating Hour') ?>:</span>
      Monday to Friday 10:00am – 2:00pm
      Closed on weekend and public holiday
    </p>
  </section>
  <section class="col-md-5">
    <h3>
      Contact Us
    </h3>
    <p>
      <span class="secondary-header"><?= Yii::t('site','Customer Support Hotline') ?>:</span>
      1700 818 315
    </p>
    <p>
      <span class="secondary-header"><?= Yii::t('site','Customer Support Email') ?>:</span>
      support@HamsterEat.my
    </p>
    <p>
      <span class="secondary-header"><?= Yii::t('site','Business Contact Email') ?>:</span>
      business@HamsterEat.my
    </p>
  </section>
</div>
