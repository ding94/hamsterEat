<?php
use yii\helpers\Html;
use yii\helpers\Url;
use frontend\assets\AppAsset;

/* @var $this yii\web\View */
/* @var $user common\models\User */
$confirmLink = Url::to(['order/update-pickedup'],true);
$paymentMethod = $item['Orders_PaymentMethod'];
$id = $item['Delivery_ID'];
$time = $item['Orders_Time'];
$date = $item['Orders_Date'];
$price = $item['Orders_TotalPrice'];

AppAsset::register($this);
?>
<div class="ontheway">

	<hr style="width:40%; border-bottom: 1px solid grey;" >

	<?php if($paymentMethod == 'Cash on Delivery'):?>
		<p>Your Delivery ID <?php echo $id?> that you requested to receive at <?php echo $time?> on <?php echo $date?> is now on its way to the designated destination. Please prepare a total of RM <?php echo $price?> for our rider.</p>
	<?php else :?>
		<p>Your Delivery ID <?php echo $id?> that you requested to receive at <?php echo $time?> on <?php echo $date?> is now on its way to the designated destination. </p>
	<?php endif ;?>

 
</div>
