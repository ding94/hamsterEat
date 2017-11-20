<?php
use yii\helpers\Html;
use yii\helpers\Url;
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
	<div>
		<ul>
			<li><input type="radio" id="f-option" name="selector">
    			<label for="f-option">Pizza</label>
    
    			<div class="check"></div>
    		</li>
		</ul>
	</div>
</div>