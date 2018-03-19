<?php
use yii\helpers\Html;
use yii\helpers\Url;
use frontend\assets\AppAsset;

/* @var $this yii\web\View */
/* @var $user common\models\User */
$link = Url::to(['/order/order-details','did'=>$did],true);
//$message = 
AppAsset::register($this);
?>
<div class="password-reset">
	<h1><?php echo $message;?></h1>
	<hr style="width:40%; border-bottom: 1px solid grey;" >
    <p>For More Detail : </p>
    <div class="verify-button"><?= Html::a('Press It', $link) ?></div>
</div>
