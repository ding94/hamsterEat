<?php
use yii\helpers\Html;
use yii\helpers\Url;
use yii\bootstrap\ActiveForm;
use frontend\assets\PaymentAsset;

$this->title = Yii::t('payment','Process Payment');
PaymentAsset::register($this);
?>
<div class="container">
       <div class="checkout-progress-bar">
         <div class="circle done">
           <span class="label"><i class="fa fa-check"></i></span>
           <span class="title"><?= Yii::t('common','Cart') ?></span>
         </div>
         <span class="bar done"></span>
         <div class="circle done">
           <span class="label"><i class="fa fa-check"></i></span>
           <span class="title"><?= Yii::t('common','Checkout') ?></span>
         </div>
         <span class="bar done"></span>
         <div class="circle active">
           <span class="label"><i class="fa fa-credit-card"></i></span>
           <span class="title"><?= Yii::t('common','Payment') ?></span>
         </div>
       </div> 
    </div>
<div class="container payment">
	<div class="payment-header">
		<div class="payment-title">
			<?= Yii::t('payment','Total Payment') ?>: RM <?php echo $order->Orders_TotalPrice?>
		</div>
	</div>
	<div class="payment-detail">
		<h3><?= Yii::t('payment','Current Payment Method') ?>:</h3>
		<?php $form = ActiveForm::begin(['action' => ['/payment/payment-post']]); ?>
		<ul class="payment-selection">
			<li>
        <input type="radio" id="account-balance" name="account-balance" value="1">
          <label for="account-balance"><?= Yii::t('payment','Use Account Balance') ?></label>
          <span class="pull-right"><?= Yii::t('payment','Your Current Balance') ?> : RM<?php echo $balance->User_Balance ?></span>
          <div class="check"></div>
        </li>
        <li>
        <input type="radio" id="cash-on" name="account-balance" value="2">
          <label for="cash-on"><?= Yii::t('payment','Back to COD') ?></label>
          <span class="pull-right"><?= Yii::t('payment','*COD = Cash On Delivery') ?></span>
          <div class="check"></div>
        </li>
		</ul>
		<?php echo Html::hiddenInput('did', $order->Delivery_ID);?>
		<div class="button-div">
			<?php echo Html::submitButton(Yii::t('common','Submit'), ['class' => 'raised-btn main-btn payment-button']);?>
		</div>
		<?php ActiveForm::end(); ?>
	</div>
</div>