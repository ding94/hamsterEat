<?php
use yii\helpers\Url;
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;

?>
<style type="text/css">
	.table-striped tbody tr:hover
	{
		background-color: #99ccff;
	}

</style>
<div>
	<?php $form = ActiveForm::begin(); ?>
	<table class="table table-striped">
		<tr>
			<th>Order ID:</th>
			<th><?= $orderitem['Order_ID'] ?></th>
		</tr>
		<tr>
			<th>Delivery ID:</th>
			<th><?= $order['Delivery_ID'] ?></th>
		</tr>
		<tr>
			<td>Time Place Order:</td>
			<td><?= date('Y-m-d h:i:s',$order['Orders_DateTimeMade']) ?></td>
		</tr>
		<tr>
			<td>Total Refunded:</td>
			<td>RM <?= number_format($order['Orders_TotalPrice'],2) ?></td>
		</tr>
	</table>

	<?php ActiveForm::end(); ?>
</div>