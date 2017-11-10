<?php
use yii\helpers\Html;
use yii\helpers\Url;
use frontend\assets\AppAsset;

/* @var $this yii\web\View */
/* @var $user common\models\User */
$confirmLink = Url::to(['order/update-pickedup'],true);
AppAsset::register($this);
?>
<div class="ontheway">

	<hr style="width:40%; border-bottom: 1px solid grey;" >
	<?php
	foreach($sql12 as $row):
	if($row['Orders_PaymentMethod']=='Cash on Delivery')
	{
	
	echo "<p>Your Delivery ID ".$row['Delivery_ID']." that you requested to receive at ".$row['Orders_Time']." and ".$row['Orders_Date']." is now on its way to the designated destination. Please prepare a total of Rm ".$row['Orders_TotalPrice']." for our rider.</p>";
	}
	else
	{
	echo "<p>Your Delivery ID ".$row['Delivery_ID']." that you requested to receive at ".$row['Orders_Time']." and ".$row['Orders_Date']." is now on its way to the designated destination.</p>";
	}
	endforeach;
?>
 
</div>
