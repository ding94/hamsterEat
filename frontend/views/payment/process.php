<?php
use yii\helpers\Html;
use yii\helpers\Url;
use yii\bootstrap\ActiveForm;
use frontend\assets\PaymentAsset;

$this->title = 'Process Payment';
PaymentAsset::register($this);
?>

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
				<input type="radio" id="account-balance" name="type" value="1">
    			<label for="account-balance">Use Account Balance</label>
    			<span class="pull-right">Your Current Balance : RM<?php echo $balance->User_Balance ?></span>
    			<div class="check"></div>
    		</li>
		</ul>
		<?php echo Html::hiddenInput('did', $order->Delivery_ID);?>
		<div class="button-div">
			<?php echo Html::submitButton('Make A Payment', ['class' => 'btn btn-primary payment-button']);?>
		</div>
		<?php ActiveForm::end(); ?>
	</div>
</div>