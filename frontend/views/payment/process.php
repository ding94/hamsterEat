<?php
use yii\helpers\Html;
use yii\helpers\Url;
use yii\bootstrap\ActiveForm;
use frontend\assets\PaymentAsset;

$this->title = 'Process Payment';
PaymentAsset::register($this);
?>
<div class="container">
       <div class="checkout-progress-bar">
         <div class="circle done">
           <span class="label"><i class="fa fa-check"></i></span>
           <span class="title">Cart</span>
         </div>
         <span class="bar done"></span>
         <div class="circle done">
           <span class="label"><i class="fa fa-check"></i></span>
           <span class="title">Checkout</span>
         </div>
         <span class="bar done"></span>
         <div class="circle active">
           <span class="label"><i class="fa fa-credit-card"></i></span>
           <span class="title">Payment</span>
         </div>
       </div> 
    </div>
<div class="container payment">
	<div class="payment-header">
		<div class="payment-title">
			Total Payment: RM <?php echo $order->Orders_TotalPrice?>
		</div>
	</div>
	<div class="payment-detail">
		<h3>Current Payment Method:</h3>
		<?php $form = ActiveForm::begin(['action' => ['/payment/payment-post']]); ?>
		<ul class="payment-selection">
			<li>
        <input type="radio" id="account-balance" name="account-balance" value="1">
          <label for="account-balance">Use Account Balance</label>
          <span class="pull-right">Your Current Balance : RM<?php echo $balance->User_Balance ?></span>
          <div class="check"></div>
        </li>
        <li>
        <input type="radio" id="cash-on" name="account-balance" value="2">
          <label for="cash-on">Back to COD</label>
          <span class="pull-right">*COD = Cash On Delivery</span>
          <div class="check"></div>
        </li>
		</ul>
		<?php echo Html::hiddenInput('did', $order->Delivery_ID);?>
		<div class="button-div">
			<?php echo Html::submitButton('Submit', ['class' => 'raised-btn main-btn payment-button']);?>
		</div>
		<?php ActiveForm::end(); ?>
	</div>
</div>